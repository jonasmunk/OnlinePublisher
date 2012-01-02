<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Include/Private.php';

$list = Query::after('issue')->get();

$writer = new ListWriter();

$writer->startList();

foreach($list as $item) {
	$pages = PageQuery::rows()->withRelationFrom($item)->search()->getList();
	$page = null;
	if ($pages) {
		$page = $pages[0];
	}
	$writer->startRow()->
		startCell()->
			startLine()->startStrong()->text($item->getTitle())->endStrong()->endLine()->
			startLine(array('mini'=>true))->text($item->getNote())->endLine()->
			startLine(array('dimmed'=>true,'mini'=>true))->text($item->getKind())->endLine();
		if ($page) {
			$writer->startLine()->text($page['title'])->endLine();
		}
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