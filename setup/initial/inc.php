<?php
$basePath = substr(dirname(__file__), 0, -14).'/';
error_log($basePath);
date_default_timezone_set('Europe/Copenhagen');
require_once($basePath."Editor/Include/Classloader.php");

$baseUrl = getBaseUrl();

function getBaseUrl() {
	$uri = $_SERVER['REQUEST_URI'];
	$find = 'setup/initial/';
	$pos = strpos($uri,$find);
	return Strings::concatUrl('http://'.$_SERVER['SERVER_NAME'],substr($uri,0,$pos));
}

function buildConfig($values) {
	$config = array();
	$config[] = "<?php";
	$config[] = "\$CONFIG = array(";
	$config[] = "	'baseUrl' => '".$values['baseUrl']."',";
	$config[] = "";
	$config[] = "	'super' => array(";
	$config[] = "		'user' => '".$values['superUser']."',";
	$config[] = "		'password' => '".$values['superPassword']."',";
	$config[] = "	),";
	$config[] = "";
	$config[] = "	'database' => array(";
	$config[] = "		'host' => '".$values['databaseHost']."',";
	$config[] = "		'user' => '".$values['databaseUser']."',";
	$config[] = "		'password' => '".$values['databasePassword']."',";
	$config[] = "		'database' => '".$values['database']."'";
	$config[] = "	)";
	$config[] = ');';
	$config[] = '?>';
	return implode("\r\n",$config);
}
?>