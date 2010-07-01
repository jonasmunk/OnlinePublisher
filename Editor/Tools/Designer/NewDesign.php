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
'<window xmlns="uri:Window" width="400" align="center" top="30">'.
'<titlebar title="Nyt design" icon="Element/Template">'.
'<close link="index.php"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="CreateDesign.php" method="post" name="Formula" focus="title">'.
'<group size="Large">'.
'<textfield badge="Navn:" name="title"/>'.
'<select badge="Design:" name="unique">';
$designs = Design::getAvailableDesigns();
foreach($designs as $unique) {
	$info = Design::getDesignInfo($unique);
	$name = strlen($info['name'])>0 ? $info['name'] : $unique;
	$gui.='<option value="'.encodeXML($unique).'" title="'.
	encodeXML($name).
	'"/>';
}
$gui.=
'</select>'.
'<buttongroup size="Large">'.
'<button title="Annuller" link="index.php"/>'.
'<button title="Opret" submit="true" style="Hilited"/>'.
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