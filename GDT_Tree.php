<?php
namespace GDO\Category;

use GDO\Core\GDT_Select;
use GDO\Core\GDT_Template;
use GDO\Core\WithObject;

/**
 * Tree view.
 * The gdo handled should inherit from Tree.
 *
 * @version 7.0.1
 * @since 5.0.0
 * @author gizmore
 */
class GDT_Tree extends GDT_Select
{

	use WithObject;

	public int $rootId = 0;

	public function isTestable(): bool
	{
		return false;
	}

	public function renderHTML(): string
	{
		return GDT_Template::php('Category', 'tree_html.php', [
			'field' => $this,
			'roots' => $this->getRoots(),
		]);
	}

	################
	### RootNode ###
	################

	private function getRoots(): array
	{
		# Build  Tree JSON
		if ($this->rootId > 0)
		{
			return [
				GDO_Category::findById($this->rootId),
			];
		}
		else
		{
			return $this->gdo->fullRoots();
		}
	}

	public function root(int $rootId): self
	{
		$this->rootId = $rootId;
		return $this;
	}

}
