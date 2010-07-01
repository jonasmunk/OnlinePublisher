<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.PersonChooser
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<form xmlns="uri:Form" action="RemoveFromGroup.php" method="post">'.
'<list xmlns="uri:List" width="100%" margin="3" sort="true">'.
'<content>'.
'<headergroup>'.
'<header title="Navn" width="30%"/>'.
'<header title="Jobtitel"/>'.
'<header title="Adresse"/>'.
'<header title="Telefon"/>'.
'<header title="Email"/>'.
'</headergroup>';

$sql="select object.id, person.* from person,object where object.id = person.object_id  order by person.firstname,person.middlename,person.surname";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	
	$addr = $row['streetname'].' '.$row['zipcode'].' '.$row['city'].' '.$row['country'];
	$gui.='<row link="javascript: window.parent.selectPerson('.$row['id'].');" target="_parent">'.
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

function concatenatePersonName($firstname, $middlename, $surname){
	if(strlen($middlename) > 0){
		$surname = $middlename." ".$surname;
	}
	return $firstname." ".$surname;
}

$elements = array("List");
writeGui($xwg_skin,$elements,$gui);
?>
