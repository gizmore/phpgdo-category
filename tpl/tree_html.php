<?php
namespace GDO\Category\tpl;
/** @var $field \GDO\Category\GDT_Tree **/
/** @var $roots array **/
use GDO\Category\GDO_Tree;
use GDO\Category\GDO_Category;
use GDO\UI\GDT_Icon;
$gdo = $field->gdo;
$gdo instanceof GDO_Tree;
if (!function_exists('\GDO\Category\tpl\__showTree'))
{
	function __showTree(GDO_Category $leaf, int $level=0)
	{
		for ($i = 0; $i < $level; $i++)
		{
			echo GDT_Icon::iconS('plus');
		}
		echo $leaf->renderName();
		echo "<br/>\n";
		foreach ($leaf->children as $child)
		{
			__showTree($child, $level+1);
		}
	}
}

foreach ($roots as $root)
{
	__showTree($root);
}
