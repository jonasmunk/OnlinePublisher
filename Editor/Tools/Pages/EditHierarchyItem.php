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
require_once 'Functions.php';

$id = requestGetNumber('id',0);

$sql="select * from hierarchy_item where id=".$id;
$row = Database::selectFirst($sql);

$title=$row['title'];
$alternative=$row['alternative'];
$targetType=$row['target_type'];
$targetId=$row['target_id'];
$targetValue=$row['target_value'];
$hierarchyId = $row['hierarchy_id'];
$hidden = $row['hidden']==1;

$sql="select * from hierarchy_item where parent=".$id;
$canDelete=Database::isEmpty($sql);

$pages=buildPages($targetId);
$allPages=buildAllPages();
$files=buildFiles();

if (requestGetExists('return')) {
    $return = requestGetText('return');
} else {
    $return = getToolSessionVar('pages','rightFrame');    
}
$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" align="center">'.
'<titlebar title="Redigering af menupunkt" icon="Element/Structure">'.
'<close link="'.$return.'" help="Luk vinduet uden at gemme ændringer"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="UpdateHierarchyItem.php" method="post" name="Formula" focus="title">'.
'<hidden name="id">'.$id.'</hidden>'.
'<hidden name="hierarchy">'.$hierarchyId.'</hidden>'.
'<hidden name="return">'.$return.'</hidden>'.
'<group size="Large">'.
'<textfield badge="Titel:" name="title">'.
encodeXML($title).
'</textfield>'.
'<textfield badge="Beskrivelse:" name="alternative">'.
encodeXML($alternative).
'</textfield>'.
'<space/>'.
'<combo badge="Link til:" name="type" selected="'.$targetType.'">'.
	'<option title="Intet" value=""/>'.
	'<option title="Side:" value="page">'.
		'<select name="page" selected="'.($targetType=='page' ? $targetId : '0').'">'.$pages.'</select>'.
	'</option>'.
	'<option title="Sidereference:" value="pageref">'.
		'<select name="pageref" selected="'.($targetType=='pageref' ? $targetId : '0').'">'.$allPages.'</select>'.
	'</option>'.
	'<option title="Fil:" value="file">'.
		'<select name="file" selected="'.($targetType=='file' ? $targetId : '0').'">'.$files.'</select>'.
	'</option>'.
	'<option title="Adresse:" value="url">'.
		'<textfield name="url">'.($targetType=='url' ? encodeXML($targetValue) : '').'</textfield>'.
	'</option>'.
	'<option title="E-post:" value="email">'.
		'<textfield name="email">'.($targetType=='email' ? encodeXML($targetValue) : '').'</textfield>'.
	'</option>'.
'</combo>'.
'<select badge="Destination:" name="target" selected="'.$row['target'].'">'.
'<option title="Samme vindue" value="_self"/>'.
'<option title="Nyt vindue" value="_blank"/>'.
'<option title="Download" value="_download"/>'.
'<option title="Øverste ramme" value="_top"/>'.
'<option title="Rammen over" value="_parent"/>'.
'</select>'.
'<space/>'.
'<checkbox badge="Skjult" name="hidden" selected="'.($hidden ? 'true' : 'false').'"/>'.
'<buttongroup size="Large">'.
'<button title="Flyt" link="NewHierarchyItemPosition.php?id='.$id.'&amp;return='.urlencode($return).'" help="Flyt menupunktet til en anden position"/>'.
($canDelete ? '<button title="Slet" link="DeleteHierarchyItem.php?id='.$id.'" help="Slet menupunktet fra hierarkiet"/>'
: '<button title="Slet" style="Disabled" help="Punktet kan ikke slettes da det indeholder underpunkter!"/>').
'<button title="Annuller" link="'.$return.'" help="Luk vinduet uden at gemme ændringer"/>'.
'<button title="Opdater" submit="true" style="Hilited" help="Gem ændringer"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Form");
writeGui($xwg_skin,$elements,$gui);

function buildPages($id) {
	$output='<option title="" value="0"/>';
	$sql="select page.id,page.title from page left join hierarchy_item on page.id=target_id and target_type='page' where hierarchy_item.id is null union select id,title from page where id=".$id." order by title";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$output.='<option title="'.encodeXML($row['title']).'" value="'.$row['id'].'"/>';
	}
	Database::free($result);
	return $output;
}

function buildAllPages() {
	$output='<option title="" value="0"/>';
	$sql="select page.id,page.title from page order by title";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$output.='<option title="'.encodeXML($row['title']).'" value="'.$row['id'].'"/>';
	}
	Database::free($result);
	return $output;
}

function buildFiles() {
	$output='<option title="" value="0"/>';
	$sql="select id,title from object where type='file' order by title";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$output.='<option title="'.encodeXML($row['title']).'" value="'.$row['id'].'"/>';
	}
	Database::free($result);
	return $output;
}
?>