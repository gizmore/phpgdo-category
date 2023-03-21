<?php
namespace GDO\Category\tpl;

use GDO\Category\GDO_Category;
use GDO\Table\GDT_Table;
use GDO\UI\GDT_Button;

$gdo = GDO_Category::table();
$query = $gdo->select();
$table = GDT_Table::make();
$table->fetchAs($gdo);
$table->addHeaderFields(
	$gdo->gdoColumn('cat_id'),
	$gdo->gdoColumn('cat_name'),
	GDT_Button::make('btn_edit'),
);
$table->query($query);
$table->paginated();
echo $table->render();
