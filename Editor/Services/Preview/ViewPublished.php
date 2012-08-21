<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../Include/Private.php';

$page = Page::load(InternalSession::getPageId());

if (strlen($page->getPath())>0) {
	$path = $page->getPath();
	if (StringUtils::startsWith($path,'/')) {
		Response::redirect('../../..'.$path);
	} else {
		Response::redirect('../../../'.$path);
	}
} else {
	Response::redirect('../../../?id='.$page->getId());
}
?>