<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../Include/Private.php';

$pageId = Request::getInt('pageId');

$list = Query::after('review')->withRelationFromPage($pageId,'reviewed')->get();

$writer = new ListWriter();

$writer->startList()->
	startHeaders()->
	header(array('width' => 1))->
	header(array('title' => 'Bruger'))->
	header(array('title' => 'Tid'))->
	endHeaders();

foreach ($list as $review) {
	$user = Query::after('user')->withRelationFrom($review,'reviewer')->first();
	$writer->startRow()->
		startCell()->icon(array('icon' => $review->getAccepted() ? 'common/success' : 'common/stop'))->endCell()->
		startCell()->text($user ? $user->getTitle() : 'Ukendt')->endCell()->
		startCell()->text(DateUtils::formatFuzzy($review->getDate()))->endCell()->
	endRow();
}
$writer->endList();
?>