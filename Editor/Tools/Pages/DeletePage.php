<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Page.php';
require_once '../../Classes/Request.php';

$id=Request::getInt('id',-1);

$page = Page::load($id);
$page->delete();

redirect('PagesFrame.php');
?>