<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Filegroup.php';

$data = Request::getObject('data');

if ($data->id>0) {
	$design = FileGroup::load($data->id);
} else {
	$design = new FileGroup();
}
$design->setTitle(Request::fromUnicode($data->title));
$design->save();
$design->publish();
?>