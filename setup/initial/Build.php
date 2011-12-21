<?php
require_once('../../Editor/Include/Public.php');
require_once('../../Editor/Classes/Core/Request.php');
require_once('../../Editor/Classes/Interface/In2iGui.php');

if (file_exists('../../Config/Setup.php')) {
	In2iGui::sendObject(array('failure'=>'The configuration file already exists'));
	exit;
}
if (!is_dir($basePath."Config/") || !is_writable($basePath."Config/")) {
	In2iGui::sendObject(array('failure'=>'The configuration folder is not writable'));
	exit;
}

$url = Request::getUnicodeString('baseUrl');
$databaseHost = Request::getUnicodeString('databaseHost');
$databaseUser = Request::getUnicodeString('databaseUser');
$databasePassword = Request::getUnicodeString('databasePassword');
$database = Request::getUnicodeString('databaseName');
$superUser = Request::getUnicodeString('superUser');
$superPassword = Request::getUnicodeString('superPassword');

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
	In2iGui::sendObject(array('success'=>true));
} else {
	In2iGui::sendObject(array('failure'=>'Unable to create the configuration file (permission denied)'));
}

?>