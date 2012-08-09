<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Shop
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

if ($data->id>0) {
	$group = ProductGroup::load($data->id);
} else {
	$group = new ProductGroup();
}
$group->setTitle($data->title);
$group->setNote($data->note);
$group->save();
$group->publish();
?>