<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Security
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Core/Database.php';
require_once '../../Classes/Utilities/StringUtils.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="500" align="center" top="30">'.
'<titlebar title="Sikkerhed" icon="Tool/Security"/>'.
'<tabgroup size="Large">'.
'<tab title="Opsætning" style="Hilited"/>'.
'<tab title="Oversigt" link="Matrix.php"/>'.
'</tabgroup>'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool title="Nyt beskyttet område" icon="Zone/Security" overlay="New" link="NewZone.php"/>'.
'</toolbar>'.
'<content>'.
'<list xmlns="uri:List" width="100%" margin="3">'.
'<content>'.
'<headergroup>'.
'<header title="Titel"/>'.
'</headergroup>';


$sql="select * from securityzone,object where securityzone.object_id=object.id order by title";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<row link="EditZone.php?id='.$row['id'].'">'.
	'<cell>'.
	'<icon size="1" icon="Zone/Security"/>'.
	'<text>'.StringUtils::escapeXML($row['title']).'</text>'.
	'</cell>'.
	'</row>';
}
Database::free($result);

$gui.=
'</content>'.
'</list>'.
'</content>'.
'</window>'.
'</interface>'.
'<script xmlns="uri:Script" type="text/javascript">
	if (window.parent!=window) {
		window.parent.baseController.changeSelection(\'tool:Security\');
	}
</script>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","List","Script");
writeGui($xwg_skin,$elements,$gui);
?>