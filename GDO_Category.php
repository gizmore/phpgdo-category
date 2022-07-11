<?php
namespace GDO\Category;

use GDO\Core\GDT_AutoInc;
use GDO\Core\GDT_Name;

/**
 * GDO_Category table. Inherits Tree.
 * @author gizmore
 * @version 6.0
 * @since 2.0
 */
final class GDO_Category extends GDO_Tree
{
	###########
	### GDO ###
	###########
	public function memCached() { return false; }
	public function gdoTreePrefix() { return 'cat'; }
	public function gdoColumns() : array
	{
		return array_merge([
			GDT_AutoInc::make('cat_id'),
			GDT_Name::make('cat_name'),
		], parent::gdoColumns());
	}

	##############
	### Getter ###
	##############
	public function getName() { return $this->gdoVar('cat_name'); }
	public function displayName() { return html($this->getName()); }
	public function href_btn_edit() { return href('Category', 'Crud', '&id='.$this->getID()); }

	#############
	### Cache ###
	#############
	public function rebuildFullTree()
	{
	    $this->uncacheAll();
		parent::rebuildFullTree();
	}
	
	##############
	### Render ###
	##############
	public function renderCell() : string
	{
		return GDT_Category::make('cat')->gdo($this)->renderCell();
	}

	public function renderChoice() : string
	{
		return GDT_Category::make('cat')->gdo($this)->renderChoice();
	}

}
