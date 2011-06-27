<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/In2iGui.php';
require_once '../../../Classes/Network/FeedParser.php';
require_once '../../../Classes/Modules/Warnings/WarningService.php';

$warnings = WarningService::getWarnings();

$writer = new ListWriter();

$writer->startList(array('unicode'=>true));

foreach($warnings as $warning) {
	$entity = $warning->getEntity();
	$writer->startRow()->
		startCell(array('icon'=>'monochrome/warning'))->startLine()->text($warning->getText())->endLine()->
		endCell()->
		startCell(array('icon'=>$entity['icon']))->text($entity['title'])->endCell()->
	endRow();
		
}
$writer->endList();
?>