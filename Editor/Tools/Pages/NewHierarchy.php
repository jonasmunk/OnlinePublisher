<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/GuiUtils.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" align="center" top="30">'.
'<titlebar title="Nyt hierarki" icon="Element/Structure">'.
'<close link="Hierarchies.php"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="CreateHierarchy.php" method="post" name="Formula" focus="name">'.
'<group size="Large">'.
'<textfield badge="Navn:" name="name"/>'.
'<select badge="Sprog" name="language">'.
'<option title="" value=""/>';
$languages = GuiUtils::getLanguages();
while ($language = current($languages)) {
    $gui.='<option value="'.key($languages).'" title="'.$language.'"/>';
    next($languages);
}
$gui.=
'</select>'.
'<buttongroup size="Large">'.
'<button title="Annuller" link="Hierarchies.php"/>'.
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