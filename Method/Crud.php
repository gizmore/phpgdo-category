<?php
namespace GDO\Category\Method;

use GDO\Admin\MethodAdmin;
use GDO\Category\GDO_Category;
use GDO\Category\GDT_Category;
use GDO\Category\Module_Category;
use GDO\Core\GDO;
use GDO\DB\Cache;
use GDO\Form\GDT_Form;
use GDO\Form\MethodCrud;

/**
 * Add and edit categories.
 *
 * @version 7.0.1
 * @since 6.2.0
 * @author gizmore
 */
final class Crud extends MethodCrud
{

	use MethodAdmin;

	public function getPermission(): ?string { return 'staff'; }

	public function onRenderTabs(): void
	{
		$this->renderAdminBar();
		Module_Category::instance()->renderAdminTabs();
	}

	public function gdoTable(): GDO
	{
		return GDO_Category::table();
	}

	public function hrefList(): string
	{
		return href('Category', 'Overview');
	}

	public function createForm(GDT_Form $form): void
	{
		$table = $this->gdoTable();
		$form->addFields(
			$table->gdoColumn('cat_name'),
			GDT_Category::make('cat_parent')->label('parent')->emptyLabel('select_parent_category'),
		);
		$this->createFormButtons($form);
	}

	public function afterCreate(GDT_Form $form, GDO $gdo) { $this->afterChange($gdo); }

	public function afterUpdate(GDT_Form $form, GDO $gdo) { $this->afterChange($gdo); }

	public function afterChange(GDO_Category $category)
	{
		GDO_Category::table()->rebuildFullTree();
		Cache::remove('gdo_category');
	}

}
