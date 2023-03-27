<?php
namespace GDO\Category\Method;

use GDO\Admin\MethodAdmin;
use GDO\Category\Module_Category;
use GDO\Core\GDT;
use GDO\Core\Method;

final class Overview extends Method
{

	use MethodAdmin;

	public function getPermission(): ?string { return 'staff'; }

	public function onRenderTabs(): void
	{
		$this->renderAdminBar();
		Module_Category::instance()->renderAdminTabs();
	}

	public function execute(): GDT
	{
		return $this->templatePHP('overview.php');
	}

}
