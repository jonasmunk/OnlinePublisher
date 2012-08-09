<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');

$review = Query::after('review')->
	withRelationTo(InternalSession::getUserId(),'reviewer')->
	withRelationFromPage($id,'reviewed')->
	first();
	
$reviewStatus = 'none';

if ($review) {
	$reviewStatus = $review->getAccepted() ? 'accepted' : 'rejected';
}

Response::sendUnicodeObject(array(
	'changed' => PageService::isChanged($id),
	'review' => $reviewStatus
));
?>