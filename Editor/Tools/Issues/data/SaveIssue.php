<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Issues
 */
require_once '../../../Include/Private.php';

$data = Request::getUnicodeObject('data');

if ($data->id > 0) {
	$object = Issue::load($data->id);
} else {
	$object = new Issue();
}
$object->setTitle($data->title);
$object->setNote($data->note);
$object->setKind($data->kind);
$object->save();
$object->publish();
?>