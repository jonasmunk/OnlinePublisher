<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Weblog
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Page.php';

$tab = requestGetText('tab') | 'html';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<dock xmlns="uri:Dock" orientation="Top">'.
'<tabgroup align="left">'.
($tab=='html' ?
'<tab title="Weblog" style="Hilited"/>'.
'<tab title="Avanceret" link="Toolbar.php?tab=advanced"/>'
:
'<tab title="Weblog" link="Toolbar.php?tab=html"/>'.
'<tab title="Avanceret" style="Hilited"/>'
).
'</tabgroup>'.
'<content>'.
'<tool title="Luk" icon="Basic/Close" link="../../Tools/Pages/index.php" target="Desktop"/>'.
'<divider/>';
if ($tab=='html') {
	$gui.=
	(Page::isChanged(getPageId())
	? '<tool title="Udgiv" icon="Basic/Internet" overlay="Upload" link="Publish.php" badge="!" badgestyle="Hilited"/>'
	: '<tool title="Udgiv" icon="Basic/Internet" overlay="Upload" style="Disabled"/>'
	).
	'<tool title="Vis ændringer" icon="Basic/View" link="../../Services/Preview/" target="Desktop"/>'.
	'<tool title="Egenskaber" icon="Basic/Info" link="../../Tools/Pages/?action=pageproperties&amp;id='.getPageId().'" target="Desktop" help="Vis sidens egenskaber i side-værktøjet"/>';
} else {
	$gui.=
	'<tool title="Historik" icon="Basic/Time" link="../../Services/PageHistory/" target="Editor" help="Oversigt over tidligere versioner af dokumentet"/>';	
}
$gui.=
'</content>'.
'</dock>'.
'</xmlwebgui>';

$elements = array("Dock","DockForm","Script");
writeGui($xwg_skin,$elements,$gui);
?>