<?php
namespace GDO\Category\Method;

use GDO\Core\Method;
use GDO\Admin\MethodAdmin;
use GDO\Category\Module_Category;

final class Overview extends Method
{
    use MethodAdmin;
    
    public function getPermission() : ?string { return 'staff'; }
	
    public function onRenderTabs() : void
    {
        $this->renderAdminBar();
        Module_Category::instance()->renderAdminTabs();
    }
    
	public function execute()
	{
		return $this->templatePHP('overview.php');
	}

}
