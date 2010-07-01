<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Path.php';

$data = Request::getObject('data');

if ($data->id>0) {
	$path = Path::load($data->id);
} else {
	$path = new Path();
}
$path->setPath(Request::fromUnicode($data->path));
$path->setPageId(Request::fromUnicode($data->pageId));
$path->save();
$path->publish();
?>