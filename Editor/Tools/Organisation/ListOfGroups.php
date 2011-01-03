<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Utilities/StringUtils.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<list xmlns="uri:List" width="100%" margin="3" sort="true">'.
'<content>'.
'<headergroup>'.
'<header title="Titel" width="40%"/>'.
'<header title="Beskrivelse" width="50%"/>'.
'<header title="Antal" width="10%" align="center" type="number"/>'.
'</headergroup>';

$sql="select distinct object.id,object.title,object.note,count(person.object_id) as personcount from persongroup, persongroup_person, person,object  where persongroup_person.persongroup_id=persongroup.object_id and persongroup_person.person_id = person.object_id and object.id=persongroup.object_id group by persongroup.object_id union select object.id,object.title,object.note,'0' from object left join persongroup_person on persongroup_person.persongroup_id=object.id where object.type='persongroup' and persongroup_person.person_id is null order by title";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<row link="persongroup.php?id='.$row['id'].'" target="_parent">'.
	'<cell>'.
	'<icon size="1" icon="Element/Folder"/>'.
	'<text>'.StringUtils::escapeXML($row['title']).'</text>'.
	'</cell>'.
	'<cell>'.StringUtils::escapeXML($row['note']).'</cell>'.
	'<cell>'.StringUtils::escapeXML($row['personcount']).'</cell>'.
	'</row>';
}
Database::free($result);

$gui.=
'</content>'.
'</list>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("List");
writeGui($xwg_skin,$elements,$gui);
?>