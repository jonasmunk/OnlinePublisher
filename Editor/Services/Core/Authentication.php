<?php
/**
 * @package OnlinePublisher
 * @subpackage Services
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Public.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/User.php';

if (Request::isPost()) {
	$page = Request::getPostInt('page');
	$username=Request::getPostString('username');
	$password=Request::getPostString('password');
	if (InternalSession::logIn($username,$password)) {
		In2iGui::sendObject(array('success' => true));
	} else {
		usleep(rand(5000000,10000000));
		In2iGui::sendObject(array('success' => false));
	}
} else {
	usleep(rand(5000000,10000000));
	In2iGui::sendObject(array('success' => false));
}
exit;
?>