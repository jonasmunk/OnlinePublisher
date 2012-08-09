<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

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