<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.FrontPage
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Session.php';

$layoutmode = getTemplateSessionVar('frontpage','layoutmode',false);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<dock xmlns="uri:Dock" orientation="Top">'.
'<tabgroup>'.
'<tab title="Forside" style="Hilited"/>'.
'</tabgroup>'.
'<content>'.
'<tool title="Luk" icon="Basic/Close" link="../../Tools/Pages/index.php" target="Desktop"/>'.
'<divider/>'.
(pageIsChanged()
? '<tool title="Udgiv" icon="Basic/World" overlay="Upload" link="Publish.php" badge="!" badgestyle="Hilited"/>'
: '<tool title="Udgiv" icon="Basic/World" overlay="Upload" style="Disabled"/>'
).
'<tool title="Vis ændringer" icon="Basic/View" link="../../Services/Preview/" target="Desktop"/>'.
'<tool title="Egenskaber" icon="Basic/Info" link="../../Tools/Pages/?action=pageproperties&amp;id='.getPageId().'" target="Desktop" help="Vis sidens egenskaber i side-værktøjet"/>'.
'<divider/>'.
($layoutmode
? '<tool title="Luk layout" icon="Basic/Layout" overlay="Close" link="Editor.php?layoutmode=false" target="Editor" help="Lukker ændring af layout"/>'
: '<tool title="Rediger layout" icon="Basic/Layout" overlay="Edit" link="Editor.php?layoutmode=true" target="Editor" help="Ændring af sidens layout samt flytning af afsnit, rækker og kolonner"/>'
).
'</content>'.
'</dock>'.
'</xmlwebgui>';

$elements = array("Dock","DockForm","Script");
writeGui($xwg_skin,$elements,$gui);

function pageIsChanged() {
	$sql="select changed-published as delta from page where id=".getPageId();
	$row = Database::selectFirst($sql);
	if ($row['delta']>0) {
		return true;
	}
	else {
		return false;
	}
}
?>