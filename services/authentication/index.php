<?php
/**
 * @package OnlinePublisher
 * @subpackage Public.Services.Authentication
 */
require_once '../../Editor/Include/Client.php';

// TODO Is it necessary to start the session each time?
session_set_cookie_params(0);
session_start();

$username = Request::getString('username');
$password = Request::getString('password');

if ($user = ExternalSession::logIn($username,$password)) {
    Log::debug('User loggend in: '.$username);
} else {
    Response::unauthorized();
}

?>