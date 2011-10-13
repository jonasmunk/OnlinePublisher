<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Links
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Interface/In2iGui.php';
require_once '../../Classes/Core/Request.php';
require_once 'LinksController.php';

$source = Request::getString('source');
$target = Request::getString('target');

$query = array('source'=>$source,'target'=>$target);

$writer = new ListWriter();

$writer->startList();
$writer->startHeaders();
$writer->header(array('title'=>'Kilde'));
$writer->header();
$writer->header(array('title'=>'Mål'));
$writer->header(array('title'=>'Status'));
$writer->endHeaders();

$sql = LinksController::buildSQL($query);
error_log($sql);
$result = Database::select($sql);
while ($row = Database::next($result)) {
    $analyzed = LinksController::analyzeLink($row);
	$writer->startRow();
	$writer->startCell(array('icon'=>$analyzed['sourceIcon']))->text($analyzed['sourceTitle'])->endCell();
	$writer->startCell()->startLine(array('dimmed'=>true))->text($analyzed['sourceData'])->endLine()->endCell();
	$writer->startCell(array('icon'=>$analyzed['targetIcon']))->text($analyzed['targetTitle'])->endCell();
	$writer->startCell()->text($analyzed['message'])->endCell();
	$writer->endRow();
}
Database::free($result);

$writer->endList();
?>