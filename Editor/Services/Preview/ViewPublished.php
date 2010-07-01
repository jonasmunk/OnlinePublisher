<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Page.php';

$page = Page::load(getPageId());

if (strlen($page->getPath())>0) {
	redirect('../../../'.$page->getPath());
} else {
	redirect('../../../?id='.$page->getId());
}
?>