<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Include/Private.php';

$file = Request::getString('file');
UI::renderFile('../guis/' . $file);
?>