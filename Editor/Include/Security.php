<?php
/**
 * @package OnlinePublisher
 * @subpackage Include
 */
session_set_cookie_params(0);
session_start();
date_default_timezone_set('Europe/Copenhagen');
$basePath = substr(__FILE__, 0,strpos(__FILE__,'Editor'));

require_once($basePath."Editor/Classes/InternalSession.php");

if ($_SESSION['core.debug.simulateLatency']) {
	usleep(rand(1000000,2000000));
}

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
	if (class_exists($class_name)) {
		return;
	}
	$folders = array('','Templates/','Services/','Utilities/','Objects/','Parts/','Model/','Network/','Modules/News/');
	foreach ($folders as $folder) {
		$path = $basePath.'Editor/Classes/'.$folder.$class_name . '.php';
		if (file_exists($path)) {
	    	require_once $path;
			break;
		}
	}
}
?>