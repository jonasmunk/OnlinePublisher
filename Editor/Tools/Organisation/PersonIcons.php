<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';

require_once 'Functions.php';

$group = getPersonGroup();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<group xmlns="uri:Icon" width="100%" spacing="12" size="2" cellwidth="17%" >'.
'<row>';

$counter=0;

if ($group>0) {
	$sql="select * from person,persongroup_person, object where object.id = person.object_id AND persongroup_person.person_id=object.id and persongroup_person.persongroup_id=".$group." order by person.firstname, person.middlename, person.surname";
} else {
	$sql="select * FROM person, object WHERE person.object_id = object.id order by person.firstname, person.middlename, person.surname";
}

$result = Database::select($sql);
while ($row = Database::next($result)) {
	$counter++;
	if ($counter==7) {
		$gui.='</row><row>';
		$counter=1;
	}
	$gui.=
	'<icon title="'.encodeXML($row['title']).'" icon="Role/'.($row['sex']==1 ? 'Male' : 'Female').'" link="PersonProperties.php?id='.$row['id'].'" target="_parent"/>';
	}
Database::free($result);

$gui.=
'</row>'.
'</group>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Icon");
writeGui($xwg_skin,$elements,$gui);
?>