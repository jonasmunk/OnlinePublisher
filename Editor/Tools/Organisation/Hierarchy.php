<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Utilities/StringUtils.php';

require_once 'Functions.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<hierarchy xmlns="uri:Hierarchy" persistence="true" unique="tools-images-hierarchy">'.


$gui.='<element icon="Tool/User" title="Bibliotek" link="Library.php" target="Right">';
	
$sql="SELECT object.*,person.sex FROM object,person WHERE person.object_id = object.id order by title";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<element icon="Role/'.($row['sex']==1 ? 'Male' : 'Female').'" title="'.StringUtils::escapeXML($row['title']).'" link="PersonProperties.php?id='.$row['id'].'&amp;group=0" target="Right"/>';
}
Database::free($result);

$gui.='</element>';

$gui.='<element icon="Element/Folders" title="Grupper" link="Groups.php" target="Right">';
$sql="select * from persongroup, object where persongroup.object_id = object.id order by title";
$result = Database::select($sql); 
while ($row = Database::next($result)) {
	$gui.='<element icon="Element/Folder" title="'.StringUtils::escapeXML($row['title']).'" link="Persongroup.php?id='.$row['id'].'" target="Right">';
	
	$sql="select person.* from person,persongroup_person,object where object.id = person.object_id AND persongroup_person.person_id=object.id and persongroup_person.persongroup_id=".$row['id']." order by person.firstname";
	$result_person = Database::select($sql);
	while ($person = Database::next($result_person)) {
		$gui.='<element icon="Role/'.($person['sex']==1 ? 'Male' : 'Female').'" title="'.concatenatePersonName(StringUtils::escapeXML($person['firstname']),StringUtils::escapeXML($person['middlename']),StringUtils::escapeXML($person['surname'])).'" link="PersonProperties.php?id='.$person['object_id'].'&amp;group='.$row['id'].'" target="Right"/>';
	}
	$gui.='</element>';
	Database::free($result_person);
}
Database::free($result);

$gui.=
'<element title="Ny gruppe" icon="Basic/Add" link="NewPersongroup.php" target="Right"/>'.
'</element>'.

'<element title="Roller" icon="Role/Administrator" link="Roles.php" target="Right">';
$sql="select * from object where type='personrole' order by title";
$result = Database::select($sql); 
while ($row = Database::next($result)) {
		$gui.='<element icon="Role/Administrator" title="'.StringUtils::escapeXML($row['title']).'" link="RoleProperties.php?id='.$row['id'].'" target="Right"/>';
}
Database::free($result);
$gui.=
'<element title="Ny rolle" icon="Basic/Add" link="NewRole.php" target="Right"/>'.
'</element>';

$gui.=
'</hierarchy>'.
'<refresh xmlns="uri:Script" source="HierarchyUpdateCheck.php" interval="3000"/>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Hierarchy","Script");
writeGui($xwg_skin,$elements,$gui);
?>