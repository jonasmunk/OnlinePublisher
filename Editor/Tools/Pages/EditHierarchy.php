<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Utilities/StringUtils.php';

require_once 'Functions.php';

$id=Request::getInt('id');
InternalSession::setToolSessionVar('pages','rightFrame','EditHierarchy.php?id='.$id);

$sql="select name,changed-published as publishdelta from hierarchy where id=".$id;
$row = Database::selectFirst($sql);
$name=$row['name'];
if ($row['publishdelta']>0) {
	$published=false;
}
else {
	$published=true;
}

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<titlebar title="Redigering af hierarkiet &quot;'.StringUtils::escapeXML($name).'&quot;" icon="Element/Structure">'.
'<close link="HierarchyFrame.php?id='.$id.'" help="Luk vinduet og gå tilbage til listen over sider"/>'.
'</titlebar>'.
'<toolbar xmlns="uri:Toolbar" align="left">'.
'<tool title="Luk" icon="Basic/Close" link="HierarchyFrame.php?id='.$id.'" help="Luk vinduet og gå tilbage til listen over sider"/>'.
($published ? '<tool title="Udgiv" icon="Basic/Internet" overlay="Upload" style="Disabled"/>'
: '<tool title="Udgiv" icon="Basic/Internet" overlay="Upload" link="PublishHierarchy.php?id='.$id.'" badge="!" badgestyle="Hilited" help="Udgiv hierarkiets ændriger"/>').
'<divider/>'.
'<tool title="Nyt punkt" icon="Basic/Add" link="NewHierarchyItem.php?hierarchy='.$id.'" help="Opret et nyt menupunkt"/>'.
'</toolbar>'.
'<content valign="top">'.
'<iframe xmlns="uri:Frame" source="ListOfHierarchyItems.php?id='.$id.'"/>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Frame");
writeGui($xwg_skin,$elements,$gui);
?>