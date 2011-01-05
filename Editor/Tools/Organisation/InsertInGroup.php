<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Request.php';
require_once 'Functions.php';

$id = Request::getInt('id');
$persons = Request::getArray('persons');


for ($i=0;$i<count($persons);$i++) {
	$sql="insert into persongroup_person (person_id, persongroup_id)".
	" values (".$persons[$i].",".$id.")";
	Database::insert($sql);
}

setUpdateHierarchy(true);
Response::redirect('Persongroup.php');
?>