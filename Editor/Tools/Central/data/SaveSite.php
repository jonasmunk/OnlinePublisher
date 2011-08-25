<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Central
 */
require_once '../../../Include/Private.php';

$data = Request::getUnicodeObject('data');

if ($data->id) {
	$site = RemotePublisher::load($data->id);
} else {
	$site = new RemotePublisher();
}
$site->setTitle($data->title);
$site->setUrl($data->url);
$site->save();
$site->publish();
?>