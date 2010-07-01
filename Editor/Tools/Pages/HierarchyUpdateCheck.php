<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Session.php';
require_once '../../Include/Functions.php';

if (getToolSessionVar('pages','updateHier')) {
	echo "true";
	setToolSessionVar('pages','updateHier',false);
} else {
	echo "false";
}
exit;
?>