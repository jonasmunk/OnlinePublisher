<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Core/Database.php';
require_once '../../Classes/Core/InternalSession.php';
require_once '../../Classes/Core/Request.php';
require_once 'Functions.php';

if (Request::exists('switch')) {
	InternalSession::switchToolSessionVar('pages','hierOpen-'.Request::getInt('switch'));
}

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<area xmlns="uri:Area" width="100%" height="100%">'.
'<titlebar title="Hierarkier"/>'.
'<content padding="3">'.
'<iframe xmlns="uri:Frame" name="HierFrame" source="Hierarchy.php"/>'.
'</content>'.
'</area>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Area","Frame","Tile");
writeGui($xwg_skin,$elements,$gui);

function getHierarchies() {
	$out = array();
	$sql="select * from hierarchy order by name";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$out[] = array("id" => $row['id'], "name" => $row['name']);
	}
	Database::free($result);
	return $out;
}
?>