<?php
namespace GDO\Category;

use GDO\Core\WithObject;
use GDO\Core\GDT_Select;
use GDO\Core\GDT_Template;

/**
 * Tree view.
 * The gdo handled should inherit from Tree.
 * 
 * @author gizmore
 * @version 7.0.1
 * @since 5.0.0
 */
class GDT_Tree extends GDT_Select
{
	use WithObject;
	
	public function isTestable(): bool
	{
		return false;
	}

	public function renderHTML() : string
	{
		return GDT_Template::php('Category', 'tree_html.php', [
			'field' => $this,
			'roots' => $this->getRoots(),
		]);
	}
	
	private function getRoots() : array
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
	
	################
	### RootNode ###
	################
	public int $rootId = 0;
	public function root(int $rootId): static
	{
		$this->rootId = $rootId;
		return $this;
	}
	
}
