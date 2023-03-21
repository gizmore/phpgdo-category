<?php
namespace GDO\Category;

use GDO\Core\GDO_Module;
use GDO\UI\GDT_Page;

/**
 * Category and Tree functions and utility.
 *
 * @version 7.0.1
 * @since 3.0.0
 * @author gizmore
 */
final class Module_Category extends GDO_Module
{

	public int $priority = 20;

	public function onLoadLanguage(): void { $this->loadLanguage('lang/category'); }

	public function getClasses(): array { return ['GDO\Category\GDO_Category']; }

	public function href_administrate_module(): ?string { return href('Category', 'Overview'); }

	##############
	### Render ###
	##############
	public function renderAdminTabs()
	{
		GDT_Page::instance()->topResponse()->addField(
			$this->templatePHP('admin_tabs.php'));
	}

}
