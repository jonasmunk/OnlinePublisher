<?php
/**
 * @package OnlinePublisher
 * @subpackage Setup
 */

require_once '../Config/Setup.php';
require_once '../Editor/Include/Functions.php';
require_once '../Editor/Include/XmlWebGui.php';
require_once '../Editor/Classes/User.php';
require_once 'Functions.php';
require_once 'Security.php';

$fullname = requestPostText('fullname');
$username = requestPostText('username');
$password = requestPostText('password');

$user = new User();
$user->setUsername($username);
$user->setTitle($fullname);
$user->setPassword($password);
$user->setInternal(true);
$user->setAdministrator(true);
$user->create();
$user->publish();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../"/>'.
'<interface background="Window">'.
'<area width="350" top="20" align="center" xmlns="uri:Area"><content>'.
'<message xmlns="uri:Message" icon="Message">'.
'<title>Administrator oprettet</title>'.
'<description>'.
'Der er nu oprettet en administrator og du kan bruge denne til at tilgå systemet'.
'</description>'.
'<buttongroup size="Large">'.
'<button title="OK" link="Users.php" style="Hilited"/>'.
'</buttongroup>'.
'</message>'.
'</content></area>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Area","Message");
writeGui($xwg_skin,$elements,$gui);
?>