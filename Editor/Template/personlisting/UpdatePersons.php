<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.PersonListing
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Request.php';
require_once 'Functions.php';

$id = getPersonListingId();
$groups = Request::getArray('group');
//print_r($groups);
//exit;

$sql="delete from personlisting_persongroup where page_id=".$id;
Database::delete($sql);

foreach ($groups as $group) {
	$sql="insert into personlisting_persongroup (page_id, persongroup_id)".
	" values (".$id.",".$group.")";
	Database::insert($sql);
}

$sql="update page set changed=now() where id=".$id;
Database::update($sql);

Response::redirect('Persons.php');
?>