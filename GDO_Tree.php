<?php
declare(strict_types=1);
namespace GDO\Category;

use GDO\Core\GDO;
use GDO\Core\GDT;
use GDO\Core\GDT_Index;
use GDO\Core\GDT_Int;
use GDO\Core\GDT_Object;
use GDO\Core\GDT_String;

/**
 * Abstract Tree class stolen from sitepoint.
 * To select a partial of the tree, look for child-items that have a LEFT between parent left and right.
 *
 * @see http://articles.sitepoint.com/article/hierarchical-data-database/3
 *
 * @author gizmore
 * @version 7.0.3
 * @since 6.2.0
 */
abstract class GDO_Tree extends GDO
{

	/**
	 * @var self[]
	 */
	public array $children;

	public function gdoAbstract(): bool
	{
		return $this->gdoClassName() === self::class;
	}

	###########
	### GDO ###
	###########

	public function gdoColumns(): array
	{
		$pre = $this->gdoTreePrefix();
		return [
			GDT_Object::make("{$pre}_parent")->table(GDO::tableFor($this->gdoClassName())),
			GDT_String::make("{$pre}_path")->ascii()->caseS()->max(128),
			GDT_Int::make("{$pre}_depth")->unsigned()->bytes(1),
			GDT_Int::make("{$pre}_left")->unsigned(),
			GDT_Int::make("{$pre}_right")->unsigned(),
			GDT_Index::make("{$pre}_left_index")->btree()->indexColumns("{$pre}_left"),
			GDT_Index::make("{$pre}_parent_index")->btree()->indexColumns("{$pre}_parent"),
		];
	}

	public function gdoTreePrefix(): string
	{
		return 'tree';
	}

	public function getParent(): ?self
	{
		return $this->gdoValue($this->getParentColumn());
	}

	public function getParentColumn(): string
	{
		return $this->gdoTreePrefix() . '_parent';
	}

	public function getPath(): ?string
	{
		return $this->gdoVar($this->getPathColumn());
	}

	public function getIDColumn(): string
	{
		return $this->gdoPrimaryKeyColumn()->getName();
	}

	public function getPathColumn(): string
	{
		return $this->gdoTreePrefix() . '_path';
	}

	/**
	 * @return self[]
	 */
	public function getChildren(): array
	{
		if (!isset($this->children))
		{
			if ($this->isPersisted())
			{
				$this->children =
					self::table()->select()->
					where("{$this->getParentColumn()}={$this->getID()}")->
					exec()->fetchAllObjects();
			}
			else
			{
				return GDT::EMPTY_ARRAY;
			}
		}
		return $this->children;
	}

	/**
	 * Get sub tree.
	 * @return self[]
	 */
	public function getTree(): array
	{
		$left = $this->gdoTreePrefix() . '_left';
		return $this->select()->
			where($this->getTreeIDWhereClause())->
			order($left)->exec()->fetchAllObjects();
	}

	public function getTreeIDWhereClause(): string
	{
		return "{$this->getLeftColumn()} BETWEEN {$this->getLeft()} AND {$this->getRight()}";
	}

	public function getParentID(): ?string
	{
		return $this->gdoVar($this->getParentColumn());
	}

	public function getLeftColumn(): string
	{
		return $this->gdoTreePrefix() . '_left';
	}

	public function getLeft(): ?string
	{
		return $this->gdoVar($this->getLeftColumn());
	}

	public function getRight(): ?string
	{
		return $this->gdoVar($this->getRightColumn());
	}

	public function getRightColumn(): string
	{
		return $this->gdoTreePrefix() . '_right';
	}

	public function getDepthColumn(): string
	{
		return $this->gdoTreePrefix() . '_depth';
	}

	public function getDepth(): ?string
	{
		return $this->gdoVar($this->getDepthColumn());
	}

	/**
	 * @return self[]
	 */
	public function &all(string $order = null, bool $json = false): array
	{
		$order = $order ?: $this->gdoTableIdentifier() . '.' . $this->getLeftColumn();
		return parent::all($order, $json);
	}

	/**
	 * Get all items as all and only roots (those with no parent)
	 *
	 * @return self[]
	 */
	public function full(): array
	{
		$roots = [];
		$tree = $this->tbl()->all();

		foreach ($tree as $leaf)
		{
			$leaf->children = [];
		}

		foreach ($tree as $leaf)
		{
			$pid = $leaf->getParentID();
			if (isset($tree[$pid]))
			{
				$parent = $tree[$pid];
				$parent->children[] = $leaf;
			}
			else
			{
				$roots[] = $leaf;
			}
		}
		$result = [$tree, $roots];
		return $result;
	}

	public function fullRoots()
	{
		return $this->full()[1];
	}

	public function toJSON(): array
	{
		return [
			'id' => (int)$this->getID(),
			'selected' => false,
			'colapsed' => false,
			'name' => $this->getName(),
			'label' => $this->renderName(),
			'depth' => (int)$this->getDepth(),
			'parent' => (int)$this->getParentID(),
			'children' => $this->getChildrenJSON(),
		];
	}

	public function getChildrenJSON()
	{
		$json = [];
		foreach ($this->getChildren() as $child)
		{
			$json[] = $child->toJSON();
		}
		return empty($json) ? null : $json;
	}


	###############
	### Rebuild ###
	###############
	public function gdoAfterCreate(GDO $gdo): void
	{
		$this->rebuildFullTree();
	}

	public function rebuildFullTree(): void
	{
		$this->rebuildTree(null, 1, 0);

		$roots = $this->fullRoots();
		foreach ($roots as $leaf)
		{
			$this->rebuildPath($leaf);
		}
	}

	private function rebuildTree($parent, $left, $depth)
	{
		$right = $left + 1;

		$p = $this->getParentColumn();
		$idc = $this->getIDColumn();

		$where = $parent ? "$p=$parent" : "$p IS NULL";
		$result = $this->table()->select($idc, false)->where($where)->exec()->fetchAllVars();
		foreach ($result as $id)
		{
			$right = $this->rebuildTree($id, $right, $depth + 1);
		}

		$l = $this->getLeftColumn();
		$r = $this->getRightColumn();
		$d = $this->getDepthColumn();
		if ($parent)
		{
			$this->table()->update()->set("$l=$left, $r=$right, $d=$depth")->where("$idc=$parent")->exec();
		}
		return $right + 1;
	}

	private function rebuildPath(GDO_Tree $leaf, string $path = '-'): static
	{
		$path .= $leaf->getID() . '-';
		$leaf->saveVar($this->getPathColumn(), $path);
		if ($leaf->children)
		{
			foreach ($leaf->children as $child)
			{
				$this->rebuildPath($child, $path);
			}
		}
		return $this;
	}

}
