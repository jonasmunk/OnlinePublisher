<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Design.php';
require_once '../../Classes/In2iGui.php';

$writer = new ItemsWriter();

$writer->startItems();
$designs = Design::getAvailableDesigns();
foreach($designs as $unique) {
	$info = Design::getDesignInfo($unique);
	$name = strlen($info['name'])>0 ? $info['name'] : $unique;
	$writer->startItem(array('value'=>$unique,'title'=>$name))->endItem();
}
$writer->endItems();
?>