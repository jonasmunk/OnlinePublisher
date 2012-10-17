<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Issues
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

if ($data->id > 0) {
	$object = IssueStatus::load($data->id);
} else {
	$object = new IssueStatus();
}
$object->setTitle($data->title);
$object->save();
$object->publish();
?>