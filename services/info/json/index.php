<?
require_once '../../../Config/Setup.php';
require_once '../../../Editor/Include/Public.php';
require_once('../../../Editor/Classes/SystemInfo.php');
require_once('../../../Editor/Classes/Response.php');
require_once('../../../Editor/Classes/Services/TemplateService.php');
require_once('../../../Editor/Classes/Services/MailService.php');
require_once('../../../Editor/Classes/Services/ToolService.php');
require_once('../../../Editor/Classes/Utilities/StringUtils.php');

Response::sendObject(array(
	'date' => SystemInfo::getDate(),
	'templates' => array(
		'installed' => TemplateService::getInstalledTemplateKeys(),
		'used' => TemplateService::getUsedTemplates()
	),
	'tools' => array(
		'installed' => ToolService::getInstalled()
	),
	'email' => array(
		'enabled' => MailService::getEnabled(),
		'server' => StringUtils::isNotBlank(MailService::getServer()),
		'username' => StringUtils::isNotBlank(MailService::getUsername()),
		'password' => StringUtils::isNotBlank(MailService::getPassword()),
		'standardEmail' => StringUtils::isNotBlank(MailService::getStandardEmail()),
		'standardName' => StringUtils::isNotBlank(MailService::getStandardName()),
		'feedbackEmail' => StringUtils::isNotBlank(MailService::getFeedbackEmail()),
		'feedbackName' => StringUtils::isNotBlank(MailService::getFeedbackName())
	)
));
?>