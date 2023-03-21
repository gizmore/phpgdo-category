<?php
namespace GDO\Category\tpl;

use GDO\Category\GDO_Category;
use GDO\Category\GDT_Tree;

$gdo = GDO_Category::table();

echo GDT_Tree::make('tree')->gdo($gdo)->render();
