<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Services/TemplateService.php';
require_once 'Functions.php';

$frames = getFrames();
$hiers = getHierarchies();
$securityZones = getSecurityZones();
$templates = TemplateService::getTemplatesSorted();
//print_r($templates);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<layout xmlns="uri:Layout"><row><cell padding="3">'.
'<group xmlns="uri:Icon" size="1" width="100%" spacing="3" titles="right">'.
'<row>'.
'<icon title="Alle sider" icon="Web/Page" link="PagesFrame.php?searchPairKey=allPages" target="Right"/>'.
'</row>'.
'<row>'.
'<icon title="Sider uden menupunkt" icon="Element/Structure" overlay="Delete" link="PagesFrame.php?searchPairKey=noHierarchyItem" target="Right"/>'.
'</row>'.
'</group>'.
'<text xmlns="uri:Text" top="5"><strong>Rammer</strong></text>'.
'<group xmlns="uri:Icon" size="1" width="100%" spacing="3" titles="right">';
foreach ($frames as $frame) {
	$gui.='<row>'.
	'<icon title="'.encodeXML($frame['name']).'" icon="Web/Frame"'.
	' link="PagesFrame.php?searchPairKey=frame&amp;searchPairValue='.$frame['id'].'" target="Right"/>'.
	'</row>';
}

$gui.=
'</group>'.
'<text xmlns="uri:Text" top="5"><strong>Hierarkier</strong></text>'.
'<group xmlns="uri:Icon" size="1" width="100%" spacing="3" titles="right">';
foreach ($hiers as $hierarchy) {
	$gui.='<row>'.
	'<icon title="'.encodeXML($hierarchy['name']).'" icon="Element/Structure"'.
	' link="PagesFrame.php?searchPairKey=hierarchy&amp;searchPairValue='.$hierarchy['id'].'" target="Right"/>'.
	'</row>';
}

$gui.=
'</group>'.
'<text xmlns="uri:Text" top="5"><strong>Skabeloner</strong></text>'.
'<group xmlns="uri:Icon" size="1" width="100%" spacing="3" titles="right">';
foreach ($templates as $template) {
	$gui.='<row>'.
	'<icon title="'.encodeXML($template['name']).'" icon="'.$template['icon'].'"'.
	' link="PagesFrame.php?searchPairKey=template&amp;searchPairValue='.$template['id'].'" target="Right"/>'.
	'</row>';
}

$gui.=
'</group>'.
'<text xmlns="uri:Text" top="5"><strong>Sikkerhedszoner</strong></text>'.
'<group xmlns="uri:Icon" size="1" width="100%" spacing="3" titles="right">'.
'<row>'.
'<icon title="Ingen sikkerhedszone" icon="Zone/Security" overlay="Delete" link="PagesFrame.php?searchPairKey=noSecurityZone" target="Right"/>'.
'</row>';
foreach ($securityZones as $zone) {
	$gui.='<row>'.
	'<icon title="'.encodeXML($zone['name']).'" icon="Zone/Security"'.
	' link="PagesFrame.php?searchPairKey=securityZone&amp;searchPairValue='.$zone['id'].'" target="Right"/>'.
	'</row>';
}

$gui.=
'</group>'.
'</cell></row></layout>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Icon","Text","Layout");
writeGui($xwg_skin,$elements,$gui);

function getHierarchies() {
	$out = array();
	$sql="select * from hierarchy order by name";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$out[] = array("id" => $row['id'], "name" => $row['name']);
	}
	Database::free($result);
	return $out;
}

function getFrames() {
	$out = array();
	$sql="select * from frame order by name";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$out[] = array("id" => $row['id'], "name" => $row['name']);
	}
	Database::free($result);
	return $out;
}

function getSecurityZones() {
	$out = array();
	$sql="select * from object where type='securityzone' order by title";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$out[] = array("id" => $row['id'], "name" => $row['title']);
	}
	Database::free($result);
	return $out;
}
?>