<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';
require_once 'PagesController.php';

$info = PagesController::getNewPageInfo();
if (requestGetExists('design')) {
	$info['design']=requestGetNumber('design');
}
PagesController::setNewPageInfo($info);

$close = getToolSessionVar('pages','rightFrame');
$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="500" height="300" align="center">'.
'<titlebar title="Ny side" icon="Tool/Assistant">'.
'<close link="'.$close.'" help="Afbryd oprettelse af den nye side"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<layout xmlns="uri:Layout" width="100%" height="100%">'.
'<row><cell valign="top">'.
'<group xmlns="uri:Icon" size="1" titles="right" spacing="6" wrapping="false">'.
'<row><icon icon="Element/Template" title="Vælg skabelon" style="Disabled"/></row>'.
'<row><icon icon="Basic/Color" title="Vælg design" style="Disabled"/></row>'.
'<row><icon icon="Web/Frame" title="Vælg opsætning" style="Hilited"/></row>';
if ($info['fixedHierarchy']==0) {
	$gui.='<row><icon icon="Element/Structure" title="Menupunkt"/></row>';
	$next = 'NewPageHierarchyItem.php';
}
else {
	$next = 'NewPageProperties.php';
}
$gui.=
'<row><icon icon="Basic/Info" title="Angiv egenskaber"/></row>'.
'</group>'.
'</cell><cell width="99%">'.
'<area xmlns="uri:Area" width="100%" height="100%"><content padding="10">'.
'<text xmlns="uri:Text" align="center" bottom="5">'.
'<strong>Vælg grundopsætning</strong>'.
'<break/><small>Klik på den opsætning den nye side skal anvende. Opsætningen bestemmer bl.a. navigation, søgefelt og nyheder i sidens omsluttende ramme.</small>'.
'</text>'.
'<overflow xmlns="uri:Layout" height="300">'.
'<group xmlns="uri:Icon" size="3" titles="right" spacing="3" wrapping="true">';

$sql="select frame.id,frame.name,frame.searchenabled,frame.userstatusenabled,hierarchy.name as hierarchy from frame left join hierarchy on frame.hierarchy_id=hierarchy.id order by frame.name";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<row><icon'.
	' link="'.$next.'?frame='.$row['id'].'"'.
	' icon="Web/Frame"'.
	' title="'.encodeXML($row['name']).'" description="'.buildFrameDescription($row).'"'.
	($row['id']==$info['frame'] ? ' style="Hilited"' : '').
	'/></row>';
}
Database::free($result);

$gui.=
'</group>'.
'</overflow>'.
'</content></area>'.
'</cell></row>'.
'<row><cell colspan="2">'.
'<group size="Large" xmlns="uri:Button" align="right" top="5">'.
'<button title="Annuller" link="'.$close.'" help="Afbryd oprettelse af den nye side"/>'.
'<button title="Forrige" link="NewPageDesign.php" help="Gå tilbage til forrige punkt"/>'.
($info['frame']>0
? '<button title="Næste" link="'.$next.'" help="Gå videre til næste punkt"/>'
: '<button title="Næste" style="Disabled"/>'
).
'</group>'.
'</cell></row>'.
'</layout>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

function buildFrameDescription(&$row) {
	$out = array();
	if ($row['hierarchy']) {
		$out[]='Hierarki: '.$row['hierarchy'];
	}
	if ($row['searchenabled']) {
		$out[]='Søgning: ja';
	}
	if ($row['userstatusenabled']) {
		$out[]='Brugerstatus: ja';
	}
	return implode(', ',$out);
}

$elements = array("Window","Area","Layout","Icon","Text","Button");
writeGui($xwg_skin,$elements,$gui);
?>