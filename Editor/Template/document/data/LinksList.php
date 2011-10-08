<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$links = LinkService::getPageLinks(InternalSession::getPageId());

$writer = new ListWriter();

$writer->startList()->
	startHeaders()->
		header(array('title'=>'Kilde','width'=>30))->
		header(array('title'=>'Destination'))->
		header(array('width'=>1))->
	endHeaders();

foreach ($links as $link) {
	$writer->startRow(array('id'=>$link->getId()))->
		startCell()->
			startLine()->text($link->getSourceText())->endLine();
		if (StringUtils::isNotBlank($link->getAlternative())) {
			$writer->startLine(array('dimmed'=>true))->text($link->getAlternative())->endLine();			
		}
		if ($link->getPartId()) {
			$writer->startLine(array('dimmed'=>true,'minor'=>true))->text('Vises kun i afsnittet: '.$link->getPartId())->endLine();			
		}
		$writer->endCell()->
		startCell(array('icon'=>$link->getTargetIcon()))->
			startLine()->startWrap()->text($link->getTargetTitle())->endWrap();
		if ($link->getTargetType()=='page') {
			$writer->startIcons();
			$writer->icon(array('icon' => 'monochrome/info','action'=>'true','data' => array('action' => 'pageInfo', 'id' => $link->getTargetId()),'revealing' => true));
			$writer->icon(array('icon' => 'monochrome/edit','action'=>'true','data' => array('action' => 'editPage', 'id' => $link->getTargetId()),'revealing' => true));
			$writer->icon(array('icon' => 'monochrome/view','action'=>'true','data' => array('action' => 'viewPage', 'id' => $link->getTargetId()),'revealing' => true));
			$writer->endIcons();
		}
		else if ($link->getTargetType()=='file') {
			$writer->startIcons();
			$writer->icon(array('icon' => 'monochrome/info','action'=>'true','data' => array('action' => 'fileInfo', 'id' => $link->getTargetId()),'revealing' => true));
			$writer->icon(array('icon' => 'monochrome/view','action'=>'true','data' => array('action' => 'viewFile', 'id' => $link->getTargetId()),'revealing' => true));
			$writer->endIcons();
		}
		else if ($link->getTargetType()=='url') {
			$writer->startIcons();
			$writer->icon(array('icon' => 'monochrome/arrow_right_light','action'=>'true','data' => array('action' => 'visitUrl', 'url' => $link->getTargetValue()),'revealing' => true));
			$writer->endIcons();
		}
		$writer->endLine()->
			startLine(array('dimmed'=>true,'minor'=>true))->text(LinkService::translateLinkType($link->getTargetType()))->endLine()->
		endCell()->
		startCell()->
			startIcons()->
			icon(array('icon' => 'monochrome/delete','action'=>'true','data' => array('action' => 'deleteLink'),'revealing' => true))->
			endIcons()->
		endCell()->
	endRow();
}

$writer->endList();
?>