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
'<form xmlns="uri:Form" action="RemoveFromGroup.php" method="post">'.
'<list xmlns="uri:List" width="100%" margin="3" sort="true">'.
'<content>'.
'<headergroup>'.
($group>0 ? '<header width="1%"/>' : '').
'<header title="Navn" width="30%"/>'.
'<header title="Jobtitel"/>'.
'<header title="Adresse"/>'.
'<header title="Telefon"/>'.
'<header title="Email"/>'.
'</headergroup>';

if ($group>0) {
	$sql="select object.id, person.* , object.created, object.updated from person,persongroup_person, object where persongroup_person.person_id=object.id and object.id = person.object_id and persongroup_person.persongroup_id=".$group." order by person.firstname,person.middlename,person.surname";
} else {
	$sql="select * FROM person, object where object.id = person.object_id order by person.firstname,person.middlename,person.surname;";
}
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$addr = $row['streetname'].' '.$row['zipcode'].' '.$row['city'].' '.$row['country'];
	$gui.='<row link="PersonProperties.php?id='.$row['id'].'" target="_parent">'.
	($group>0 ? '<cell><checkbox name="persons[]" value="'.$row['id'].'"/></cell>' : '').
	'<cell>'.
	'<icon size="1" icon="Role/'.($row['sex']==1 ? 'Male' : 'Female').'"/>'.
	'<text>'.concatenatePersonName(encodeXML($row['firstname']),encodeXML($row['middlename']),encodeXML($row['surname'])).'</text>'.
	'</cell>'.
	'<cell>'.encodeXML($row['jobtitle']).'</cell>'.
	'<cell>'.encodeXML($addr).'</cell>'.
	'<cell>'.encodeXML($row['phone_private']).'<break/>'.encodeXML($row['phone_job']).'</cell>'.
	'<cell>'.encodeXML($row['email_private']).'<break/>'.encodeXML($row['email_job']).'</cell>'.
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