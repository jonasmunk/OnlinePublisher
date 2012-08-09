<?php
require_once '../../Config/Setup.php';
require_once '../../Editor/Include/Public.php';
require_once('../../Editor/Classes/Objects/Person.php');
require_once('../../Editor/Info/SystemInfo.php');
require_once '../../Classes/Utilities/StringUtils.php';

$name = Request::getString('name');
$email = Request::getString('email');

if (strlen($name)==0) {
	$error = '<error key="noname"/>';
} else if (strlen($email)==0) {
	$error = '<error key="noemail"/>';
} else if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) {
	$error = '<error key="invalidemail"/>';
}
if ($error!='') {
	$values .= '<value key="name" value="'.StringUtils::escapeXML($name).'"/>';
	$values .= '<value key="email" value="'.StringUtils::escapeXML($email).'"/>';
} else {
	//$this->subscribe($name,$email);
}
?>