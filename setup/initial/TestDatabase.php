<?php
require_once('../../Editor/Include/Public.php');

$host = Request::getString('databaseHost');
$name = Request::getString('databaseName');
$user = Request::getString('databaseUser');
$password = Request::getString('databasePassword');

if (!Database::testServerConnection($host,$user,$password)) {
	Response::sendUnicodeObject(array('server'=>false,'database'=>false));
} else if (!Database::testDatabaseConnection($host,$user,$password,$name)) {
	Response::sendUnicodeObject(array('server'=>true,'database'=>false));
} else {
	Response::sendUnicodeObject(array('server'=>true,'database'=>true));
}
?>