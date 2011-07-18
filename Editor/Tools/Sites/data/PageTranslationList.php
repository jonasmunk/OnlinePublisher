<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';

$id = Request::getInt('page');

$writer = new ListWriter();

$writer->startList()->
	startHeaders()->
		header(array('title'=>'Side'))->
		header(array('title'=>'Sprog','width'=>1))->
		header(array('width'=>1))->
	endHeaders();

$list = PageService::getPageTranslationList($id);

foreach ($list as $row) {
	$writer->
	startRow(array( 'kind'=>'translation', 'id'=>$row['id'] ))->
		startCell(array('icon'=>'common/page'))->text($row['title'])->endCell()->
		startCell()->icon(array('icon'=>GuiUtils::getLanguageIcon($row['language'])))->endCell()->
		startCell()->
			startIcons()->
				icon(array('icon'=>'monochrome/delete','data'=>array('action'=>'delete')))->
			endIcons()->
		endCell()->
	endRow();
}
$writer->endList();
?>