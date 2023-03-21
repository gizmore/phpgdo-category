<?php
namespace GDO\Category\tpl;

use GDO\Category\GDT_Category;

/** @var $field GDT_Category * */
if ($category = $field->getCategory())
{
	echo $category->renderName();
}
else
{
	echo t('no_category');
}
