<?php
require_once '../../../Editor/Include/Public.php';

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
		'server' => Strings::isNotBlank(MailService::getServer()),
		'username' => Strings::isNotBlank(MailService::getUsername()),
		'password' => Strings::isNotBlank(MailService::getPassword()),
		'standardEmail' => Strings::isNotBlank(MailService::getStandardEmail()),
		'standardName' => Strings::isNotBlank(MailService::getStandardName()),
		'feedbackEmail' => Strings::isNotBlank(MailService::getFeedbackEmail()),
		'feedbackName' => Strings::isNotBlank(MailService::getFeedbackName())
	)
));
?>