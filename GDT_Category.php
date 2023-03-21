<?php
namespace GDO\Category;

use GDO\Core\GDT_ObjectSelect;
use GDO\Core\GDT_Template;

/**
 * A selection for a Category object.
 *
 * @version 7.0.2
 * @author gizmore
 * @see GDO_Category
 */
final class GDT_Category extends GDT_ObjectSelect
{

	protected function __construct()
	{
		parent::__construct();
		$this->table(GDO_Category::table());
		$this->icon('folder');
		$this->emptyLabel('sel_no_category');
	}

	public function defaultLabel(): self
	{
		return $this->label('category');
	}

	public function renderHTML(): string
	{
		return GDT_Template::php('Category', 'category_html.php', [
			'field' => $this,
		]);
	}

	public function getCategory(): ?GDO_Category
	{
		return $this->getValue();
	}

	public function withCompletion(): self
	{
		return $this->completionHref(href('Category', 'Completion'));
	}

}
