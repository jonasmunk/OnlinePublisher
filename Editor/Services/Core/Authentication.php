<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Core
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Public.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Interface/In2iGui.php';
require_once '../../Classes/Core/InternalSession.php';
require_once '../../Classes/Services/ToolService.php';
require_once '../../Classes/Objects/User.php';

if (Request::isPost()) {
	
	$page = Request::getPostInt('page');
	$username = Request::getPostString('username');
	$password = Request::getPostString('password');
	
	if (InternalSession::logIn($username,$password)) {
		ToolService::install('System'); // Ensure that the system tool is present
		In2iGui::sendObject(array('success' => true));
	} else {
		usleep(rand(1500000,3000000)); // Wait for random amount of time
		In2iGui::sendObject(array('success' => false));
	}
	
} else {
	
	usleep(rand(1500000,3000000));  // Wait for random amount of time
	In2iGui::sendObject(array('success' => false));

}
exit;
?>