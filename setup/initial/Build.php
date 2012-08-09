<?php
require_once('../../Editor/Include/Private.php');

if (file_exists('../../Config/Setup.php')) {
	Response::sendObject(array('failure'=>'The configuration file already exists'));
	exit;
}
if (!is_dir($basePath."Config/") || !is_writable($basePath."Config/")) {
	Response::sendObject(array('failure'=>'The configuration folder is not writable'));
	exit;
}

$url = Request::getString('baseUrl');
$databaseHost = Request::getString('databaseHost');
$databaseUser = Request::getString('databaseUser');
$databasePassword = Request::getString('databasePassword');
$database = Request::getString('databaseName');
$superUser = Request::getString('superUser');
$superPassword = Request::getString('superPassword');

$config = array();
$config[] = '<?';
$config[] = '$superUser="'.$superUser.'";';
$config[] = '$superPassword="'.$superPassword.'";';
$config[] = '$baseUrl="'.$url.'";';
$config[] = '$database_host="'.$databaseHost.'";';
$config[] = '$database_user="'.$databaseUser.'";';
$config[] = '$database_password="'.$databasePassword.'";';
$config[] = '$database="'.$database.'";';
$config[] = '?>';

$data = implode("\r\n",$config);
if (@file_put_contents($basePath."Config/Setup.php",$data)) {
	Response::sendObject(array('success'=>true));
} else {
	Response::sendObject(array('failure'=>'Unable to create the configuration file (permission denied)'));
}

?>