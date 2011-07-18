<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$type = Request::getString('type');
if ($type=='pages') {
	CacheService::clearCompletePageCache();
}
else if ($type=='images') {
	CacheService::clearCompleteImageCache();
}
else if ($type=='temp') {
	CacheService::clearCompleteTempCache();
}
else if ($type=='urls') {
	CacheService::clearCompleteUrlCache();
}
?>