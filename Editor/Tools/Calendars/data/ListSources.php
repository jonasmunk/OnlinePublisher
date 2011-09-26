<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendars
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/Utilities/DateUtils.php';
require_once '../../../Classes/Objects/Calendarsource.php';
require_once '../../../Classes/Core/Request.php';
require_once '../../../Classes/Interface/In2iGui.php';


$sources = Query::after('calendarsource')->orderBy('title')->get();

$writer = new ListWriter();

$writer->startList();
//$writer->sort($sort,$direction);
//$writer->window(array( 'total' => $list['total'], 'size' => $windowSize, 'page' => $windowPage ));
$writer->startHeaders();
$writer->header(array('title'=>'Titel','width'=>30));
$writer->header(array('title'=>'Adresse'));
$writer->header(array('title'=>'Filter'));
$writer->header(array('title'=>'Interval'));
$writer->header(array('title'=>'Synkroniseret'));
$writer->endHeaders();

foreach ($sources as $source) {
	$writer->startRow(array('kind'=>'calendarsource','id'=>$source->getId()));
	$writer->startCell(array('icon'=>$source->getIn2iGuiIcon()))->
		startLine()->text($source->getTitle())->endLine()->
		startLine(array('dimmed'=>true))->text($source->getDisplayTitle())->endLine()->
	endCell();
	$writer->startCell()->startLine(array('dimmed'=>true))->startWrap()->text($source->getUrl())->endWrap()->endLine()->endCell();
	$writer->startCell()->text($source->getFilter())->endCell();
	$writer->startCell()->text(DateUtils::formatDuration($source->getSyncInterval()))->endCell();
	$writer->startCell(array('wrap'=>false))->text(DateUtils::formatFuzzy($source->getSynchronized()))->endCell();
	$writer->endRow();
}
$writer->endList();