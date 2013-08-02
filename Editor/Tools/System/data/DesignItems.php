<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$designs = DesignService::getAvailableDesigns();

$writer = new ItemsWriter();

$writer->startItems();
foreach($designs as $name => $info) {
	$title = Strings::isNotBlank($info->name) ? $info->name : $name;
	$writer->startItem(array('value'=>$name,'title'=>$title))->endItem();
}
$writer->endItems();
?>