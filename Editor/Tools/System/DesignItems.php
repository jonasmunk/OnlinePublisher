<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';

$designs = DesignService::getAvailableDesigns();

$writer = new ItemsWriter();

$writer->startItems();
foreach($designs as $name => $info) {
	$title = StringUtils::isNotBlank($info->name) ? $info->name : $name;
	$writer->startItem(array('value'=>$name,'title'=>$title))->endItem();
}
$writer->endItems();
?>