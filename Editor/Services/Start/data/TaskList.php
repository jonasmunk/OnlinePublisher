<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Include/Private.php';

$list = Query::after('issue')->get();

$writer = new ListWriter();

$writer->startList();

foreach($list as $item) {
	$writer->startRow()->
		startCell()->
			startLine()->text($item->getTitle())->endLine()->
			startLine()->text($item->getNote())->endLine()->
		startLine(array('dimmed'=>true,'mini'=>true))->text($item->getKind())->endLine()->
		endCell()->
	endRow();
}
$writer->endList();
?>