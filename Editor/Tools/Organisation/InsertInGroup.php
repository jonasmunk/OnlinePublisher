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

$id = requestPostNumber('id',0);
$persons = requestPostArray('persons');


for ($i=0;$i<count($persons);$i++) {
	$sql="insert into persongroup_person (person_id, persongroup_id)".
	" values (".$persons[$i].",".$id.")";
	Database::insert($sql);
}

setUpdateHierarchy(true);
redirect('Persongroup.php');
?>