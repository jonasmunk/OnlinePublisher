<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Customers
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

if ($data->id>0) {
	$group = Persongroup::load($data->id);
} else {
	$group = new Persongroup();
}
$group->setTitle($data->title);
$group->setNote($data->note);
$group->save();
$group->publish();
?>