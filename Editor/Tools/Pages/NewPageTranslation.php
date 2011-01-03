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
require_once '../../Classes/Request.php';
require_once '../../Classes/Utilities/StringUtils.php';

$id=Request::getInt('id');

$pages=buildPages($id);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" align="center">'.
'<titlebar title="Vælg oversættelser">'.
'<close link="EditPageTranslations.php?id='.$id.'" help="Luk vinduet og gå tilbage til sidens egenskaber"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="CreatePageTranslation.php" method="post" name="Formula">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large" badgeplacement="above">'.
'<select badge="Side:" name="page" lines="8">'.
$pages.
'</select>'.
'<buttongroup size="Large">'.
'<button title="Annuller" link="EditPageTranslations.php?id='.$id.'" help="Luk vinduet og gå tilbage til sidens egenskaber"/>'.
'<button title="Tilknyt" submit="true" style="Hilited" help="Tilknyt valgte sider som oversættelser"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Form");
writeGui($xwg_skin,$elements,$gui);

function buildPages($id) {
	$output='';
	$not = array($id);
	$sql="select page.id from page,page_translation".
	" where page.id=page_translation.translation_id and page_translation.page_id=".$id;
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$not[] = $row['id'];
	}
	Database::free($result);
	
	$sql="select distinct page.id,page.title,page.language from page left join page_translation on page_translation.translation_id = page.id".
	" where (page_translation.page_id is NULL or page_translation.page_id!=".$id.
	") and page.id not in (".implode(",",$not).")".
	" order by title";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$output.='<option title="'.StringUtils::escapeXML($row['title']).($row['language']!='' ? ' ['.$row['language'].']' : '').'" value="'.$row['id'].'"/>';
	}
	Database::free($result);
	return $output;
}
?>