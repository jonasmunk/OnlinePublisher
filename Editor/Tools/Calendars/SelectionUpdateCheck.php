<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once 'CalendarsController.php';

if (CalendarsController::getUpdateSelection()) {
	echo "true";
	CalendarsController::setUpdateSelection(false);
} else {
	echo "false";
}
exit;
?>