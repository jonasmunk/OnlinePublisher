<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Issues
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

if (!$data) {
    Log::debug('Invalid JSON: ' . Request::getString('data'));
    Response::badRequest('No data');
    exit;
}

if ($data->id > 0) {
	$object = Issue::load($data->id);
} else {
	$object = new Issue();
}
$object->setTitle($data->title);
$object->setNote($data->note);
$object->setKind($data->kind);
$object->setStatusId($data->statusId);
$object->save();
$object->publish();
?>