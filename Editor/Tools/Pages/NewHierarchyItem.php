<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once 'Functions.php';

require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Utilities/StringUtils.php';

$hierarchyId=Request::getInt('hierarchy');

$pages=buildPages();
$allPages=buildAllPages();
$files=buildFiles();
$parents=buildParents($hierarchyId,0,0);

$close = InternalSession::getToolSessionVar('pages','rightFrame');
$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" align="center">'.
'<titlebar title="Nyt punkt" icon="Element/Structure">'.
'<close link="'.$close.'" help="Afbryd oprettelsen af menupunktet"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="CreateHierarchyItem.php" method="post" name="Formula" focus="title">'.
'<hidden name="hierarchy">'.$hierarchyId.'</hidden>'.
'<group size="Large">'.
'<select badge="Position:" name="parent" selected="'.Request::getInt('parent',0).'">'.
'<option title=":: roden ::" value="0"/>'.
$parents.
'</select>'.
'<space/>'.
'<textfield badge="Titel:" name="title"/>'.
'<textfield badge="Beskrivelse:" name="alternative"/>'.
'<space/>'.
'<combo badge="Link til:" name="type">'.
	'<option title="Intet" value=""/>'.
	'<option title="Side:" value="page">'.
		'<select name="page">'.$pages.'</select>'.
	'</option>'.
	'<option title="Sidereference:" value="pageref">'.
		'<select name="pageref">'.$allPages.'</select>'.
	'</option>'.
	'<option title="Fil:" value="file">'.
		'<select name="file">'.$files.'</select>'.
	'</option>'.
	'<option title="Adresse:" value="url">'.
		'<textfield name="url"></textfield>'.
	'</option>'.
	'<option title="E-post:" value="email">'.
		'<textfield name="email"></textfield>'.
	'</option>'.
'</combo>'.
'<select badge="Destination:" name="target">'.
'<option title="Samme ramme" value="_self"/>'.
'<option title="Nyt vindue" value="_blank"/>'.
'<option title="Download" value="_download"/>'.
'<option title="Øverste ramme" value="_top"/>'.
'<option title="Rammen over" value="_parent"/>'.
'</select>'.
'<buttongroup size="Large">'.
'<button title="Annuller" link="'.$close.'" help="Afbryd oprettelsen af menupunktet"/>'.
'<button title="Opret" submit="true" style="Hilited" help="Opret menupunktet"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Form");
writeGui($xwg_skin,$elements,$gui);

function buildPages() {
	$output='<option title="" value="0"/>';
	$sql="select page.id,page.title from page left join hierarchy_item on page.id=target_id and target_type='page' where hierarchy_item.id is null order by title";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$output.='<option title="'.StringUtils::escapeXML($row['title']).'" value="'.$row['id'].'"/>';
	}
	Database::free($result);
	return $output;
}

function buildAllPages() {
	$output='<option title="" value="0"/>';
	$sql="select page.id,page.title from page order by title";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$output.='<option title="'.StringUtils::escapeXML($row['title']).'" value="'.$row['id'].'"/>';
	}
	Database::free($result);
	return $output;
}

function buildFiles() {
	$output='<option title="" value="0"/>';
	$sql="select id,title from object where type='file' order by title";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$output.='<option title="'.StringUtils::escapeXML($row['title']).'" value="'.$row['id'].'"/>';
	}
	Database::free($result);
	return $output;
}

function buildParents($hierarchyId,$parent,$level) {
	$gui='';
	$sql="select * from hierarchy_item where hierarchy_id=".$hierarchyId." and parent=".$parent." order by `index`";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$prefix='';
		for ($i=0;$i<$level;$i++) {
			$prefix.=' · ';
		}
		$title = $prefix.$row['title'];
		$gui.=
		'<option title="'.StringUtils::escapeXML($title).'" value="'.$row['id'].'" target="_parent"/>'.
		buildParents($hierarchyId,$row['id'],$level+1);
	}
	Database::free($result);
	return $gui;
}
?>