<?php
/**
 * @package OnlinePublisher
 * @subpackage Customers
 */
require_once '../../Include/Private.php';

$writer = new ListWriter();

$list = Query::after('issue')->withRelationToPage(InternalSession::getPageId())->get();

$result = Database::select($sql);

$writer->startList();
	foreach ($list as $issue) {
		$writer->startRow(array('id'=>$issue->getId(),'kind'=>$issue->getType()))->
			startCell()->
				startLine()->text($issue->getNote())->endLine()->
				startLine(array('minor'=>true,'dimmed'=>true))->text($issue->getKind())->endLine()->
			endCell()->
			startCell(array('width'=>1));
			$writer->startIcons()->
				icon(array('icon'=>'monochrome/edit','revealing'=>true,'action'=>true,'data'=>array('action'=>'view')))->
			endIcons();
			$writer->endCell()->
		endRow();
	}
$writer->endList();

?>