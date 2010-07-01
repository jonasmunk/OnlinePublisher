<?php
/**
 * Displays the base frameset of the internal system
 *
 * @package OnlinePublisher
 * @subpackage Base
 * @category Interface
 */
if (!file_exists('../Config/Setup.php')) {
	header('Location: ../setup/initial/');
	exit;
}
require_once '../Config/Setup.php';
require_once 'Include/Functions.php';
require_once 'Include/Security.php';
require_once 'Include/XmlWebGui.php';

$start='Services/Start/';
if (requestGetExists("page")) {
	$page=requestgetNumber('page',0);
	setPageId($page);
	$start='Services/Preview/';
}

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../"/>'.
'<meta><title>OnlinePublisher</title></meta>'.
'<interface xmlns="uri:Frame">'.
'<dock align="bottom" tabs="true">'.
'<frame name="Desktop" source="'.$start.'"/>'.
'<frame scrolling="false" name="Navigationbar" source="Toolbar.php"/>'.
'</dock>'.
'<script xmlns="uri:Script" source="Services/Core/Controller.js"/>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Frame","Script");
writeGui($xwg_skin,$elements,$gui);
?>
