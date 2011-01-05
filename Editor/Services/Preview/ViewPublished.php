<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Page.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/InternalSession.php';

$page = Page::load(InternalSession::getPageId());

if (strlen($page->getPath())>0) {
	Response::redirect('../../../'.$page->getPath());
} else {
	Response::redirect('../../../?id='.$page->getId());
}
?>