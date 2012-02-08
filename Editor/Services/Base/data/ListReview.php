<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Base
 */
require_once '../../../Include/Private.php';

$subset = Request::getString('subset');
$query = array($subset => true);

$list = ReviewService::search($query);


$writer = new ListWriter();
	


$writer->startList()->
	startHeaders()->
		header(array('title'=>'Side','width'=>45))->
	endHeaders();

foreach ($list as $review) {
	$writer->startRow(array( 'kind' => 'page', 'id' => $review->getPageId() ))->
		startCell(array('icon'=>'common/page'))->
			text($review->getPageTitle())->
		endCell()->
	endRow();
}

$writer->endList();	

?>