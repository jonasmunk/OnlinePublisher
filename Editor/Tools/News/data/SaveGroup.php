<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/Database.php';
require_once '../../../Classes/Request.php';
require_once '../../../Classes/Objects/Newsgroup.php';

$data = Request::getUnicodeObject('data');

if ($data->id>0) {
	$design = NewsGroup::load($data->id);
} else {
	$design = new NewsGroup();
}
$design->setTitle($data->title);
$design->save();
$design->publish();
?>