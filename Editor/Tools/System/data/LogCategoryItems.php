<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$categories = LogService::getUsedCategories();

$writer = new ItemsWriter();

$writer->startItems();
$writer->item(array('value'=>'all','title'=>array('All categories','da'=>'Alle kategorier')));
foreach($categories as $category) {
	$writer->startItem(array('value'=>$category,'title'=>$category))->endItem();
}
$writer->endItems();
?>