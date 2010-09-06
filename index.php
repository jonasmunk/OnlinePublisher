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
require_once 'Editor/Include/Functions.php';
require_once 'Editor/Include/XmlWebGui.php';
require_once 'Editor/Classes/Request.php';
require_once 'Editor/Classes/Response.php';
require_once 'Editor/Classes/Database.php';
require_once 'Editor/Classes/Services/RenderingService.php';
require_once 'Editor/Classes/ExternalSession.php';

startSession();

if (!Database::testConnection()) {
	$error = '<title>Siden er ikke tilgængelig i øjeblikket.</title>'.
	'<note>Prøv venligst igen senere.</note>';
	RenderingService::displayError($error);
	exit;
}

if (Request::exists('designsession')) {
	$_SESSION['debug.design']=Request::getString('designsession');
}
if (Request::getBoolean('resetdesign')) {
	unset($_SESSION['debug.design']);
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
	showFile($file);
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
		error_log($page['redirect']);
		redirect($page['redirect']);
	}
	// If the page is secure
	else if ($page && $page['secure']) {
		if (Request::exists('preview')) {
			writePage($id,$page,$relative,$samePageBaseUrl);
		}
		else if ($user = ExternalSession::getUser()) {
			if (RenderingService::userHasAccessToPage($user['id'],$id)) {
				writePage($id,$page,$relative,$samePageBaseUrl);
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
		writePage($id,$page,$relative,$samePageBaseUrl);
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
				$error = '<title>Siden findes ikke!</title>'.
				'<note>Den forespurgte side findes ikke på dette website.</note>';
				RenderingService::displayError($error);
			}
		} else {
			$error = '<title>Siden findes ikke!</title>'.
			'<note>Den forespurgte side findes ikke på dette website.</note>';
			RenderingService::displayError($error);
		}
	}
}

function writePage($id,&$page,$relative,$samePageBaseUrl) {
	if (Request::getBoolean('viewsource')) {
		header('Content-type: text/xml');
		echo $page['xml'];
	} else {
		$html = RenderingService::applyStylesheet($page['xml'],getDesign($page['design']),$page['template'],'',$relative,$relative,$samePageBaseUrl,false);
		header("Last-Modified: " . gmdate("D, d M Y H:i:s",$page['published']) . " GMT");
		header("Content-Type: text/html; charset=UTF-8");
		echo $html;
	}
}

function showFile($id) {
	$sql = "select * from file where object_id = ".$id;
	if ($row = Database::selectFirst($sql)) {
		Response::redirect('files/'.$row['filename']);
	} else {
		$error = '<title>Filen findes ikke!</title>'.
		'<note>Den forespurgte fil findes ikke på dette website.</note>';
		RenderingService::displayError($error);
	}
}

function getCacheFile($id) {
	global $basePath;
	return $basePath.'cache/pages/'.$id.'.xml';
}

function getDesign($design) {
	if (Request::exists('design')) {
		$design = Request::getString('design');
	}
	else if (isset($_SESSION['debug.design'])) {
		$design = $_SESSION['debug.design'];
	}
	return $design;
}
?>