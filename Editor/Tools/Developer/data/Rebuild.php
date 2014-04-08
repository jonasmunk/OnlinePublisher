<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$success = ClassService::rebuildClasses();
if (!$success) {
	Response::internalServerError();
}
?>