<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../../Include/Private.php';
require_once '../../../Classes/Integration/GoogleAnalytics.php';

$kind = Request::getString('kind');
$time = Request::getString('time');

$query = array(
	'metrics' => array('pageviews','visits'),
	'sort' => array('-pageviews'),
	'startDate' => '2007-01-01',
	'endDate' => date('Y-m-d')
);

if ($time=='week') {
	$query['startDate'] = date('Y-m-d',mktime()-(7 * 24 * 60 * 60));
} else if ($time=='month') {
	$query['startDate'] = date('Y-m-d',mktime()-(30 * 24 * 60 * 60));
} else if ($time=='year') {
	$query['startDate'] = date('Y-m-d',mktime()-(356 * 24 * 60 * 60));
}

if ($kind=='browsers') {
	$query['dimensions'] = array('browser');
} else if ($kind=='browserVersions') {
	$query['dimensions'] = array('browser','browserVersion');
} else if ($kind=='pagePath') {
	$query['dimensions'] = array('pagePath');
} else {
	$query['dimensions'] = array('pageTitle');
}

$result = GoogleAnalytics::getResult($query);

$writer = new ListWriter();

$writer->startList();
if ($result) {
	$writer->startHeaders();
	$writer->header(array('title'=>''));
	$writer->header(array('title'=>array('Pageviews','da'=>'Sidevisninger')));
	$writer->header(array('title'=>array('Visitors','da'=>'Besøgende')));
	$writer->endHeaders();

	foreach ($result as $row) {
		$writer->startRow();
		$writer->startCell(array('icon'=>'common/page'))->text(mb_convert_encoding($row, "ISO-8859-1", "UTF-8"))->endCell();
		$writer->startCell()->text($row->getPageViews())->endCell();
		$writer->startCell()->text($row->getVisits())->endCell();
		$writer->endRow();
	}
}
$writer->endList();

?>