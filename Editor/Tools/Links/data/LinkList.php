<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Links
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/Interface/In2iGui.php';
require_once '../../../Classes/Core/Request.php';

$source = Request::getString('source');
$target = Request::getString('target');
$state = Request::getString('state');

if ($target=='all') {
	$target=null;
}
if ($source=='all') {
	$source=null;
}

$query = new LinkQuery();
$query->withTargetType($target)->withSourceType($source)->withTextCheck();

if ($state=='warnings') {
	$query->withOnlyWarnings();
}


$links = LinkService::search($query);

$writer = new ListWriter();

$writer->startList();
$writer->startHeaders();
$writer->header(array('title'=>'Kilde'));
$writer->header(array('width'=>1));
$writer->header();
$writer->header(array('title'=>'Mål'));
$writer->header(array('title'=>'Status'));
$writer->endHeaders();

$icons = array(
	'hierarchy' => 'monochrome/hierarchy',
	'file' => 'monochrome/file',
	'url' => 'monochrome/globe',
	'email' => 'monochrome/email',
	'page' => 'common/page',
	'news' => 'common/news'
);

foreach ($links as $link) {
	$sourceIcon = $icons[$link->getSourceType()];
	$targetIcon = $icons[$link->getTargetType()];
	$writer->startRow()->
		startCell(array('icon'=>$sourceIcon))->text($link->getSourceTitle());
		if ($link->getSourceSubId()) {
			$writer->badge(array('text' => '#'.$link->getSourceSubId()));
		}
		if ($link->getSourceType()=='news') {
			$writer->startIcons()->
				icon(array('icon'=>'monochrome/info','action'=>true,'revealing'=>true,'data'=>array('action'=>'newsInfo','id'=>$link->getSourceId())))->
			endIcons();			
		}
		if ($link->getSourceType()=='page') {
			$writer->startIcons()->
				icon(array('icon'=>'monochrome/view','action'=>true,'revealing'=>true,'data'=>array('action'=>'viewPage','id'=>$link->getSourceId())))->
				icon(array('icon'=>'monochrome/edit','action'=>true,'revealing'=>true,'data'=>array('action'=>'editPage','id'=>$link->getSourceId())))->
			endIcons();
		}
		$writer->endCell();
		$writer->startCell();
		$writer->endCell()->
		startCell()->startLine(array('dimmed'=>true))->text($link->getSourceText());
		if ($link->hasError(LinkView::$TEXT_NOT_FOUND)) {
			$writer->startIcons()->icon(array('icon'=>'common/warning','hint'=>'Teksten findes ikke'))->endIcons();
		}
		$writer->endLine()->endCell()->
		startCell(array('icon'=>$targetIcon))->startWrap()->text($link->getTargetTitle())->endWrap();
		if ($link->getTargetType()=='page' && !$link->hasError(LinkView::$TARGET_NOT_FOUND)) {
			$writer->startIcons()->
				icon(array('icon'=>'monochrome/view','action'=>true,'revealing'=>true,'data'=>array('action'=>'viewPage','id'=>$link->getTargetId())))->
				icon(array('icon'=>'monochrome/edit','action'=>true,'revealing'=>true,'data'=>array('action'=>'editPage','id'=>$link->getTargetId())))->
			endIcons();
		}
		if ($link->getTargetType()=='file' && !$link->hasError(LinkView::$TARGET_NOT_FOUND)) {
			$writer->startIcons()->
				icon(array('icon'=>'monochrome/info','action'=>true,'revealing'=>true,'data'=>array('action'=>'fileInfo','id'=>$link->getTargetId())))->
			endIcons();
		}
		if ($link->getTargetType()=='url') {
			$writer->startIcons()->
				icon(array('icon'=>'monochrome/view','action'=>true,'revealing'=>true,'data'=>array('action'=>'viewUrl','url'=>$link->getTargetId())))->
			endIcons();
		}

		$writer->endCell();
		$writer->startCell();
		foreach ($link->getErrors() as $error) {
			$writer->startLine()->icon(array('icon'=>'common/warning'))->text($error['message'])->endLine();
		}
		$writer->endCell();
	$writer->endRow();
}
Database::free($result);

$writer->endList();
?>