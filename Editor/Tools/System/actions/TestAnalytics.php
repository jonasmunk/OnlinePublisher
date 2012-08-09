<?php
/**
 * @package OnlinePublisher
 * @subpackage Tool.System
 */
require_once '../../../Include/Private.php';

$result = GoogleAnalytics::test();

Response::sendUnicodeObject($result);
?>