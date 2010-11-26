<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Templates
 */
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