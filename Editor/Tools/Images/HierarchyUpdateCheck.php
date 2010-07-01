<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */

require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once 'ImagesController.php';

if (ImagesController::getUpdateHierarchy()) {
	echo "true";
	ImagesController::setUpdateHierarchy(false);
} else {
	echo "false";
}
exit;
?>