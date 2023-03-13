<?php
namespace GDO\Category;

use GDO\Core\GDT_Template;
use GDO\Core\GDT_ObjectSelect;

/**
 * A selection for a Category object.
 *
 * @author gizmore
 * @version 7.0.2
 * @see GDO_Category
 */
final class GDT_Category extends GDT_ObjectSelect
{

	public function defaultLabel(): self
	{
		return $this->label('category');
	}

	protected function __construct()
	{
		parent::__construct();
		$this->table(GDO_Category::table());
		$this->icon('folder');
		$this->emptyLabel('sel_no_category');
	}

	public function getCategory(): ?GDO_Category
	{
		return $this->getValue();
	}

	public function withCompletion(): self
	{
		return $this->completionHref(href('Category', 'Completion'));
	}

	public function renderHTML(): string
	{
		return GDT_Template::php('Category', 'category_html.php', [
			'field' => $this
		]);
	}

}
