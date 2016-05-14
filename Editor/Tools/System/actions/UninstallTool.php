<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$key = Request::getString('key');

ToolService::uninstall($key);
?>