<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once 'NewsController.php';

$id=NewsController::getGroupId();

$newsOptions='';
$sql="SELECT object.* FROM object LEFT JOIN newsgroup_news ON newsgroup_news.news_id=object.id and newsgroup_news.newsgroup_id=$id WHERE object.type='news' and newsgroup_news.news_id IS NULL;";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$newsOptions.='<option title="'.encodeXML(shortenString($row['title'],30)).'" value="'.encodeXML($row['id']).'"/>';
}
Database::free($result);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" top="20" align="center">'.
'<titlebar title="Tilf&#248;j nyheder til gruppe" icon="Basic/Add">'.
'<close link="Group.php"/>'.
'</titlebar>'.
'<content padding="5" background="true" valign="top">'.
'<form xmlns="uri:Form" action="InsertInGroup.php" method="post" name="Formula" focus="title">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large" badgewidth="20%" badgeplacement="above">'.
'<select badge="Nyheder:" name="news[]" lines="12" multiple="true">'.
$newsOptions.
'</select>'.
'<buttongroup size="Large">'.
'<button title="Annuller" link="Group.php"/>'.
'<button title="Tilf&#248;j" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Form");
writeGui($xwg_skin,$elements,$gui);
?>