<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Customers
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Objects/Persongroup.php';

$data = Request::getUnicodeObject('data');

if ($data->id>0) {
	$group = Persongroup::load($data->id);
} else {
	$group = new Persongroup();
}
$group->setTitle($data->title);
$group->setNote($data->note);
$group->save();
$group->publish();
?>