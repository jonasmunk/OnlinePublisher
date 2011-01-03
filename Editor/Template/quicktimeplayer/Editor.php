<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.QuickTimePlayer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/Utilities/StringUtils.php';

$sql="select * from quicktimeplayer where page_id=".InternalSession::getPageId();
$row = Database::selectFirst($sql);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop" onload="parent.Toolbar.location=\'Toolbar.php?\'+Math.random();">'.
'<window xmlns="uri:Window" width="500" align="center" margin="10">'.
'<titlebar title="QuickTime player" icon="Logo/QuickTime">'.
'<close link="../../Tools/Pages/index.php"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="Update.php" method="post" name="Formula" focus="title">'.
'<group size="Large">'.
'<textfield badge="Titel:" name="title">'.StringUtils::escapeXML($row['title']).'</textfield>'.
'<textfield badge="Tekst:" name="text" lines="6">'.StringUtils::escapeXML($row['text']).'</textfield>'.
'<select badge="Fil:" name="file" selected="'.$row['file_id'].'">'.
'<option title="" value="0"/>';
$sql="SELECT * FROM object where type='file' order by title;";
$result = Database::select($sql);
while ($rowFile = Database::next($result)) {
	$gui.='<option title="'.StringUtils::escapeXML($rowFile['title']).'" value="'.StringUtils::escapeXML($rowFile['id']).'"/>';
}
Database::free($result);
$gui.='</select>'.
'<number badge="Bredde:" name="width" min="0" decimals="0" value="'.$row['width'].'"/>'.
'<number badge="Højde:" name="height" min="0" decimals="0" value="'.$row['height'].'"/>'.
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