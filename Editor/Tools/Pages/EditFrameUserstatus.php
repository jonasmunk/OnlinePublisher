<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Designer
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Utilities/GuiUtils.php';
require_once '../../Classes/Database.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Request.php';

$id = Request::getInt('id',0);

$sql="select * from frame where id=".$id;
$row = Database::selectFirst($sql);
$enabled=$row['userstatusenabled'];
$page=$row['userstatuspage_id'];

$pageList=GuiUtils::buildPageOptions('authentication');

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
'<tab title="Søgning" link="EditFrameSearch.php?id='.$id.'"/>'.
'<tab title="Links" link="EditFrameLinks.php?id='.$id.'"/>'.
'<tab title="Nyheder" link="FrameNews.php?id='.$id.'"/>'.
'<tab title="Brugerstatus" style="Hilited"/>'.
'</tabgroup>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="UpdateFrameUserstatus.php" method="post" name="Formula">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large">'.
'<checkbox badge="Aktiv:" name="enabled" selected="'.($enabled ? 'true' : 'false').'"/>'.
'<select badge="Login-side:" name="page" selected="'.$page.'">'.
'<option title="Vælg side..." value="0"/>'.
$pageList.
'</select>'.
'<buttongroup size="Large">'.
'<button title="Udgiv" link="PublishFrame.php?id='.$id.'&amp;return=userstatus"/>'.
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
?>