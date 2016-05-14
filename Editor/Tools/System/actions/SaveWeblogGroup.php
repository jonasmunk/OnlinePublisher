<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

if ($data->id>0) {
	$group = Webloggroup::load($data->id);
} else {
	$group = new Webloggroup();
}
$group->setTitle($data->title);
$group->setNote($data->note);
$group->save();
$group->publish();
?>