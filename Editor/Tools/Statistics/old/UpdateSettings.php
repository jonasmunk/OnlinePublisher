<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Statistics
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';

$ignoreInternalUsers = requestPostCheckbox('ignoreInternalUsers');
$ignoreRobots = requestPostCheckbox('ignoreRobots');
setToolSessionVar('statistics','ignoreInternalUsers',$ignoreInternalUsers);
setToolSessionVar('statistics','ignoreRobots',$ignoreRobots);

redirect("Settings.php");
?>