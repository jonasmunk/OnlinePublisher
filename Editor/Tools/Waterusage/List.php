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
} else if ($filter=='log') {
	listLog($windowSize,$windowPage,$text);
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
	$writer->header(array('title'=>'Adresse'));
	$writer->header(array('title'=>'Værdi'));
	$writer->header(array('title'=>'Aflæsningsdato'));
	$writer->endHeaders();

	foreach ($result->getList() as $object) {
		$address = Query::after('address')->withRelationFrom($object)->first();
		$addressString = null;
		if ($address) {
			$addressString = StringBuilder::append($address->getStreet())->separator(', ')->append($address->getZipcode())->separator(', ')->append($address->getCity())->toString();
		}
		$usage = Query::after('waterusage')->withProperty('watermeterId',$object->getId())->orderBy('date')->descending()->first();
		$writer->startRow(array( 'kind'=>'watermeter', 'id'=>$object->getId(), 'icon'=>$object->getIn2iGuiIcon(), 'title'=>$object->getTitle() ));
		$writer->startCell(array( 'icon'=>$object->getIn2iGuiIcon() ))->text( $object->getNumber() )->endCell();
		$writer->startCell()->text($addressString)->endCell();
		if ($usage) {
			$writer->startCell()->text($usage->getValue())->endCell();
			$writer->startCell()->text(DateUtils::formatDate($usage->getDate()))->endCell();
		} else {
			$writer->startCell()->text('?')->endCell();
			$writer->startCell()->text('?')->endCell();
		}
		$writer->endRow();
	}
	
	$writer->endList();
}

function listUsage($windowSize, $windowPage, $text, $year=null) {
	$query = Query::after('waterusage')->orderBy('date')->withWindowPage($windowPage)->withWindowSize($windowSize)->withText($text);
	if ($year) {
		$from = DateUtils::getFirstInstanceOfYear($year);
		$to = DateUtils::getLastInstanceOfYear($year);
		$query->withPropertyBetween('date',$from,$to);
	}
	$result = $query->search();

	$writer = new ListWriter();

	$writer->startList();
	$writer->sort($sort,$direction);
	$writer->window(array( 'total' => $result->getTotal(), 'size' => $windowSize, 'page' => $windowPage ));
	$writer->startHeaders();
	$writer->header(array('title'=>'Målernummer','width'=>30));
	$writer->header(array('title'=>'Værdi','width'=>30));
	$writer->header(array('title'=>'Aflæsningsdato'));
	$writer->header(array('title'=>'Opdateret'));
	$writer->endHeaders();

	foreach ($result->getList() as $object) {
		$meter = Watermeter::load($object->getWatermeterId());
		$writer->startRow(array( 'kind'=>'waterusage', 'id'=>$object->getId(), 'icon'=>$object->getIn2iGuiIcon(), 'title'=>$object->getTitle() ));
		if ($meter) {
			$writer->startCell(array( 'icon'=>$meter->getIn2iGuiIcon() ))->text( $meter->getNumber() )->endCell();
		} else {
			$writer->startCell(array( 'icon'=>'common/warning' ))->text( 'Ikke fundet' )->endCell();
		}
		$writer->startCell(array( 'icon'=>$object->getIn2iGuiIcon() ))->text($object->getValue())->endCell();
		$writer->startCell()->text(DateUtils::formatLongDate($object->getDate()))->endCell();
		$writer->startCell()->text(DateUtils::formatFuzzy($object->getUpdated()))->endCell();
		$writer->endRow();
	}
	$writer->endList();
}

function listLog($windowSize, $windowPage, $text) {
	$entries = LogService::getEntries(array('category'=>'waterusage'));

	$writer = new ListWriter();

	$writer->startList();
	//$writer->sort($sort,$direction);
	//$writer->window(array( 'total' => $result->getTotal(), 'size' => $windowSize, 'page' => $windowPage ));
	$writer->startHeaders();
	$writer->header(array('title'=>'Tid','width'=>30));
	$writer->header(array('title'=>'Besked'));
	$writer->endHeaders();

	foreach ($entries as $entry) {
		$writer->startRow(array( 'kind'=>'logentry', 'icon'=>'common/file' ));
		$writer->startCell()->text(DateUtils::formatDateTime($entry['time']))->endCell();
		$writer->startCell()->text($entry['message'])->endCell();
		$writer->endRow();
	}
	$writer->endList();
}
?>