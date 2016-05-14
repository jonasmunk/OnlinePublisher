<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../Include/Private.php';

$pageId = Request::getInt('pageId');
$accepted = Request::getBoolean('accepted');

$user = User::load(InternalSession::getUserId());
$page = Page::load($pageId);

if (!$user || !$page) {
	Response::badRequest();
	exit;
}

$review = Query::after('review')->withRelationTo($user,'reviewer')->withRelationFromPage($page,'reviewed')->first();
if ($review) {
	$review->setDate(time());
	$review->setAccepted($accepted);
	$review->save();
} else {
	$review = new Review();
	$review->setTitle('My review');
	$review->setAccepted($accepted);
	$review->setDate(time());
	$review->save();
	$review->publish();

	RelationsService::relatePageToObject($page,$review,'reviewed');
	RelationsService::relateObjectToObject($review,$user,'reviewer');	
}
?>