<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Customers
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Productgroup.php';

$data = Request::getObject('data');

if ($data->id>0) {
	$group = ProductGroup::load($data->id);
} else {
	$group = new ProductGroup();
}
$group->setTitle(Request::fromUnicode($data->title));
$group->setNote(Request::fromUnicode($data->note));
$group->save();
$group->publish();
?>