<?php
/**
 * @package OnlinePublisher
 * @subpackage Sites
 */
require_once '../../../Include/Private.php';


$windowSize = Request::getInt('windowSize',30);
$windowPage = Request::getInt('windowPage',0);
$text = Request::getString('text');
$sort = Request::getString('sort');
$direction = Request::getString('direction');

if ($direction=='') $direction='ascending';

$result = PageQuery::rows()->withText($text)->orderBy($sort)->withDirection($direction)->withWindowSize($windowSize)->withWindowPage($windowPage)->search();

$writer = new ListWriter();

$writer->startList();
$writer->sort($sort,$direction);
$writer->window(array( 'total' => $result->getTotal(), 'size' => $windowSize, 'page' => $windowPage ));
$writer->startHeaders();
$writer->header(array('title'=>'Titel','width'=>40,'key'=>'page.title','sortable'=>'true'));
$writer->header(array('title'=>'Skabelon','key'=>'template.unique','sortable'=>'true'));
$writer->header(array('title'=>'Sprog','key'=>'page.language','sortable'=>'true','width'=>1));
$writer->endHeaders();

$templates = TemplateService::getTemplatesKeyed();

foreach ($result->getList() as $row) {
	$modified = $row['publishdelta']>0;
	$writer->startRow(array('id'=>$row['id'],'title'=>$row['title'],'kind'=>'page','icon'=>'common/page'))->
		startCell(array('icon'=>'common/page'))->text($row['title'])->endCell()->
		startCell()->text($templates[$row['unique']]['name'])->endCell()->
		startCell()->icon(array('icon'=>GuiUtils::getLanguageIcon($row['language'])))->endCell()->
	endRow();
}
$writer->endList();
?>