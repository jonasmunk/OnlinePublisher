<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.PageHistory
 */
require_once '../../../Include/Private.php';

$historyId = Request::getInt('id');
$pageId = InternalSession::getPageId();

$success = PageService::reconstruct($pageId,$historyId);

Response::sendObject(array(
	'success' => $success
));
?>