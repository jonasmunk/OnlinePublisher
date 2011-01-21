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

$title = Request::getString('title');
$url = Request::getString('url');

$site = new RemotePublisher();
$site->setTitle($title);
$site->setUrl($url);
$site->create();
$site->publish();

Response::redirect('index.php');
?>