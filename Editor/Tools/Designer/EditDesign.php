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

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="500" align="center" top="30">'.
'<titlebar title="Redigering af design" icon="Element/Template">'.
'<close link="index.php"/>'.
'</titlebar>'.
'<tabgroup size="Large">'.
'<tab title="Egenskaber" style="Hilited"/>'.
'<tab title="Parametre" link="EditDesignParameters.php?id='.$id.'"/>'.
'</tabgroup>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="UpdateDesign.php" method="post" name="Formula" focus="title">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large">'.
'<textfield badge="Navn:" name="title">'.encodeXML($design->getTitle()).'</textfield>'.
'<select badge="Design:" name="unique" selected="'.encodeXML($design->getUnique()).'">';
$designs = Design::getAvailableDesigns();
foreach($designs as $unique) {
	$info = Design::getDesignInfo($unique);
	$title = (strlen($info['name'])>0 ? $info['name'] : $unique);
	$gui.='<option value="'.encodeXML($unique).'" title="'.
	encodeXML($title).
	'"/>';
}
$gui.=
'</select>'.
'<buttongroup size="Large">'.
($design->canRemove() ? 
'<button title="Slet" link="DeleteDesign.php?id='.$id.'"/>'
:
'<button title="Slet" style="Disabled"/>'
).
'<button title="Annuller" link="index.php"/>'.
(!$design->isPublished() ? 
'<button title="Udgiv" link="PublishDesign.php?id='.$id.'"/>'
:
'<button title="Udgiv" style="Disabled"/>'
).
'<button title="Opdater" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Form");
writeGui($xwg_skin,$elements,$gui);
?>