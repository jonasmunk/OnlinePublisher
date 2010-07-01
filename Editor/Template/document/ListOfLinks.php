<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once 'Functions.php';

$pageId = getPageId();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="600" align="center" top="20">'.
'<titlebar title="Links" icon="Element/InternetAddress">'.
'<close link="Frame.php"/>'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar" align="left">'.
'<tool title="Luk" icon="Basic/Close" link="Frame.php"/>'.
'</toolbar>'.
'<content valign="top">'.
'<list xmlns="uri:List" width="100%" margin="3" sort="true">'.
'<content>'.
'<headergroup>'.
'<header title="Kilde"/>'.
'<header title="Destination"/>'.
'<header title="Alternativ"/>'.
'<header title="" width="1%"/>'.
'</headergroup>';

$sql="select * from link where page_id=".$pageId;
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$type = $row['target_type'];
	if ($type=='url') {
		$icon='Element/InternetAddress';
		$text=$row['target_value'];
		$link=$row['target_value'];
	}
	else if ($type=='email') {
		$icon='Element/EmailAddress';
		$text=$row['target_value'];
		$link=$row['target_value'];
	}
	else if ($type=='page') {
		$icon='Web/Page';
		$text=getPageTitle($row['target_id']);
		$link='../../../?id='.$row['target_id'];
		if ($text==null) {
			$icon='Basic/Close';
			$text='Siden findes ikke mere!';
			$link='';
		}
	}
	else if ($type=='file') {
		$icon='File/Generic';
		$text=getFileTitle($row['target_id']);
		$link='../../../?file='.$row['target_id'];
		if ($text==null) {
			$icon='Basic/Close';
			$text='Filen findes ikke mere!';
			$link='';
		}
	}
	// target="Toolbar" link="Toolbar.php?tab=links&amp;edit='.$row['id'].'"
	$gui.='<row>'.
	'<cell>'.encodeXML($row['source_text']).'</cell>'.
	'<cell>'.
	'<icon icon="'.$icon.'"/>'.
	'<text>'.encodeXML($text).'</text>'.
	'</cell>'.
	'<cell>'.encodeXML($row['alternative']).'</cell>'.
	'<cell>'.
	'<icon size="1" icon="Basic/Delete" link="DeleteLink.php?id='.$row['id'].'" help="Slet linket"/>'.
	'<icon size="1" icon="Basic/Search" link="'.encodeXML($link).'" target="_blank" help="Åben linket"/>'.
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
'</xmlwebgui>';

$elements = array("Window","Toolbar","List");
writeGui($xwg_skin,$elements,$gui);

function getPageTitle($id) {
	$output=NULL;
	$sql = "select title from page where id=".$id;
	$row = Database::selectFirst($sql);
	if ($row) {
		$output=$row['title'];
	}
	return $output;
}

function getFileTitle($id) {
	$output=NULL;
	$sql = "select title from object where type='file' and id=".$id;
	if ($row = Database::selectFirst($sql)) {
		$output=$row['title'];
	}
	return $output;
}
?>