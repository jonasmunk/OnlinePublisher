<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Waterusage
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Objects/Waterusage.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Utilities/DateUtils.php';
require_once '../../Classes/Utilities/GuiUtils.php';

$filter = Request::getString('filter');
$text = Request::getUnicodeString('query');
$windowSize = Request::getInt('windowSize',30);
$windowPage = Request::getInt('windowPage',0);

if ($filter=='meters') {
	listMeters($windowSize,$windowPage,$text);
} else {
	listUsage($windowSize,$windowPage,$text,intval($filter));
}

function listMeters($windowSize, $windowPage, $text) {
	$query = Query::after('watermeter')->orderBy('number')->withWindowPage($windowPage)->withWindowSize($windowSize)->withText($text);
	$result = $query->search();

	$writer = new ListWriter();

	$writer->startList();
	$writer->sort($sort,$direction);
	$writer->window(array( 'total' => $result->getTotal(), 'size' => $windowSize, 'page' => $windowPage ));
	$writer->startHeaders();
	$writer->header(array('title'=>'Nummer','width'=>40));
	$writer->header(array('title'=>'Address'));
	$writer->header(array('title'=>'Værdi'));
	$writer->header(array('title'=>'Aflæsningsdato'));
	$writer->endHeaders();

	foreach ($result->getList() as $object) {
		$writer->startRow(array( 'kind'=>'watermeter', 'id'=>$object->getId(), 'icon'=>$object->getIn2iGuiIcon(), 'title'=>$object->getTitle() ));
		$writer->startCell(array( 'icon'=>$object->getIn2iGuiIcon() ))->text( $object->getNumber() )->endCell();
		$writer->startCell()->text('?')->endCell();
		$writer->startCell()->text('?')->endCell();
		$writer->startCell()->text('?')->endCell();
		$writer->endRow();
	}
	
	$writer->endList();
}

function listUsage($windowSize, $windowPage, $text, $year=null) {
	$query = Query::after('waterusage')->orderBy('title')->withWindowPage($windowPage)->withWindowSize($windowSize)->withText($text);
	if ($filter)
	if ($year) {
		$query->withProperty('year',$year);
	}
	$result = $query->search();

	$writer = new ListWriter();

	$writer->startList();
	$writer->sort($sort,$direction);
	$writer->window(array( 'total' => $result->getTotal(), 'size' => $windowSize, 'page' => $windowPage ));
	$writer->startHeaders();
	$writer->header(array('title'=>'Nummer','width'=>40));
	$writer->header(array('title'=>'År'));
	$writer->header(array('title'=>'Værdi'));
	$writer->header(array('title'=>'Aflæsningsdato'));
	$writer->header(array('title'=>'Opdateret'));
	$writer->endHeaders();

	foreach ($result->getList() as $object) {
		$writer->startRow(array( 'kind'=>'waterusage', 'id'=>$object->getId(), 'icon'=>$object->getIn2iGuiIcon(), 'title'=>$object->getTitle() ));
		$writer->startCell(array( 'icon'=>$object->getIn2iGuiIcon() ))->text( $object->getNumber() )->endCell();
		$writer->startCell()->text($object->getYear())->endCell();
		$writer->startCell()->text($object->getValue())->endCell();
		$writer->startCell()->text(DateUtils::formatDateTime($object->getDate()))->endCell();
		$writer->startCell()->text(DateUtils::formatDateTime($object->getUpdated()))->endCell();
		$writer->endRow();
	}
	$writer->endList();
}
?>