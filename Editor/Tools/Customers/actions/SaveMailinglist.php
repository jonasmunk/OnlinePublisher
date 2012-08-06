<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Customers
 */
require_once '../../../Include/Private.php';

$data = Request::getUnicodeObject('data');

if ($data->id>0) {
	$list = Mailinglist::load($data->id);
} else {
	$list = new Mailinglist();
}
$list->setTitle($data->title);
$list->setNote($data->note);
$list->save();
$list->publish();
?>