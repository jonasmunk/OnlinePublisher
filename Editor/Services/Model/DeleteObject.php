<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Customers
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Object.php';

$data = Request::getObject('data');

$obj=Object::load($data->id);
$obj->remove();
?>