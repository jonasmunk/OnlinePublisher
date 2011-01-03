<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Utilities/StringUtils.php';

$id = Request::getInt('id',0);

$sql="select * from frame where id=".$id;
$row = Database::selectFirst($sql);
$buttontitle=$row['searchbuttontitle'];
$enabled=$row['searchenabled'];
$page=$row['searchpage_id'];
$pages=$row['searchpages'];
$images=$row['searchimages'];
$news=$row['searchnews'];
$files=$row['searchfiles'];
$products=$row['searchproducts'];
$persons=$row['searchpersons'];

$pageList=buildPages();

$sql="select * from page where frame_id=".$id;
$canDelete=Database::isEmpty($sql);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" align="center" top="30">'.
'<titlebar title="Redigering af ramme" icon="Web/Frame">'.
'<close link="Frames.php"/>'.
'</titlebar>'.
'<tabgroup size="Large" align="center">'.
'<tab title="Egenskaber" link="EditFrame.php?id='.$id.'"/>'.
'<tab title="Søgning" style="Hilited"/>'.
'<tab title="Links" link="EditFrameLinks.php?id='.$id.'"/>'.
'<tab title="Nyheder" link="FrameNews.php?id='.$id.'"/>'.
'<tab title="Brugerstatus" link="EditFrameUserstatus.php?id='.$id.'"/>'.
'</tabgroup>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="UpdateFrameSearch.php" method="post" name="Formula">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large">'.
'<checkbox badge="Aktiv:" name="enabled" selected="'.($enabled ? 'true' : 'false').'"/>'.
'<textfield badge="Knap:" name="buttontitle">'.StringUtils::escapeXML($buttontitle).'</textfield>'.
'<select badge="Søgeside:" name="page" selected="'.$page.'">'.
'<option title="Vælg side..." value="0"/>'.
$pageList.
'</select>'.
'<checkbox badge="Sider:" name="pages" selected="'.($pages ? 'true' : 'false').'"/>'.
'<checkbox badge="Billeder:" name="images" selected="'.($images ? 'true' : 'false').'"/>'.
'<checkbox badge="Filer:" name="files" selected="'.($files ? 'true' : 'false').'"/>'.
'<checkbox badge="Nyheder:" name="news" selected="'.($news ? 'true' : 'false').'"/>'.
'<checkbox badge="Personer:" name="persons" selected="'.($persons ? 'true' : 'false').'"/>'.
'<checkbox badge="Produkter:" name="products" selected="'.($products ? 'true' : 'false').'"/>'.
'<buttongroup size="Large">'.
'<button title="Udgiv" link="PublishFrame.php?id='.$id.'&amp;return=search"/>'.
($canDelete ? 
'<button title="Slet" link="DeleteFrame.php?id='.$id.'"/>'
:
'<button title="Slet" style="Disabled"/>'
).
'<button title="Annuller" link="Frames.php"/>'.
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

function buildPages() {
	$output="";
	$sql="select page.id,page.title from page,template where page.template_id=template.id and template.unique='search' order by page.title";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$output.='<option title="'.StringUtils::escapeXML($row['title']).'" value="'.$row['id'].'"/>';
	}
	Database::free($result);
	return $output;
}
?>