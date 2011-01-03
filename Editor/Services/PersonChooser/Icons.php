<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.PersonChooser
 */
require '../../../Config/Setup.php';
require '../../Include/Security.php';
require '../../Include/XmlWebGui.php';
require '../../Include/Functions.php';
require '../../Classes/Request.php';
require_once '../../Classes/Utilities/StringUtils.php';

$group = Request::getInt('group',0);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<group xmlns="uri:Icon" width="100%" spacing="12" size="3" cellwidth="20%" >'.
'<row>';

$counter=0;

if ($group==0) {
	$sql="select * from persongroup, object where object.id = persongroup.object_id order by title";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$counter++;
		if ($counter==6) {
			$gui.='</row><row>';
			$counter=1;
		}
		$gui.=
		'<icon title="'.StringUtils::escapeXML($row['title']).'" icon="Element/Album" link="Icons.php?group='.$row['id'].'"/>';
	}
	Database::free($result);
}

if ($group>0) {
	$sql="select person.* from person,persongroup_person where persongroup_person.person_id=person.object_id and persongroup_person.persongroup_id=".$group." order by firstname, surname, middlename";
}
else {
	$sql="SELECT person.* FROM person LEFT JOIN persongroup_person ON persongroup_person.person_id=person.object_id WHERE persongroup_person.person_id IS NULL;";
}
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$counter++;
	if ($counter==6) {
		$gui.='</row><row>';
		$counter=1;
	}
	$gui.=
	'<icon title="'.concatenatePersonName(StringUtils::escapeXML($row['firstname']),StringUtils::escapeXML($row['middlename']),StringUtils::escapeXML($row['surname'])).'" icon="Role/'.($row['sex']==0 ? 'Male' : 'Female').'" link="javascript: window.parent.selectPerson('.$row['object_id'].');"/>';
}
Database::free($result);


$gui.=
'</row>'.
'</group>'.
'</interface>'.
'</xmlwebgui>';

function concatenatePersonName($firstname, $middlename, $surname){
	if(strlen($middlename) > 0){
		$surname = $middlename." ".$surname;
	}
	return $firstname." ".$surname;
}

$elements = array("Icon","Drag");
writeGui($xwg_skin,$elements,$gui);
?>
