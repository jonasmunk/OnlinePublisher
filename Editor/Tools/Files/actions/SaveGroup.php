<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../.../Include/Private.php';

$data = Request::getUnicodeObject('data');

if ($data->id>0) {
	$design = FileGroup::load($data->id);
} else {
	$design = new FileGroup();
}
$design->setTitle($data->title);
$design->save();
$design->publish();
?>