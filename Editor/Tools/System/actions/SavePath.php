<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$data = Request::getUnicodeObject('data');

if ($data->id>0) {
	$path = Path::load($data->id);
} else {
	$path = new Path();
}
$path->setPath($data->path);
$path->setPageId($data->pageId);
$path->save();
$path->publish();
?>