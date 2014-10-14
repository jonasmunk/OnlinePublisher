<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

header('Content-type: text/css');

require_once('../../../../hui/bin/minimized.css');
	echo "\n";
require_once('../../../../style/basic/css/parts.php');
	echo "\n";
require_once('../../../../style/basic/css/document.css');
	echo "\n";
require_once('../../../../style/'.Request::getString('design').'/css/overwrite.css');
	echo "\n";
require_once('../css/stylesheet.css');

?>