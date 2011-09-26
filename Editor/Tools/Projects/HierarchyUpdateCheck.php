<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/InternalSession.php';

if (InternalSession::getToolSessionVar('projects','updateHierarchy')) {
	echo "true";
	InternalSession::setToolSessionVar('projects','updateHierarchy',false);
} else {
    echo "false";
}
?>