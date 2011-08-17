<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../Include/Private.php';

$designs = Query::after('design')->get();
$frames = Frame::search();
$templates = Template::search();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" align="center" top="30">'.
'<titlebar title="Ny skabelon" icon="Element/Template">'.
'<close link="Blueprints.php"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="SaveBlueprint.php" method="post" name="Formula" focus="title">'.
'<group size="Large">'.
'<textfield badge="Titel:" name="title"/>'.
'<select badge="Skabelon:" name="template">';
foreach ($templates as $template) {
    $gui.='<option value="'.$template->getId().'" title="'.In2iGui::escape($template->getName()).'"/>';
}
$gui.=
'</select>'.
'<select badge="Design:" name="design">';
foreach ($designs as $design) {
    $gui.='<option value="'.$design->getId().'" title="'.In2iGui::escape($design->getTitle()).'"/>';
}
$gui.=
'</select>'.
'<select badge="Opsætning:" name="frame">';
foreach ($frames as $frame) {
    $gui.='<option value="'.$frame->getId().'" title="'.In2iGui::escape($frame->getTitle()).'"/>';
}
$gui.=
'</select>'.
'<buttongroup size="Large">'.
'<button title="Annuller" link="Blueprints.php"/>'.
'<button title="Opret" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Form");
In2iGui::display($elements,$gui);
?>