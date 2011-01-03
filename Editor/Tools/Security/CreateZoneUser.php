<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Security
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Securityzone.php';
require_once '../../Classes/Request.php';

$id = Request::getInt('id',0);
$users = Request::getArray('user');

if (count($users)>0) {
	$zone = SecurityZone::load($id);

	foreach ($users as $user) {
		$zone->addUser($user);
	}

}

Response::redirect('EditZoneUsers.php?id='.$id);
?>