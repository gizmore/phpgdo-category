<?php
namespace GDO\Category;
use GDO\Core\GDT_Template;
use GDO\Core\GDT_ObjectSelect;

/**
 * A selection for a Category object.
 * @author gizmore
 * @see Category
 */
final class GDT_Category extends GDT_ObjectSelect
{
	public function defaultLabel() : self { return $this->label('category'); }
	
	protected function __construct()
	{
		$this->table(GDO_Category::table());
		$this->icon('folder');
		$this->emptyLabel = 'sel_no_category';
	}
	
	/**
	 * @return GDO_Category
	 */
	public function getCategory()
	{
		return $this->getValue();
	}
	
	public function withCompletion()
	{
	 	$this->completionHref(href('Category', 'Completion'));
	}
	
	public function renderHTML() : string
	{
		return GDT_Template::php('Category', 'cell/category.php', ['field'=>$this]);
	}
	
}
