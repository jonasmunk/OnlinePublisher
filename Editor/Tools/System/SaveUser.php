<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/User.php';

$data = Request::getObject('data');

if ($data->id>0) {
	$user = User::load($data->id);
} else {
	$user = new User();
}
$user->setTitle(Request::fromUnicode($data->title));
$user->setNote(Request::fromUnicode($data->note));
$user->setUsername(Request::fromUnicode($data->username));
$user->setPassword(Request::fromUnicode($data->password));
$user->setEmail(Request::fromUnicode($data->email));
$user->setInternal($data->internal);
$user->setExternal($data->external);
$user->setAdministrator($data->administrator);
$user->save();
$user->publish();
?>