<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Base
 */
require_once '../../../Include/Private.php';

$writer = new ListWriter();
	
	$writer->startList()->
		startHeaders()->
			header(array('title'=>'Side','width'=>45))->
		endHeaders();
	$sql="select page.id as page_id, page.title as page_title,'' as user_title, null as date, -1 as accepted 
		from page where page.id not in (select relation.from_object_id from relation,review 
		where relation.to_type='object' and relation.to_object_id=review.object_id)
		order by date desc,page_title";
	
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$writer->startRow(array('kind'=>'page','id'=>$row['page_id']))->
			startCell(array('icon'=>'common/page'))->text($row['page_title'])->
			endCell()->
		endRow();
			
	}
	Database::free($result);
	
	$writer->endList();	

?>