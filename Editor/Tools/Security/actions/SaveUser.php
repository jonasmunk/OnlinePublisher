<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Security
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
$user->setEmail($data->email);
$user->setInternal($data->internal);
$user->setExternal($data->external);
$user->setAdministrator($data->administrator);
$user->setLanguage($data->language);
if ($user->save()) {
	$password = $data->password;
	if (Strings::isNotBlank($password)) {
		AuthenticationService::setPassword($user,$password);
        $user->save();
	}
	$user->publish();
} else {
	Response::badRequest();
}
?>