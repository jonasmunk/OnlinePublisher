<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$data = Request::getUnicodeObject('data');

if ($data->id>0) {
	$design = Design::load($data->id);
} else {
	$design = new Design();
}
$design->setTitle($data->title);
$design->setUnique($data->unique);
$design->save();
$design->publish();
?>