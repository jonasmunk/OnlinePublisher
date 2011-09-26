<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Model/Page.php';
require_once '../../Classes/Core/Request.php';

$id=Request::getInt('id',-1);

$page = Page::load($id);
$page->delete();

Response::redirect('PagesFrame.php');
?>