<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Security
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Securityzone.php';

$id = requestPostNumber('id',0);
$users = requestPostArray('user');

if (count($users)>0) {
	$zone = SecurityZone::load($id);

	foreach ($users as $user) {
		$zone->addUser($user);
	}

}


redirect('EditZoneUsers.php?id='.$id);
?>