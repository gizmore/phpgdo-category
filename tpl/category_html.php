<?php
/** @var $field GDT_Category **/
if ($category = $field->getCategory())
{
	echo $category->renderName();
}
else
{
	echo t('no_category');
}