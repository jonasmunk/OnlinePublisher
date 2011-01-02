<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/InternalSession.php';

if (InternalSession::getToolSessionVar('pages','updateHier')) {
	echo "true";
	InternalSession::setToolSessionVar('pages','updateHier',false);
} else {
	echo "false";
}
exit;
?>