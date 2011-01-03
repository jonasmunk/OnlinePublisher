<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Utilities/StringUtils.php';

require_once 'Functions.php';



$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<form xmlns="uri:Form" action="RemoveFromGroup.php" method="post">'.
'<list xmlns="uri:List" width="100%" margin="3" sort="true">'.
'<content>'.
'<headergroup>'.
'<header title="Rolle"/>'.
'<header title="Beskrivelse"/>'.
'<header title="Tilknyttet person"/>'.
'</headergroup>';


$sql="SELECT personrole.*, object.*, person.firstname, person.middlename, person.surname FROM object ,personrole LEFT JOIN person ON personrole.person_id = person.object_id where personrole.object_id = object.id";

$result = Database::select($sql);
while ($row = Database::next($result)) {
	
		$style="Standard";
		$status="Finished";
		$index=1;
	
	$gui.='<row link="RoleProperties.php?id='.$row['id'].'" target="_parent" style="'.$style.'">'.
	'<cell>'.	
	'<icon size="1" icon="Role/Administrator"/>'.
	'<text>'.StringUtils::escapeXML($row['title']).'</text>'.
	'</cell>'.
	'<cell>'.StringUtils::escapeXML($row['note']).'</cell>'.
	'<cell>'.
	'<text>'.concatenatePersonName(StringUtils::escapeXML($row['firstname']),StringUtils::escapeXML($row['middlename']),StringUtils::escapeXML($row['surname'])).'</text>'.
	'</cell>'.
	'</row>';
}

Database::free($result);

$gui.=
'</content>'.
'</list>'.
'</form>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("List","Form");
writeGui($xwg_skin,$elements,$gui);
?>