<?php
/**
 * @package OnlinePublisher
 * @subpackage Public
 */
if (!ini_get('display_errors')) {
    ini_set('display_errors', 1);
}
// If no setup, go to setup UI
if (!file_exists('Config/Setup.php')) {
	header('Location: '.dirname($_SERVER['PHP_SELF']).'/setup/initial/');
	exit;
}
require_once 'Config/Setup.php';
require_once 'Editor/Include/Public.php';
require_once 'Editor/Include/XmlWebGui.php';
require_once 'Editor/Classes/Request.php';
require_once 'Editor/Classes/Response.php';
require_once 'Editor/Classes/Database.php';
require_once 'Editor/Classes/Services/RenderingService.php';
require_once 'Editor/Classes/ExternalSession.php';

session_set_cookie_params(0);
session_start();

if (!Database::testConnection()) {
	$error = '<title>The page is not available at the moment</title>'.
	'<note>Please try again later</note>';
	RenderingService::displayError($error);
	exit;
}

$file = Request::getInt('file',-1);
$id = Request::getInt('id',-1);
$path = Request::getString('path');


if ($file>0) {
	RenderingService::showFile($file);
	exit;
}

if (strlen($path)>0) {
	$relative = str_repeat('../',substr_count($path,'/'));
	$samePageBaseUrl = $relative.$path.'?';
} else {
	$relative = '';
	$samePageBaseUrl = '?id='.$id.'&amp;';
}
if (strlen($relative)==0) {
	$relative = './';
}

if (Request::getBoolean('logout')) {
	ExternalSession::logOut();
}

if ($id==-1) {
	$id = RenderingService::findPage('home',"");
}
if ($path=='') {
	$page = RenderingService::buildPage($id);
} else {
	$page = RenderingService::buildPage(-1,$path);
}

if (!$page) {
	RenderingService::handleMissingPage($path);
}
   // If the page has redirect
else if ($page['redirect']!==false) {
	Response::redirect($page['redirect']);
}
// If the page is secure
else if ($page['secure']) {
	if ($user = ExternalSession::getUser()) {
		if (RenderingService::userHasAccessToPage($user['id'],$id)) {
			RenderingService::writePage($id,$page,$relative,$samePageBaseUrl);
		}
		else {
			RenderingService::goToAuthenticationPage($id);
		}
	}
	else {
		RenderingService::goToAuthenticationPage($id);
	}
}
// If nothing special about page
else {
	RenderingService::writePage($id,$page,$relative,$samePageBaseUrl);
}


?>