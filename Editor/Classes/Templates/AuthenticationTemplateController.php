<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Templates
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Templates/TemplateController.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class AuthenticationTemplateController extends TemplateController
{
	function AuthenticationTemplateController() {
		parent::TemplateController('authentication');
	}
	
	function isClientSide() {
		return true;
	}
}