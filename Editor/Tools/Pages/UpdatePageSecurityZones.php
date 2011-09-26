<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Database.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Core/Request.php';

$id = Request::getInt('id');
$zones = Request::getArray('zones');

$sql = "delete from securityzone_page where page_id = ".$id;
Database::delete($sql);

if (count($zones)>0) {
	foreach ($zones as $zone) {
		$sql = "insert into securityzone_page (securityzone_id,page_id) values (".$zone.",".$id.")";
		Database::insert($sql);
	}
	$sql = "update page set secure=1 where id = ".$id;
	Database::update($sql);
}
else {
	$sql = "update page set secure=0 where id = ".$id;
	Database::update($sql);
}

Response::redirect('EditPageSecurity.php?id='.$id);
?>