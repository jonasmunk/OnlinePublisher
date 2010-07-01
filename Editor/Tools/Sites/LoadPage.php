<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Page.php';

$data = Request::getObject('data');
$page=Page::load($data->id);
$page->setData(null);
$page->toUnicode();

In2iGui::sendObject($page);
?>