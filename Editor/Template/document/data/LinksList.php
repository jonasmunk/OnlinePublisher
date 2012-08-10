<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$pageId = InternalSession::getPageId();

$query = new LinkQuery();
$query->withSourcePage($pageId)->withTextCheck();

$links = LinkService::search($query);

$writer = new ListWriter();

$writer->startList()->
	startHeaders()->
		header(array('title'=>array('Source','da'=>'Kilde'),'width'=>30))->
		header(array('title'=>'Destination'))->
		header()->
		header(array('width'=>1))->
	endHeaders();

foreach ($links as $link) {
	$writer->startRow(array('id'=>$link->getId()))->
		startCell()->
			startLine()->text($link->getSourceText());
			$writer->startIcons();
		if ($link->hasError(LinkView::$TEXT_NOT_FOUND)) {
			$writer->icon(array('icon'=>'monochrome/warning','size'=>12,'hint'=>array('The text was not found','da'=>'Teksten findes ikke')));
		}
		$writer->icon(array('icon'=>'common/edit','revealing'=>true,'action'=>true,'data'=>array('action'=>'editLink')))->endIcons();
		

		$writer->endLine();
			
		if (StringUtils::isNotBlank($link->getSourceDescription())) {
			$writer->startLine(array('dimmed'=>true))->text($link->getSourceDescription())->endLine();			
		}
		if ($link->getSourceSubId()) {
			$writer->startLine(array('dimmed'=>true,'minor'=>true))->text(array('Only shown in the section: '.$link->getSourceSubId(),'da'=>'Vises kun i afsnittet: '.$link->getSourceSubId()))->endLine();			
		}
		$writer->endCell()->
		startCell(array('icon'=>LinkService::getTargetIcon($link)))->
			startLine()->startWrap()->text($link->getTargetTitle())->endWrap();
		if ($link->getTargetType()=='page' && !$link->hasError(LinkView::$TARGET_NOT_FOUND)) {
			$writer->startIcons();
			$writer->icon(array('icon' => 'monochrome/info','action'=>'true','data' => array('action' => 'pageInfo', 'id' => $link->getTargetId()),'revealing' => true));
			$writer->icon(array('icon' => 'monochrome/edit','action'=>'true','data' => array('action' => 'editPage', 'id' => $link->getTargetId()),'revealing' => true));
			$writer->icon(array('icon' => 'monochrome/view','action'=>'true','data' => array('action' => 'viewPage', 'id' => $link->getTargetId()),'revealing' => true));
			$writer->endIcons();
		}
		else if ($link->getTargetType()=='file' && !$link->hasError(LinkView::$TARGET_NOT_FOUND)) {
			$writer->startIcons();
			$writer->icon(array('icon' => 'monochrome/info','action'=>'true','data' => array('action' => 'fileInfo', 'id' => $link->getTargetId()),'revealing' => true));
			$writer->icon(array('icon' => 'monochrome/view','action'=>'true','data' => array('action' => 'viewFile', 'id' => $link->getTargetId()),'revealing' => true));
			$writer->endIcons();
		}
		else if ($link->getTargetType()=='url') {
			$writer->startIcons();
			$writer->icon(array('icon' => 'monochrome/arrow_right_light','action'=>'true','data' => array('action' => 'visitUrl', 'url' => $link->getTargetId()),'revealing' => true));
			$writer->endIcons();
		}
		$writer->endLine()->
			startLine(array('dimmed'=>true,'minor'=>true))->text(LinkService::translateLinkType($link->getTargetType()))->endLine()->
		endCell();
		$writer->startCell();
		foreach ($link->getErrors() as $error) {
			$writer->startLine()->icon(array('icon'=>'common/warning'))->text($error['message'])->endLine();
		}
		$writer->endCell();
		$writer->startCell()->
			startIcons()->
			icon(array('icon' => 'monochrome/delete','action'=>'true','data' => array('action' => 'deleteLink'),'revealing' => true))->
			endIcons()->
		endCell()->
	endRow();
}

$writer->endList();
?>