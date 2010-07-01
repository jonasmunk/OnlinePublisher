<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Session.php';
require_once '../../Include/Functions.php';
require_once 'PagesController.php';

$active = PagesController::getActiveItem();
if ($active['id']>0) {
	echo $active['type'].'-'.$active['id'];
} else {
	echo $active['type'];
}
exit;
?>