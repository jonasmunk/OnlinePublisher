<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Security
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';


$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" align="center" margin="30">'.
'<titlebar title="Sikkerhed" icon="Tool/Security"/>'.
'<tabgroup size="Large">'.
'<tab title="Opsætning" link="index.php"/>'.
'<tab title="Oversigt" style="Hilited"/>'.
'</tabgroup>'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool title="Gem ændringer" icon="Basic/Save" link="javascript: Formula.submit();"/>'.
'</toolbar>'.
'<content>'.
'<form action="UpdateMatrix.php" method="post" object="Formula" xmlns="uri:Form">'.
'<matrix xmlns="uri:Matrix">';

$gui.='<columns header="Brugere">';
$columns = array();
$sql = "select * from object where type='securityzone' order by title";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<column title="'.encodeXML($row['title']).'"/>'.
	$columns[$row['id']]=array();
}
Database::free($result);
$sql="select object_id as securityzone_id,user_id from securityzone left join securityzone_user on securityzone_user.securityzone_id=securityzone.object_id";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$columns[$row['securityzone_id']][]=$row['user_id'];
}
Database::free($result);
$gui.='</columns>';

$sql = "select * from object,user where object.id=user.object_id order by title";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<row title="'.encodeXML($row['title']).'" icon="Element/User">';
	foreach($columns as $column => $selected) {
		$gui.='<cell><boolean selected="'.(in_array($row['id'],$selected) ? 'true' : 'false').'" value="user-'.$row['id'].'-'.$column.'"/></cell>';
	}
	$gui.='</row>';
}
Database::free($result);

$gui.='<columns header="Sider">';
$columns = array();
$sql = "select * from object where type='securityzone' order by title";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<column title="'.encodeXML($row['title']).'"/>'.
	$columns[$row['id']]=array();
}
Database::free($result);
$sql="select object_id as securityzone_id,page_id from securityzone left join securityzone_page on securityzone_page.securityzone_id=securityzone.object_id";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$columns[$row['securityzone_id']][]=$row['page_id'];
}
Database::free($result);
$gui.='</columns>';
$sql = "select * from page order by title";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<row title="'.encodeXML($row['title']).'" icon="Template/Generic">';
	foreach($columns as $column => $selected) {
		$gui.='<cell><boolean selected="'.(in_array($row['id'],$selected) ? 'true' : 'false').'" value="page-'.$row['id'].'-'.$column.'"/></cell>';
	}
	$gui.='</row>';
}
Database::free($result);
$gui.='</matrix>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';


$elements = array("Window","Toolbar","Form","Matrix");
writeGui($xwg_skin,$elements,$gui);
?>