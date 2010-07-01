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

$id = requestPostNumber('id',0);
$title = requestPostText('title');
$url = requestPostText('url');

$site = RemotePublisher::load($id);
$site->setTitle($title);
$site->setUrl($url);
$site->update();
$site->publish();

redirect('index.php');
?>