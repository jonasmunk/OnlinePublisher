<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Users
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/RemotePublisher.php';

$id = requestGetNumber('id');

$site = RemotePublisher::load($id);
$site->remove();

redirect('index.php');
?>