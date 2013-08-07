<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Issues
 */
require_once '../../../Include/Private.php';

$text = Request::getString('text');
$type = Request::getString('type');
$kind = Request::getString('kind');
$status = Request::getString('status');

if ($type=='feedback') {
	listFeedback();
} else {
	listIssues();
}

function listFeedback() {
	global $text;
	
	$query = Query::after('feedback')->withText($text)->orderByCreated()->descending();
	
	$list = $query->get();
	
	$writer = new ListWriter();

	$writer->startList(array('checkboxes'=>true));

	$writer->startHeaders()->
		header()->
		header(array('title'=>'Status','width'=>1))->
		header(array('title'=>'Oprettet','width'=>1))->
	endHeaders();
	
	foreach($list as $item) {
		$writer->startRow(array('id'=>$item->getId(),'kind'=>'feedback'))->
			startCell()->
				startLine()->startStrong()->text($item->getTitle())->endStrong()->endLine()->
				startLine(array('top'=>3))->text($item->getNote())->endLine()->
			endCell()->
			startCell()->text('?')->endCell()->
			startCell(array('wrap'=>false,'dimmed'=>true))->text(Dates::formatFuzzy($item->getCreated()))->endCell()->
		endRow();
	}
	$writer->endList();
}

function listIssues() {
	global $text,$kind,$status;
	
	$query = Query::after('issue')->withText($text)->orderByCreated()->descending();
	if ($kind!='any') {
		$query->withProperty('kind',$kind);
	}
	if ($status!='any') {
		$query->withProperty('statusId',$status);
	}

	$states = IssueService::getStatusMap();

	$list = $query->get();

	$writer = new ListWriter();

	$writer->startList(array('checkboxes'=>true));

	$writer->startHeaders()->
		header()->
		header(array('title'=>'Status'.$type,'width'=>1))->
		header(array('title'=>'Oprettet','width'=>1))->
	endHeaders();

	foreach($list as $item) {
		$pages = PageQuery::rows()->withRelationFrom($item)->search()->getList();
		$page = null;
		if ($pages) {
			$page = $pages[0];
		}
		$writer->startRow(array('id'=>$item->getId(),'kind'=>'issue'))->
			startCell()->
			startLine()->startStrong()->text($item->getTitle())->endStrong()->endLine()->
			startLine(array('top'=>3))->text($item->getNote())->endLine();
			if ($page) {
				$writer->startLine(array('top'=>10))->object(array('icon'=>'common/page','text'=>$page['title']));
				$writer->startIcons()->
					icon(array('icon'=>'monochrome/view','action'=>true,'revealing'=>true,'data'=>array('id'=>$page['id'],'action'=>'view')))->
					icon(array('icon'=>'monochrome/edit','action'=>true,'revealing'=>true,'data'=>array('id'=>$page['id'],'action'=>'edit')))->
				endIcons();
				$writer->endLine();
			}
			$writer->startLine(array('dimmed'=>true,'mini'=>true,'top'=>3))->text(IssueService::translateKind($item->getKind()))->endLine()->
			endCell()->
			startCell()->text(@$states[$item->getStatusId()])->endCell()->
			startCell(array('wrap'=>false,'dimmed'=>true))->text(Dates::formatFuzzy($item->getCreated()))->endCell()->
		endRow();
	}
	$writer->endList();
}
?>