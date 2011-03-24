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

$meterId = Request::getInt('meterId');
$windowSize = Request::getInt('windowSize',30);
$windowPage = Request::getInt('windowPage',0);

$query = Query::after('waterusage')->orderBy('title')->withWindowPage($windowPage)->withWindowSize($windowSize)->withProperty('watermeterId',$meterId)->orderBy('date');
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
	$writer->startRow(array( 'kind'=>'waterusage', 'id'=>$object->getId(), 'icon'=>$object->getIn2iGuiIcon(), 'title'=>$object->getTitle() ));
	$writer->startCell()->text($object->getValue())->endCell();
	$writer->startCell()->text(DateUtils::formatDateTime($object->getDate()))->endCell();
	$writer->startCell()->text(DateUtils::formatDateTime($object->getUpdated()))->endCell();
	$writer->endRow();
}
$writer->endList();
?>