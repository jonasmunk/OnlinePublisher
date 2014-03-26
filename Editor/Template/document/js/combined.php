<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

header('Content-type: text/javascript');
Response::setExpiresInHours(16);

if (Request::getBoolean('dev')) {
	require_once('../../../../hui/bin/joined.js');
	echo "\n";
	require_once('../../../../hui/js/Menu.js');
	echo "\n";
	require_once('../../../../hui/js/Overlay.js');
	echo "\n";
	require_once('../../../../hui/js/Window.js');
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
	require_once('DropController.js');
	echo "\n";
	require_once('ColumnsController.js');
	echo "\n";
	require_once('RowsController.js');
	echo "\n";
	require_once('../../../Services/Parts/js/parts.js');
	echo "\n";
	require_once('../../../../style/basic/js/OnlinePublisher.js');
?>