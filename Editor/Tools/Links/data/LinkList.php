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
$query->withTargetType($target)->withSourceType($source);

if ($state=='warnings') {
	$query->withOnlyWarnings();
}


$links = LinkService::search($query);

$writer = new ListWriter();

$writer->startList();
$writer->startHeaders();
$writer->header(array('title'=>'Kilde'));
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
		startCell(array('icon'=>$sourceIcon))->text($link->getSourceTitle())->endCell()->
		startCell()->startLine(array('dimmed'=>true))->text($link->getSourceText())->endLine()->endCell()->
		startCell(array('icon'=>$targetIcon))->startWrap()->text($link->getTargetTitle())->endWrap()->endCell();
		if ($link->getStatus()==LinkView::$NOT_FOUND) {
			$writer->startCell(array('icon'=>'common/warning','wrap'=>false))->text('Findes ikke')->endCell();
		} else if ($link->getStatus()==LinkView::$INVALID) {
			$writer->startCell(array('icon'=>'common/warning','wrap'=>false))->text('Er ikke valid')->endCell();
		} else {
			$writer->startCell()->endCell();
		}
	$writer->endRow();
}
Database::free($result);

$writer->endList();
?>