<?php
namespace GDO\Category\Method;

use GDO\Category\GDO_Category;
use GDO\Category\Module_Category;
use GDO\Form\GDT_AntiCSRF;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;
use GDO\Form\MethodForm;
use GDO\Admin\MethodAdmin;

final class Rebuild extends MethodForm
{
    use MethodAdmin;
    
    public function onRenderTabs() : void
    {
        $this->renderAdminBar();
        Module_Category::instance()->renderAdminTabs();
    }
    
    public function execute()
	{
        return parent::execute()->addField($this->renderTree());
	}
	
	public function createForm(GDT_Form $form) : void
	{
		$form->addFields(
			GDT_AntiCSRF::make(),
		);
		$form->actions()->addField(GDT_Submit::make());
	}
	
	public function formValidated(GDT_Form $form)
	{
		GDO_Category::table()->rebuildFullTree();
		return $this->message('msg_cat_tree_rebuilt');
	}
	
	### Tree
	public function renderTree()
	{
		return $this->templatePHP('rebuild.php');
	}
}
