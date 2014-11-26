<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../../Include/Private.php';

$type = Request::getString('type');

$ctrl = PartService::getController($type);

$gui = file_get_contents($basePath.'Editor/Template/document/live/gui/properties.xml');

$partUI = $ctrl->getUI();

if ($partUI) {
	$buttons = '';
	$pages = '';
	for ($i=0; $i < count($partUI); $i++) { 
		$item = $partUI[$i];
		$buttons.= '<button icon="'.$item['icon'].'" key="'.$item['key'].'"/>';
		$pages.= '<page key="'.$item['key'].'">'.$item['body'].'</page>';
	}
	$gui = str_replace("<!--buttons-->", $buttons, $gui);
	$gui = str_replace("<!--pages-->", $pages, $gui);
}


echo UI::renderFragment($gui);
?>