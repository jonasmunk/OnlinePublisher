<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.PageHistory
 */
require_once '../../Include/Private.php';

$historyId = Request::getInt('id');
$pageId = InternalSession::getPageId();

PageService::reconstruct($pageId,$historyId);

Response::redirect("../../Template/Edit.php");
?>