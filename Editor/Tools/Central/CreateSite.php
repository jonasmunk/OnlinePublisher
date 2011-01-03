<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Users
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/RemotePublisher.php';
require_once '../../Classes/Request.php';

$title = Request::getString('title');
$url = Request::getString('url');

$site = new RemotePublisher();
$site->setTitle($title);
$site->setUrl($url);
$site->create();
$site->publish();

redirect('index.php');
?>