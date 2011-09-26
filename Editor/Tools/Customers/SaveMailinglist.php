<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Customers
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Objects/Mailinglist.php';

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