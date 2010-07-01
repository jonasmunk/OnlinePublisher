<?php
/**
 * @package OnlinePublisher
 * @subpackage Setup
 */

function setupIsLoggedIn() {
	$out = false;
	if (isset($_SESSION['setup.loggedin']) && $_SESSION['setup.loggedin']=='x') {
		$out=true;
	}
	return $out;
}

function setupIsTimedOut() {
	return !isset($_SESSION['setup.lastaccesstime']) || (time()-($_SESSION['setup.lastaccesstime'])>60*15);
}

function setupRegisterActivity() {
	$_SESSION['setup.lastaccesstime'] = time();
}

function setupLogIn() {
	session_start();
	setupRegisterActivity();
	$_SESSION['setup.loggedin']='x';
}

function setupLogOut() {
	session_start();
	$_SESSION['setup.loggedin']=null;
}

function setupGetPosition() {
	if (!isset($_SESSION['setup.position'])) {
		return 'Intro';
	}
	else {
		return $_SESSION['setup.position'];
	}
}

function setupSetPosition($pos) {
	$_SESSION['setup.position']=$pos;
}
?>