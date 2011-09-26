<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Core/Database.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Utilities/StringUtils.php';

$id = Request::getInt('id',0);
if (Request::exists('position')) {
	$position=Request::getString('position');
}
else {
	$position='top';
}

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" align="center" top="30">'.
'<titlebar title="Redigering af ramme" icon="Web/Frame">'.
'<close link="Frames.php"/>'.
'</titlebar>'.
'<tabgroup size="Large" align="center">'.
'<tab title="Egenskaber" link="EditFrame.php?id='.$id.'"/>'.
'<tab title="Søgning" link="EditFrameSearch.php?id='.$id.'"/>'.
'<tab title="Links" style="Hilited"/>'.
'<tab title="Nyheder" link="FrameNews.php?id='.$id.'"/>'.
'<tab title="Brugerstatus" link="EditFrameUserstatus.php?id='.$id.'"/>'.
'</tabgroup>'.
'<content padding="5" background="true">'.
'<area xmlns="uri:Area" width="100%">'.
'<tabgroup align="center">'.
'<tab title="Top" '.
($position=='top' ?
'style="Hilited"'
:
'link="EditFrameLinks.php?id='.$id.'&amp;position=top"'
).
'/>'.
'<tab title="Bund" '.
($position=='bottom' ?
'style="Hilited"'
:
'link="EditFrameLinks.php?id='.$id.'&amp;position=bottom"'
).
'/>'.
'</tabgroup>'.
'<toolbar xmlns="uri:Toolbar" align="center">'.
'<tool title="Nyt link" icon="Web/Link" overlay="New" link="NewFrameLink.php?id='.$id.'&amp;position='.$position.'"/>'.
'</toolbar>'.
'<content padding="3">'.
'<list xmlns="uri:List" width="100%">'.
'<content>'.
'<headergroup>'.
'<header title="Titel" width="50%"/>'.
'<header title="Link" width="50%"/>'.
'<header title="" width="1%"/>'.
'</headergroup>';

$sql="select * from frame_link where position='".$position."' and frame_id=".$id." order by `index`";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	if ($row['target_type']=='page') {
		$icon = 'Web/Page';
		$text = getPageTitle($row['target_id']);
		if ($text==NULL) {
			$text = 'Siden findes ikke mere!';
			$icon = 'Basic/Close';
		}
	}
	else if ($row['target_type']=='file') {
		$icon = 'File/Generic';
		$text = getFileTitle($row['target_id']);
		if ($text==NULL) {
			$text = 'Filen findes ikke mere!';
			$icon = 'Basic/Close';
		}
	}
	else if ($row['target_type']=='url') {
		$icon = 'Element/InternetAddress';
		$text = $row['target_value'];
	}
	else if ($row['target_type']=='email') {
		$icon = 'Element/EmailAddress';
		$text = $row['target_value'];
	}
	$gui.='<row link="EditFrameLink.php?id='.$row['id'].'">'.
	'<cell>'.
	'<icon icon="Web/Link"/>'.
	'<text>'.StringUtils::escapeXML($row['title']).'</text>'.
	'</cell>'.
	'<cell>'.
	'<icon icon="'.$icon.'"/>'.
	'<text>'.StringUtils::escapeXML($text).'</text>'.
	'</cell>'.
	'<cell>'.
	'<direction direction="Up" link="MoveFrameLink.php?id='.$row['id'].'&amp;dir=-1"/>'.
	'<direction direction="Down" link="MoveFrameLink.php?id='.$row['id'].'&amp;dir=1"/>'.
	'</cell>'.
	'</row>';
}
Database::free($result);

$gui.=
'</content>'.
'</list>'.
'</content>'.
'</area>'.
'<group xmlns="uri:Button" size="Large" align="right" top="5">'.
'<button title="Udgiv" link="PublishFrame.php?id='.$id.'&amp;return=links&amp;position='.$position.'"/>'.
'</group>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Area","List","Button");
writeGui($xwg_skin,$elements,$gui);


function getPageTitle($id) {
	$output=NULL;
	$sql = "select title from page where id=".$id;
	if ($row = Database::selectFirst($sql)) {
		$output = $row['title'];
	}
	return $output;
}

function getFileTitle($id) {
	$output=NULL;
	$sql = "select title from file where id=".$id;
	if ($row = Database::selectFirst($sql)) {
		$output = $row['title'];
	}
	return $output;
}
?>