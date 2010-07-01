<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once 'NewsController.php';

if (NewsController::getUpdateHierarchy()) {
	echo "true";
	NewsController::setUpdateHierarchy(false);
} else {
	echo "false";
}
exit;
?>