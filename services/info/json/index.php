<?
require_once '../../../Editor/Include/Public.php';
require_once('../../../Editor/Classes/SystemInfo.php');
require_once('../../../Editor/Classes/In2iGui.php');

In2iGui::sendObject(array(
	'date' => SystemInfo::getDate()
));
?>