<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$list = LogService::getUsedEvents();

$writer = new ItemsWriter();

$writer->startItems();
$writer->item(array('value'=>'all','title'=>'Alle begivenheder'));
foreach($list as $item) {
	$writer->startItem(array('value'=>$item,'title'=>$item))->endItem();
}
$writer->endItems();
?>