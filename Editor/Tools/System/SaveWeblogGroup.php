<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Webloggroup.php';

$data = Request::getObject('data');

if ($data->id>0) {
	$group = WeblogGroup::load($data->id);
} else {
	$group = new WeblogGroup();
}
$group->setTitle(Request::fromUnicode($data->title));
$group->setNote(Request::fromUnicode($data->note));
$group->save();
$group->publish();
?>