<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Newsgroup.php';

$data = Request::getObject('data');

if ($data->id>0) {
	$design = NewsGroup::load($data->id);
} else {
	$design = new NewsGroup();
}
$design->setTitle(Request::fromUnicode($data->title));
$design->save();
$design->publish();
?>