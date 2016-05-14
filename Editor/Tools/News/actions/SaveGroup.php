<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

if ($data->id>0) {
	$design = Newsgroup::load($data->id);
} else {
	$design = new Newsgroup();
}
$design->setTitle($data->title);
$design->save();
$design->publish();
?>