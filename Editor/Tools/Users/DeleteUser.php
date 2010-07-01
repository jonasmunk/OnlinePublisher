<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Users
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/User.php';

$id = requestGetNumber('id');

$user = User::load($id);
$user->remove();

redirect('index.php');
?>