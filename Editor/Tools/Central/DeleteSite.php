<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Users
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Objects/Remotepublisher.php';
require_once '../../Classes/Request.php';

$id = Request::getInt('id');

$site = RemotePublisher::load($id);
$site->remove();

Response::redirect('index.php');
?>