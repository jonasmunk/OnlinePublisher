<?php
/**
 * @package OnlinePublisher
 * @subpackage Public
 */
if (!ini_get('display_errors')) {
    ini_set('display_errors', 1);
}
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
	$error = '<title>Siden er ikke tilgængelig i øjeblikket.</title>'.
	'<note>Prøv venligst igen senere.</note>';
	RenderingService::displayError($error);
	exit;
}

$file = Request::getInt('file',-1);
$id = Request::getInt('id',-1);
$path = Request::getString('path');

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
	$id=RenderingService::findPage('home',"");
}
if ($file>0) {
	RenderingService::showFile($file);
}
else {
	$allowDisabled = Request::exists('preview');
	if ($path=='') {
		$page = RenderingService::buildPage($id,$allowDisabled);
	} else {
		$page = RenderingService::buildPage(-1,$allowDisabled,$path);
	}
    // If the page has redirect
	if ($page && $page['redirect']!==false) {
		Response::redirect($page['redirect']);
	}
	// If the page is secure
	else if ($page && $page['secure']) {
		if (Request::exists('preview')) {
			RenderingService::writePage($id,$page,$relative,$samePageBaseUrl);
		}
		else if ($user = ExternalSession::getUser()) {
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
	elseif ($page) {
		RenderingService::writePage($id,$page,$relative,$samePageBaseUrl);
	}
	// If page was not found
	else {
		// See if there is a page redirect
		$sql = "select page.id,page.path from path left join page on page.id=path.page_id where path.path=".Database::text($path);
		if ($row = Database::selectFirst($sql)) {
			if ($row['path']!='') {
				Response::redirectMoved($baseUrl.$row['path']);
			} else if ($row['id']>0) {
				Response::redirectMoved($baseUrl.'?id='.$row['id']);
			} else {
				RenderingService::sendNotFound();
			}
		} else {
			RenderingService::sendNotFound();
		}
	}
}


?>