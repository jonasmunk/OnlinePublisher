<?php
/**
 * @package OnlinePublisher
 * @subpackage Setup
 */

session_set_cookie_params(0);
session_start();
if (!setupIsLoggedIn() || setupIsTimedOut()) {
	header("Location: ".$baseUrl."setup/Authentication.php");
	exit;
}
setupRegisterActivity();
?>