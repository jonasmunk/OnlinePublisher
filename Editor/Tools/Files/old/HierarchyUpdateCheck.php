<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Session.php';
require_once '../../Include/Functions.php';
require_once 'Functions.php';

if (getToolSessionVar('files','updateHierarchy')) {
	echo "true";
	setToolSessionVar('files','updateHierarchy',false);
} else {
	echo "false";
}
exit;
?>