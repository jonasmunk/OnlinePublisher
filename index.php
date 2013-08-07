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
require_once 'Editor/Include/Public.php';

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
if (Request::getBoolean('logout')) {
	ExternalSession::logOut();
}


//if (CacheService::sendCachedPage($id,$path)) {
//	exit;
//}

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

if ($id==-1 && StringUtils::isBlank($path)) {
	$id = RenderingService::findPage('home');
}

$page = RenderingService::buildPage($id,$path,Request::getParameters());
if (!$page) {
	$id = RenderingService::findPage('home');
	if ($id==null) {
		$error = '<title>Ingen forside!</title>'.
		'<note>Der er ikke opsat en forside til dette website.
		Hvis du er redaktør på siden bør du logge ind i redigeringsværktøjet
		og opsætte hvilken side der skal være forsiden.
		</note>';
		RenderingService::displayError($error,'');
		exit;
	}
	$page = RenderingService::buildPage($id);
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
			RenderingService::writePage($id,$path,$page,$relative,$samePageBaseUrl);
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
	RenderingService::writePage($id,$path,$page,$relative,$samePageBaseUrl);
}


?>