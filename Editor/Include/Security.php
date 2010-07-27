<?php
/**
 * @package OnlinePublisher
 * @subpackage Include
 */
session_set_cookie_params(0);
session_start();
date_default_timezone_set('Europe/Copenhagen');
require_once($basePath."Editor/Classes/InternalSession.php");

// If not logged in
if (!InternalSession::isLoggedIn()) {
	if (isset($_GET['page'])) {
		header("Location: ".$baseUrl."Editor/Authentication.php?notloggedin=true&page=".$_GET['page']); 
	}
	else {
		header("Location: ".$baseUrl."Editor/Authentication.php?notloggedin=true"); 
	}
	exit;
}
// If timed out
else if (InternalSession::isTimedOut()) {
	InternalSession::logOut();
	if (isset($_GET['page'])) {
		header("Location: ".$baseUrl."Editor/Authentication.php?timeout=true&page=".$_GET['page']);
	} else {
		header("Location: ".$baseUrl."Editor/Authentication.php?timeout=true");
	}
	exit;
}
// update timestamp if nothing is wrong
else {
	InternalSession::registerActivity();
}

function __autoload($class_name) {
	global $basePath;
    require_once $basePath.'Editor/Classes/'.$class_name . '.php';
}
?>