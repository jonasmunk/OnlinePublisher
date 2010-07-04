<?php
/**
 * @package OnlinePublisher
 * @subpackage Public
 */
if (!file_exists('Config/Setup.php')) {
	header('Location: setup/initial/');
	exit;
}
date_default_timezone_set('Europe/Copenhagen');
require_once 'Config/Setup.php';	
require_once 'Editor/Include/Functions.php';
require_once 'Editor/Include/XmlWebGui.php';
require_once 'Editor/Include/Publishing.php';
require_once 'Editor/Classes/Request.php';
require_once 'Editor/Classes/Response.php';
require_once 'Editor/Classes/Database.php';

startSession();

if (!Database::testConnection()) {
	error_log('dasdadsaaa');
	$error = '<title>Siden er ikke tilgængelig i øjeblikket.</title>'.
	'<note>Prøv venligst igen senere.</note>';
	displayError($error);
	exit;
}

if (requestGetExists('designsession')) {
	$_SESSION['debug.design']=requestGetText('designsession');
}
if (requestGetBoolean('resetdesign')) {
	unset($_SESSION['debug.design']);
}

$file = requestGetNumber('file',-1);
$id = requestGetNumber('id',-1);
$path = requestGetText('path');
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

if (requestGetBoolean('logout')) {
	logOutExternalUser();
}

if ($id==-1) {
	$id=findPage('home',"");
}
if ($file>0) {
	showFile($file);
}
else {
	$allowDisabled = requestGetExists('preview');
	if ($path=='') {
		$page = buildPage($id,$allowDisabled);
	} else {
		$page = buildPage(-1,$allowDisabled,$path);
	}
    // If the page has redirect
	if ($page && $page['redirect']!==false) {
		error_log($page['redirect']);
		redirect($page['redirect']);
	}
	// If the page is secure
	else if ($page && $page['secure']) {
		if (requestGetExists('preview')) {
			writePage($id,$page,$relative,$samePageBaseUrl);
		}
		else if ($user = getExternalUser()) {
			if (userHasAccessToPage($user['id'],$id)) {
				writePage($id,$page,$relative,$samePageBaseUrl);
			}
			else {
				goToAuthenticationPage($id);
			}
		}
		else {
			goToAuthenticationPage($id);
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
				displayError($error);
			}
		} else {
			$error = '<title>Siden findes ikke!</title>'.
			'<note>Den forespurgte side findes ikke på dette website.</note>';
			displayError($error);
		}
	}
}

function writePage($id,&$page,$relative,$samePageBaseUrl) {
	if (Request::getBoolean('viewsource')) {
		header('Content-type: text/xml');
		echo $page['xml'];
	} else {
		$html = applyStylesheet($page['xml'],getDesign($page['design']),$page['template'],'',$relative,$relative,$samePageBaseUrl,false);
		header("Last-Modified: " . gmdate("D, d M Y H:i:s",$page['published']) . " GMT");
		header("Content-Type: text/html; charset=UTF-8");
		echo $html;
		//statistics('page',$id);
	}
}

function showFile($id) {
	$sql = "select * from file where object_id = ".$id;
	if ($row = Database::selectFirst($sql)) {
		//statistics('file',$id);
		Response::redirect('files/'.$row['filename']);
	} else {
		$error = '<title>Filen findes ikke!</title>'.
		'<note>Den forespurgte fil findes ikke på dette website.</note>';
		displayError($error);
	}
}

/*
function statistics($type,$id) {
	if (requestGetExists('preview')) {
		return;
	}
	require_once 'Editor/Include/GeoIP.php';
	global $basePath;
	$ip = getenv("REMOTE_ADDR");
	$method = getenv('REQUEST_METHOD');
	$uri = getenv('REQUEST_URI');
	$language = getenv('HTTP_ACCEPT_LANGUAGE');
	$session = session_id();
	$agent = $_SERVER['HTTP_USER_AGENT'];
	if (isset($_SERVER['HTTP_REFERER'])) {
		$referer = $_SERVER['HTTP_REFERER'];
	} else {
		$referer = '';
	}
	$userhost = '';
	if(isset($_SERVER['REMOTE_HOST'])) $userhost = $_SERVER['REMOTE_HOST'];
	$country='';
	$oldErrorLevel = error_reporting(0);
	$gi = geoip_open($basePath."Editor/Resources/GeoIP.dat",GEOIP_STANDARD);
	$country = geoip_country_code_by_addr($gi, $ip);
	geoip_close($gi);

	$sql="insert into statistics (time,type,value,ip,country,agent,method,uri,language,session,referer,host) values (".
	"now(),".Database::text($type).",".$id.",".Database::text($ip).",".Database::text($country).",".Database::text($agent).",".Database::text($method).",".Database::text($uri).",".Database::text($language).",".Database::text($session).",".Database::text($referer).",".Database::text($userhost).")";
	Database::insert($sql);
	error_reporting($oldErrorLevel);
}
*/

function getCacheFile($id) {
	global $basePath;
	return $basePath.'cache/pages/'.$id.'.xml';
}

function getDesign($design) {
	if (requestGetExists('design')) {
		$design = requestGetText('design');
	}
	else if (isset($_SESSION['debug.design'])) {
		$design = $_SESSION['debug.design'];
	}
	return $design;
}
?>