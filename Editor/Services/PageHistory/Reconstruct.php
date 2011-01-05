<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.PageHistory
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Page.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/InternalSession.php';


$historyId = Request::getInt('id');
$pageId = InternalSession::getPageId();

$page = Page::load($pageId);

$page->reconstruct($historyId);

Response::redirect("../../Template/Edit.php");
?>