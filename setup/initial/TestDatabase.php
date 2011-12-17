<?php
require_once('../../Editor/Include/Public.php');
require_once('../../Editor/Classes/Interface/In2iGui.php');
require_once('../../Editor/Classes/Core/Request.php');

$host = Request::getString('databaseHost');
$name = Request::getString('databaseName');
$user = Request::getString('databaseUser');
$password = Request::getString('databasePassword');

if (!Database::testServerConnection($host,$user,$password)) {
	In2iGui::sendObject(array('server'=>false,'database'=>false));
} else if (!Database::testDatabaseConnection($host,$user,$password,$name)) {
	In2iGui::sendObject(array('server'=>true,'database'=>false));
} else {
	In2iGui::sendObject(array('server'=>true,'database'=>true));
}
?>