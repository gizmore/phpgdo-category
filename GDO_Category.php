<?php
namespace GDO\Category;

use GDO\Core\GDT_AutoInc;
use GDO\Core\GDT_Name;

/**
 * GDO_Category table. Inherits Tree.
 *
 * @version 6.0
 * @since 2.0
 * @author gizmore
 */
final class GDO_Category extends GDO_Tree
{

	###########
	### GDO ###
	###########
	public function memCached(): bool { return false; }

	public function gdoTreePrefix() { return 'cat'; }

	public function gdoColumns(): array
	{
		return array_merge([
			GDT_AutoInc::make('cat_id'),
			GDT_Name::make('cat_name')->notNull(),
		], parent::gdoColumns());
	}

	##############
	### Getter ###
	##############

	public function href_btn_edit() { return href('Category', 'Crud', '&id=' . $this->getID()); }

	public function getName(): ?string { return $this->gdoVar('cat_name'); }

	public function renderName(): string { return html($this->getName()); }



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
	public function renderHTML(): string
	{
		return GDT_Category::make('cat')->gdo($this)->renderHTML();
	}

	public function renderOption(): string
	{
		return $this->renderName();
	}

}
