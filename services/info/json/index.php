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