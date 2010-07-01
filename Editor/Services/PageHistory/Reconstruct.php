<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.PageHistory
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Page.php';



$historyId = requestGetNumber('id');
$pageId = getPageId();

$page = Page::load($pageId);

$page->reconstruct($historyId);

redirect("../../Template/Edit.php");
?>