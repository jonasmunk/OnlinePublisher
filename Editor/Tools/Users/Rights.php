<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Security
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Tool.php';


$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" align="center" margin="30">'.
'<titlebar title="Sikkerhed" icon="Tool/Security"/>'.
'<tabgroup size="Large">'.
'<tab title="Brugere" link="index.php"/>'.
'<tab title="Rettigheder" style="Hilited"/>'.
'</tabgroup>'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool title="Gem ændringer" icon="Basic/Save" link="javascript: Formula.submit();"/>'.
'</toolbar>'.
'<content>'.
'<form action="UpdateRights.php" method="post" object="Formula" xmlns="uri:Form">'.
'<matrix xmlns="uri:Matrix">';

$gui.='<columns header="Værktøjer">';
$columns = array();
$sql = "select * from object where type='user' order by title";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<column title="'.encodeXML($row['title']).'"/>'.
	$columns[$row['id']]=array();
}
Database::free($result);
$sql="select * from user_permission where entity_type='tool'";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$columns[$row['user_id']][]=$row['entity_id'];
}
Database::free($result);
$gui.='</columns>';

$tools = Tool::getTools();
foreach ($tools as $tool) {
	$gui.='<row title="'.encodeXML($tool['name']).'" icon="'.$tool['icon'].'">';
	foreach($columns as $column => $selected) {
		$gui.='<cell><boolean selected="'.(in_array($tool['id'],$selected) ? 'true' : 'false').'" value="tool-'.$tool['id'].'-'.$column.'"/></cell>';
	}
	$gui.='</row>';
}

$gui.='</matrix>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';


$elements = array("Window","Toolbar","Form","Matrix");
writeGui($xwg_skin,$elements,$gui);
?>