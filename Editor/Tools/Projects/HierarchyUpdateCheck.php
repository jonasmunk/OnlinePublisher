<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Session.php';
require_once '../../Include/Functions.php';

if (getToolSessionVar('projects','updateHierarchy')) {
	echo "true";
	setToolSessionVar('projects','updateHierarchy',false);
} else {
    echo "false";
}
?>