<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.ImageGallery
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';

$id = getPageId();

$sql="select * from imagegallery where page_id=".$id;
$row = Database::selectFirst($sql);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop" onload="parent.Toolbar.location=\'Toolbar.php?\'+Math.random();">'.
'<window xmlns="uri:Window" width="500" align="center" top="30">'.
'<titlebar title="Billedgalleri">'.
'<close link="../../Tools/Pages/index.php" target="Desktop"/>'.
'</titlebar>'.
'<tabgroup align="center" size="Large">'.
'<tab title="Indstillinger" style="Hilited"/>'.
'<tab title="Billeder" link="Images.php"/>'.
'</tabgroup >'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="Update.php" method="post" name="Formula" focus="title">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large">'.
'<textfield badge="Overskrift:" name="title">'.encodeXML($row['title']).'</textfield>'.
'<textfield badge="Tekst:" name="text" lines="6">'.encodeXML($row['text']).'</textfield>'.
'<space/>'.
'<indent><box title="Udseende">'.
'<number badge="St&#248;rrelse:" name="imagesize" min="10" max="800" value="'.$row['imagesize'].'"/>'.
'<number badge="Rotering:" name="rotate" min="0" max="360" value="'.$row['rotate'].'"/>'.
'<checkbox badge="Vis titel" name="showtitle" selected="'.($row['showtitle'] ? 'true' : 'false').'"/>'.
'<checkbox badge="Vis beskrivelse" name="shownote" selected="'.($row['shownote'] ? 'true' : 'false').'"/>'.
'</box></indent>'.
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

$elements = array("Window","Toolbar","Form");
writeGui($xwg_skin,$elements,$gui);
?>