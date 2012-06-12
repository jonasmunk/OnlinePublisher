<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

header('Content-type: text/javascript');

if (Request::getBoolean('dev')) {
	require_once('../../../../hui/bin/combined.js');
	echo "\n";
	require_once('../../../../hui/js/Menu.js');
	echo "\n";
	require_once('../../../../hui/js/Overlay.js');
	echo "\n";
} else {
	echo "\n";
	require_once('../../../../hui/bin/minimized.js');
}
	echo "\n";
	require_once('Controller.js');
	echo "\n";
	require_once('LinksController.js');
	echo "\n";
	require_once('Columns.js');
	echo "\n";
	require_once('../../../Services/Parts/js/parts.js');
	echo "\n";
	require_once('../../../../style/basic/js/OnlinePublisher.js');
?>