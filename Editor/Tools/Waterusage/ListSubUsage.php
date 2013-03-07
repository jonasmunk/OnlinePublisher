<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Waterusage
 */
require_once '../../Include/Private.php';

$meterId = Request::getInt('meterId');
$windowSize = Request::getInt('windowSize',30);
$windowPage = Request::getInt('windowPage',0);

$query = Query::after('waterusage')->withWindowPage($windowPage)->withWindowSize($windowSize)->withProperty('watermeterId',$meterId)->orderBy('date');

$result = $query->search();

$writer = new ListWriter();

$writer->startList();
$writer->sort($sort,$direction);
$writer->window(array( 'total' => $result->getTotal(), 'size' => $windowSize, 'page' => $windowPage ));
$writer->startHeaders();
$writer->header(array('title'=>'Værdi'));
$writer->header(array('title'=>'Dato'));
$writer->header(array('title'=>'Opdateret'));
$writer->endHeaders();

foreach ($result->getList() as $object) {
	$writer->startRow(array( 'kind'=>'waterusage', 'id'=>$object->getId(), 'icon'=>$object->getIcon(), 'title'=>$object->getTitle() ));
	$writer->startCell()->text($object->getValue())->endCell();
	$writer->startCell()->text(DateUtils::formatLongDate($object->getDate()))->endCell();
	$writer->startCell()->text(DateUtils::formatLongDateTime($object->getUpdated()))->endCell();
	$writer->endRow();
}
$writer->endList();
?>