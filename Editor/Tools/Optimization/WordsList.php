<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';

$writer = new ListWriter();

$writer->startList();
$writer->startHeaders();
$writer->header(array('title'=>'Side','width'=>'30'));
$writer->header(array('title'=>'Indeks'));
$writer->endHeaders();

$sql = "select `index`,title from page";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$writer->startRow();
	$writer->startCell(array('icon'=>'common/page'))->text($row['title'])->endCell();
	$writer->startCell()->text($row['index'])->endCell();
	$writer->endRow();
}
Database::free($result);

$writer->endList();
?>