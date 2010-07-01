<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Users
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/User.php';

$id = requestPostNumber('id',0);
$fullname = requestPostText('fullname');
$username = requestPostText('username');
$password = requestPostText('password');
$email = requestPostText('email');
$note = requestPostText('note');
$internal = requestPostCheckbox('internal');
$external = requestPostCheckbox('external');
$administrator = requestPostCheckbox('administrator');

$user = new User();
$user->setTitle($fullname);
$user->setUsername($username);
$user->setPassword($password);
$user->setEmail($email);
$user->setNote($note);
$user->setInternal($internal);
$user->setExternal($external);
$user->setAdministrator($administrator);
$user->create();
$user->publish();

redirect('index.php');
?>