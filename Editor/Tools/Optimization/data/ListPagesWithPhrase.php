<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';

$id = Request::getId();

$phrase = Testphrase::load($id);

$result = Database::select($sql);

$writer = new ListWriter();

$writer->startList();
$writer->startHeaders();
$writer->header(array('title'=>'Side'));
$writer->header(array('width'=>1));
$writer->endHeaders();

$sql = "select id,title from page where lower(`index`) like '%".strtolower($phrase->getTitle())."%'";

$result = Database::select($sql);
while ($row = Database::next($result)) {
	$writer->startRow(array('id'=>$row['id']))->
		startCell(array('icon'=>'common/page'))->
			text($row['title'])->
		endCell()->
		startCell()->
			startIcons()->
				icon(array('icon'=>'monochrome/view'))->
				icon(array('icon'=>'monochrome/edit'))->
			endIcons()->
		endCell()->
	endRow();
}
Database::free($result);

$writer->endList();
?>