<?php
/**
 * @package OnlinePublisher
 * @subpackage Setup
 */

require_once '../Config/Setup.php';
require_once '../Editor/Include/Functions.php';
require_once '../Editor/Classes/Settings.php';
require_once 'Functions.php';
require_once 'Security.php';

$workerServer = requestPostText('worker-server-address');
$extrapath = requestPostText('extrapath');
$neato = requestPostCheckbox('neato');


Settings::setSetting("system","environment","neato",$neato);
Settings::setSetting("system","environment","extrapath",$extrapath);
Settings::setSetting("system","environment","worker-server-address",$workerServer);


redirect('Integration.php');
?>