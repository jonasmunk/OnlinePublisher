<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Session.php';
require_once 'Functions.php';

if (requestGetExists('switch')) {
	switchToolSessionVar('pages','hierOpen-'.requestGetNumber('switch'));
}

//$hiers=getHierarchies();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<area xmlns="uri:Area" width="100%" height="100%">'.
'<titlebar title="Hierarkier"/>'.
'<content padding="3">'.
'<iframe xmlns="uri:Frame" name="HierFrame" source="Hierarchy.php"/>'.
/*
'<tiles xmlns="uri:Tile" height="auto" width="100%">';
foreach ($hiers as $hierarchy) {
	if (getToolSessionVar('pages','hierOpen-'.$hierarchy['id'],true)) {
		$gui.=
		'<tile title="'.$hierarchy['name'].'" arrow="Open" link="Left.php?switch='.$hierarchy['id'].'">'.
		'<link title="Oversigt" link="EditHierarchy.php?id='.$hierarchy['id'].'" target="Right" help="Oversigt over hierarkiet"/>'.
		'<link title="Sider" link="PagesFrame.php?searchPairKey=hierarchy&amp;searchPairValue='.$hierarchy['id'].'" target="Right" help="List alle sider i hierarkiet"/>'.
		'</tile>'.
		'<content>'.
		'<iframe xmlns="uri:Frame" name="HierFrame" source="Hierarchy.php?id='.$hierarchy['id'].'"/>'.
		'</content>';
	}
	else {
		$gui.=
		'<tile title="'.$hierarchy['name'].'" arrow="Closed" link="Left.php?switch='.$hierarchy['id'].'">'.
		'<link title="Oversigt" link="EditHierarchy.php?id='.$hierarchy['id'].'" target="Right" help="Oversigt over hierarkiet"/>'.
		'<link title="Sider" link="PagesFrame.php?searchPairKey=hierarchy&amp;searchPairValue='.$hierarchy['id'].'" target="Right" help="List alle sider i hierarkiet"/>'.
		'</tile>';
	}
}
$gui.=
'</tiles>'.
*/
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