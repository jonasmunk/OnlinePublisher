<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../Include/Private.php';

$page = Page::load(InternalSession::getPageId());

if (strlen($page->getPath())>0) {
	Response::redirect('../../../'.$page->getPath());
} else {
	Response::redirect('../../../?id='.$page->getId());
}
?>