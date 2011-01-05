<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Design.php';

$data = Request::getObject('data');

if ($data->id>0) {
	$design = Design::load($data->id);
} else {
	$design = new Design();
}
$design->setTitle(Request::fromUnicode($data->title));
$design->setUnique(Request::fromUnicode($data->unique));
$design->save();
$design->publish();
?>