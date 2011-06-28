<?php
/**
 * @package OnlinePublisher
 * @subpackage Tool.System
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Integration/GoogleAnalytics.php';
require_once '../../Classes/In2iGui.php';

$result = GoogleAnalytics::test();

In2iGui::sendObject($result);
?>