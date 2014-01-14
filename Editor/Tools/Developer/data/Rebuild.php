<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$success = ClassService::rebuildClassPaths();
if (!$success) {
	Response::internalServerError();
}
?>