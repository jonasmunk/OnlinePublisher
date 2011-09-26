<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Search
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Core/Database.php';
require_once '../../Classes/Utilities/StringUtils.php';

require_once 'Functions.php';

$id = getSearchId();

$sql="select * from search where page_id=".$id;
$row = Database::selectFirst($sql);
$enabled = $row['productsenabled'];
$label = $row['productslabel'];
$default = $row['productsdefault'];
$hidden = $row['productshidden'];

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="500" align="center" top="30">'.
'<titlebar title="Søgeside">'.
'<close link="../../Tools/Pages/index.php" target="_parent"/>'.
'</titlebar>'.
'<tabgroup align="center" size="Large">'.
'<tab title="Egenskaber" link="Properties.php"/>'.
'<space/>'.
'<tab title="Sider" link="Pages.php"/>'.
'<tab title="Billeder" link="Images.php"/>'.
'<tab title="Filer" link="Files.php"/>'.
'<tab title="Personer" link="Persons.php"/>'.
'<tab title="Nyheder" link="News.php"/>'.
'<tab title="Produkter" style="Hilited"/>'.
'</tabgroup>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="UpdateProducts.php" method="post" name="Formula" focus="data">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large">'.
'<radiogroup direction="vertical" badge="Opf&#248;rsel:" name="mode">'.
'<radiobutton badge="Inaktiv" value="inactive"'.(!$enabled ? ' selected="true"' : '').'/>'.
'<radiobutton badge="Kan v&#230;lges" value="possible"'.($enabled & !$hidden & !$default ? ' selected="true"' : '').'/>'.
'<radiobutton badge="Valgt p&#229; forh&#229;nd" value="choosen"'.($enabled & !$hidden & $default ? ' selected="true"' : '').'/>'.
'<radiobutton badge="Altid aktiv" value="automatic"'.($enabled & $hidden & $default ? ' selected="true"' : '').'/>'.
'</radiogroup>'.
'<box title="Sprog:">'.
'<textfield badge="Tekst:" name="label" hint="Den tekst der vises for produkter">'.
StringUtils::escapeXML($label).
'</textfield>'.
'</box>'.
'<buttongroup size="Large">'.
'<button title="Luk" link="../../Tools/Pages/index.php" target="_parent"/>'.
'<button title="Opdater" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'<script xmlns="uri:Script">parent.Toolbar.location="Toolbar.php?"+Math.random();</script>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Form","Script");
writeGui($xwg_skin,$elements,$gui);
?>