<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.ProductListing
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once 'Functions.php';


$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<dock xmlns="uri:Dock" orientation="Top">'.
'<content>'.
documentTab().
'</content>'.
'</dock>'.
'</xmlwebgui>';

$elements = array("Dock","DockForm","Script");
writeGui($xwg_skin,$elements,$gui);

function documentTab() {
	$pageId = getProductListingId();
	$output=
	'<tool title="Luk" icon="Basic/Close" link="../../Tools/Pages/index.php" target="Desktop"/>'.
	'<divider/>'.
	(pageIsChanged($pageId)
	? '<tool title="Udgiv" icon="Basic/Internet" overlay="Upload" link="Publish.php" badge="!" badgestyle="Hilited"/>'
	: '<tool title="Udgiv" icon="Basic/Internet" overlay="Upload" style="Disabled"/>'
	).
	'<tool title="Vis ændringer" icon="Basic/View" link="../../Services/Preview/" target="Desktop"/>'.
	'<tool title="Egenskaber" icon="Basic/Info" link="../../Tools/Pages/?action=pageproperties&amp;id='.getPageId().'" target="Desktop" help="Vis sidens egenskaber i side-værktøjet"/>';
	return $output;
}

function pageIsChanged($id) {
	$sql="select changed-published as delta from page where id=".$id;
	$row = Database::selectFirst($sql);
	if ($row['delta']>0) {
		return true;
	}
	else {
		return false;
	}
}
?>