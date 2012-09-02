<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Clipboard
 */
require_once '../../Include/Private.php';

$sectionId = Request::getInt('sectionId');


ClipboardService::copySection($sectionId);
?>