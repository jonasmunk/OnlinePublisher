<?php
/**
 * @package OnlinePublisher
 * @subpackage Setup
 */

require_once '../Config/Setup.php';
require_once '../Editor/Include/Public.php';
require_once '../Editor/Classes/Request.php';
require_once '../Editor/Classes/Response.php';
require_once 'Functions.php';

$username = Request::getString('username');
$password = Request::getString('password');
if (Request::isPost() && $username==$superUser && $password==$superPassword) {
	setupLogIn();
	Response::sendObject(array('success' => true));
} else {
	usleep(rand(5000000,10000000));
	Response::sendObject(array('success' => false));
}