<?
require_once '../../../Config/Setup.php';
require_once '../../../Editor/Include/Public.php';
require_once('../../../Editor/Classes/SystemInfo.php');
require_once('../../../Editor/Classes/Response.php');
require_once('../../../Editor/Classes/Services/TemplateService.php');
require_once('../../../Editor/Classes/Tool.php');

Response::sendObject(array(
	'date' => SystemInfo::getDate(),
	'templates' => array(
		'installed' => TemplateService::getInstalledTemplateKeys(),
		'used' => TemplateService::getUsedTemplates()
	),
	'tools' => Tool::getInstalledToolKeys()
));
?>