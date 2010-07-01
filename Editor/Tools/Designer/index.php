<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Design.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="500" align="center" top="20">'.
'<titlebar title="Designs" icon="Element/Template"/>'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool title="Registrer nyt design" icon="Element/Template" overlay="New" link="NewDesign.php"/>'.
'</toolbar>'.
'<content>'.
'<list xmlns="uri:List" width="100%" margin="3" sort="true">'.
'<content>'.
'<headergroup>'.
'<header title="Navn" width="60%"/>'.
'<header title="Unikt" width="40%"/>'.
'<header align="center"/>'.
'<header align="center"/>'.
'</headergroup>';

$designs = Design::search();
foreach ($designs as $design) {
	$gui.='<row link="EditDesign.php?id='.$design->getId().'">'.
	'<cell>'.
	'<icon size="1" icon="Element/Template"/>'.
	'<text>'.encodeXML($design->getTitle()).'</text>'.
	(!$design->isPublished() ? '<status type="Attention"/>' : '').
	'</cell>'.
	'<cell>'.encodeXML($design->getUnique()).'</cell>'.
	'<cell><status type="'.(is_dir($basePath.'style/'.$design->getUnique()) ? "Finished" : "Error").'"/></cell>'.
	'<cell><button title="Afprøv" link="Preview.php?id='.$design->getId().'"/></cell>'.
	'</row>';
}

$gui.=
'</content>'.
'</list>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","List","Form");
writeGui($xwg_skin,$elements,$gui);
?>