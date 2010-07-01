<?php
/**
 * @package OnlinePublisher
 * @subpackage Tool.System
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/EmailUtil.php';
require_once '../../Classes/In2iGui.php';

$setup = array(
	'server' => EmailUtil::getServer(),
	'port' => EmailUtil::getPort(),
	'username' => EmailUtil::getUsername(),
	'password' => EmailUtil::getPassword(),
	'standardEmail' => EmailUtil::getStandardEmail(),
	'standardName' => EmailUtil::getStandardName()
);

In2iGui::sendObject($setup);
?>