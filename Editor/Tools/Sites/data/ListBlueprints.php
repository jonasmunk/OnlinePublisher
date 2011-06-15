<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';

$windowSize = Request::getInt('windowSize',30);
$windowPage = Request::getInt('windowPage',0);

$query = Query::after('pageblueprint')->orderBy('title')->withWindowPage($windowPage)->withWindowSize($windowSize);
$result = $query->search();

$writer = new ListWriter();

$writer->startList();
$writer->sort($sort,$direction);
$writer->window(array( 'total' => $result->getTotal(), 'size' => $windowSize, 'page' => $windowPage ));
$writer->startHeaders();
$writer->header(array('title'=>'Titel'));
$writer->header(array('title'=>'Ramme'));
$writer->header(array('title'=>'Type'));
$writer->header(array('title'=>'Design'));
$writer->endHeaders();

foreach ($result->getList() as $object) {
	$frame = Frame::load($object->getFrameId());
	$template = TemplateService::getTemplateById($object->getTemplateId());
	$design = Design::load($object->getDesignId());
	$writer->startRow(array( 'kind'=>'blueprint', 'id'=>$object->getId(), 'icon'=>$object->getIn2iGuiIcon(), 'title'=>$object->getTitle() ));
	$writer->startCell()->text($object->getTitle())->endCell();
	$writer->startCell()->text($frame ? $frame->getName() : '?')->endCell();
	$writer->startCell()->text($template ? $template->getName() : '?')->endCell();
	$writer->startCell()->text($design ? $design->getTitle() : '?')->endCell();
	$writer->endRow();
}
$writer->endList();
?>