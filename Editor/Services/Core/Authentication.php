<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Core
 */
require_once '../../Include/Public.php';

if (Request::isPost()) {
	
	$page = Request::getInt('page');
	$username = Request::getString('username');
	$password = Request::getString('password');
	
	if (InternalSession::logIn($username,$password)) {
		ToolService::install('System'); // Ensure that the system tool is present
		Response::sendObject(array('success' => true));
	} else {
		usleep(rand(1500000,3000000)); // Wait for random amount of time
		Response::sendObject(array('success' => false));
	}
	
} else {
	
	usleep(rand(1500000,3000000));  // Wait for random amount of time
	Response::sendObject(array('success' => false));

}
exit;
?>