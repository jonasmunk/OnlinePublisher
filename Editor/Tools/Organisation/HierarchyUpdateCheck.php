<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once 'Functions.php';

if (getUpdateHierarchy()) {
	echo "true";
	setUpdateHierarchy(false);
}
?>