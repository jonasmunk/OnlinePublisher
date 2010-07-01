<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendars
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/UserInterface.php';
require_once '../../../Classes/Calendarsource.php';
require_once '../../../Classes/Request.php';
require_once '../../../Classes/In2iGui.php';


	$sources = CalendarSource::search();

	$writer = new ListWriter();

	$writer->startList();
	//$writer->sort($sort,$direction);
	//$writer->window(array( 'total' => $list['total'], 'size' => $windowSize, 'page' => $windowPage ));
	$writer->startHeaders();
	$writer->header(array('title'=>'Titel','width'=>30));
	$writer->header(array('title'=>'Adresse'));
	$writer->header(array('title'=>'Filter'));
	$writer->header(array('title'=>'Synkroniseret'));
	$writer->endHeaders();

	foreach ($sources as $source) {
		$writer->startRow(array('kind'=>'calendarsource','id'=>$source->getId()));
		$writer->startCell(array('icon'=>$source->getIn2iGuiIcon()))->text($source->getTitle())->endCell();
		$writer->startCell()->text($source->getUrl())->endCell();
		$writer->startCell()->text($source->getFilter())->endCell();
		$writer->startCell()->text(UserInterface::presentFuzzyDate($source->getSynchronized()))->endCell();
		$writer->endRow();
	}
	$writer->endList();