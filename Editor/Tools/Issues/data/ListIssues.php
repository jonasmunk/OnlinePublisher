<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Issues
 */
require_once '../../../Include/Private.php';

$text = Request::getString('text');
$filter = Request::getString('filter');

$query = Query::after('issue')->withText($text);
if ($filter!='all') {
	$query->withProperty('kind',$filter);
}

$list = $query->get();

$writer = new ListWriter();

$writer->startList(array('checkboxes'=>true));

$writer->startHeaders()->
	header()->
	header()->
endHeaders();

foreach($list as $item) {
	$pages = PageQuery::rows()->withRelationFrom($item)->search()->getList();
	$page = null;
	if ($pages) {
		$page = $pages[0];
	}
	$writer->startRow(array('id'=>$item->getId()))->
		startCell();
	$writer->startLine()->startStrong()->text($item->getTitle())->endStrong()->endLine();
		$writer->startLine(array('top'=>3))->text($item->getNote())->endLine();
		if ($page) {
			$writer->startLine(array('top'=>10))->object(array('icon'=>'common/page','text'=>$page['title']));
			$writer->startIcons()->
				icon(array('icon'=>'monochrome/view','action'=>true,'revealing'=>true,'data'=>array('id'=>$page['id'],'action'=>'view')))->
				icon(array('icon'=>'monochrome/edit','action'=>true,'revealing'=>true,'data'=>array('id'=>$page['id'],'action'=>'edit')))->
			endIcons();
			$writer->endLine();
		}
		$writer->startLine(array('dimmed'=>true,'mini'=>true,'top'=>3))->text(IssueService::translateKind($item->getKind()))->endLine();
		$writer->endCell()->
		startCell(array('wrap'=>false,'dimmed'=>true))->text(DateUtils::formatFuzzy($item->getCreated()))->endCell()->
	endRow();
}
$writer->endList();
?>