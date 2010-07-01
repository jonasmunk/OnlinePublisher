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

$id=requestGetNumber('id',0);

$design = Design::load($id);
$info = $design->getInfo();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="500" align="center" top="30">'.
'<titlebar title="Redigering af design" icon="Element/Template">'.
'<close link="index.php"/>'.
'</titlebar>'.
'<tabgroup size="Large">'.
'<tab title="Egenskaber" link="EditDesign.php?id='.$id.'"/>'.
'<tab title="Parametre" style="Hilited"/>'.
'</tabgroup>'.
'<content padding="5" background="true">'.
'<list xmlns="uri:List" width="100%" margin="3">'.
'<content>'.
'<headergroup>'.
'<header title="Navn"/>'.
'<header title="Type"/>'.
'<header title="Værdi"/>'.
'<header title="" width="1%"/>'.
'</headergroup>';

$designs = Design::getAvailableDesigns();
foreach($info['parameters'] as $parm) {
	$saved = $design->getParameter($parm['key']);
	$gui.='<row>'.
	'<cell>'.encodeXML($parm['name']).'</cell>'.
	'<cell>'.encodeXML(Design::translateParameterType($parm['type'])).'</cell>'.
	'<cell>'.encodeXML(Design::translateParameterValue($parm['type'],$saved['value'],$info['parameters'][$parm['key']])).'</cell>'.
	'<cell><button title="Skift" link="EditDesignParameter.php?designId='.$id.'&amp;key='.$parm['key'].'"/></cell>'.
	'</row>';
}

$gui.=
'</content>'.
'</list>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","List");
writeGui($xwg_skin,$elements,$gui);
?>