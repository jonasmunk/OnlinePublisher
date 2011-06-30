<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/User.php';
require_once '../../Classes/Services/AuthenticationService.php';

$data = Request::getUnicodeObject('data');

if ($data->id>0) {
	$user = User::load($data->id);
} else {
	$user = new User();
}
$user->setTitle($data->title);
$user->setNote($data->note);
$user->setUsername($data->username);
$password = $data->password;
if (StringUtils::isNotBlank($password)) {
	AuthenticationService::setPassword($user,$password);
}
$user->setEmail($data->email);
$user->setInternal($data->internal);
$user->setExternal($data->external);
$user->setAdministrator($data->administrator);
$user->save();
$user->publish();
?>