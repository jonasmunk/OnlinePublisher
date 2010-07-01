<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Weblog
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Pageblueprint.php';
require_once '../../Classes/Webloggroup.php';

$blueprints = PageBlueprint::search();
$groups = WeblogGroup::search();

$sql="select * from weblog where page_id=".getPageId();
$row = Database::getRow($sql);

$sql="select webloggroup_id as id from weblog_webloggroup where page_id=".getPageId();
$selectedGroups = Database::getIds($sql);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop" onload="parent.Toolbar.location=\'Toolbar.php?\'+Math.random();">'.
'<window xmlns="uri:Window" width="500" align="center" margin="10">'.
'<titlebar title="Weblog">'.
'<close link="../../Tools/Pages/index.php" target="Desktop"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="Update.php" method="post" name="Formula" focus="data">'.
'<group size="Large" badgeplacement="above">'.
'<textfield badge="Titel:" name="title">'.In2iGui::escape($row['title']).'</textfield>'.
'<select badge="Skabelon til ny side:" name="blueprint" selected="'.$row['pageblueprint_id'].'">'.
'<option value="0" title="Ingen"/>';
foreach ($blueprints as $blueprint) {
    $gui.='<option value="'.$blueprint->getId().'" title="'.In2iGui::escape($blueprint->getTitle()).'"/>';
}
$gui.=
'</select>'.
'<space/>'.
'</group>'.
'<list xmlns="uri:List" width="100%" selectable="group[]" margin="3">'.
'<content>'.
'<headergroup>'.
'<header title="Kategorier"/>'.
'</headergroup>';
foreach ($groups as $group) {
	$gui.='<row selected="'.(in_array($group->getId(),$selectedGroups) ? 'true' : 'false').'" uid="'.$group->getId().'">'.
	'<cell>'.encodeXML($group->getTitle()).'</cell>'.
	'</row>';
}
$gui.=
'</content>'.
'</list>'.
'<group size="Large">'.
'<space/>'.
'<buttongroup size="Large">'.
'<button title="Luk" link="../../Tools/Pages/index.php" target="Desktop"/>'.
'<button title="Opdater" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","List","Form");
In2iGui::display($elements,$gui);
?>