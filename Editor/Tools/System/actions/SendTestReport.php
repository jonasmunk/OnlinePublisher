<?php
/**
 * @package OnlinePublisher
 * @subpackage Tool.System
 */
require_once '../../../Include/Private.php';

$success = ReportService::sendReport();
if (!$success) {
	Response::internalServerError();
}
?>