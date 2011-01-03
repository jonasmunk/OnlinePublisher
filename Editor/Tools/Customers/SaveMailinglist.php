<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Customers
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Mailinglist.php';

$data = Request::getObject('data');

if ($data->id>0) {
	$list = Mailinglist::load($data->id);
} else {
	$list = new Mailinglist();
}
$list->setTitle(Request::fromUnicode($data->title));
$list->setNote(Request::fromUnicode($data->note));
$list->save();
$list->publish();
?>