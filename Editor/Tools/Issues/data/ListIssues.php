<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Issues
 */
require_once '../../../Include/Private.php';

$list = Query::after('issue')->get();

//$list = array();

$writer = new ListWriter();

$writer->startList();

foreach($list as $item) {
	$pages = PageQuery::rows()->withRelationFrom($item)->search()->getList();
	$page = null;
	if ($pages) {
		$page = $pages[0];
	}
	$writer->startRow(array('id'=>$item->getId()))->
		startCell();
		$writer->startLine(array('mini'=>false))->text($item->getNote())->endLine();
		if ($page) {
			$writer->startLine(array('top'=>3))->object(array('icon'=>'common/page','text'=>$page['title']))->endLine();
		}
		$writer->startLine(array('dimmed'=>true,'mini'=>true))->text($item->getKind())->endLine();
		$writer->endCell()->
		startCell(array('width'=>1));
		if ($page!==null) {
			$writer->startIcons()->
				icon(array('icon'=>'monochrome/view','action'=>true,'revealing'=>true,'data'=>array('id'=>$page['id'],'action'=>'view')))->
				icon(array('icon'=>'monochrome/edit','action'=>true,'revealing'=>true,'data'=>array('id'=>$page['id'],'action'=>'edit')))->
			endIcons();
		}
		$writer->endCell()->
	endRow();
}
$writer->endList();
?>