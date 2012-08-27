<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Publish
 */
require_once '../../Include/Private.php';

$pages = PublishingService::getUnpublishedPages();
$hierarchies = PublishingService::getUnpublishedHierarchies();
$objects = PublishingService::getUnpublishedObjects();

$writer = new ListWriter();

$writer->startList();
$writer->startHeaders();
$writer->header(array('title'=>array('Title','da'=>'Titel'),'width'=>70));
$writer->header(array('title'=>'Type','width'=>30));
$writer->header();
$writer->endHeaders();

foreach ($pages as $page) {
	$writer->startRow(array('kind'=>'page','id'=>$page['id']))->
		startCell(array('icon'=>'common/page'))->
			text($page['title'])->
			startIcons()->
				icon(array('icon'=>'monochrome/view','revealing'=>true,'action'=>true,'data'=>array('action'=>'viewPage','id'=>$page['id'])))->
				icon(array('icon'=>'monochrome/edit','revealing'=>true,'action'=>true,'data'=>array('action'=>'editPage','id'=>$page['id'])))->
			endIcons()->
		endCell()->
		startCell()->text(array('Page','da'=>'Side'))->endCell()->
		startCell(array('wrap'=>false))->button(array('text'=>array('Publish','da'=>'Udgiv')))->endCell()->
	endRow();
}

foreach ($hierarchies as $hierarchy) {
	$writer->startRow(array('kind'=>'hierarchy','id'=>$hierarchy['id']));
	$writer->startCell(array('icon'=>'common/hierarchy'))->text($hierarchy['name'])->endCell();
	$writer->startCell()->text(array('Hierarchy','da'=>'Hierarki'))->endCell();
	$writer->startCell(array('wrap'=>false))->button(array('text'=>array('Publish','da'=>'Udgiv')))->endCell();
	$writer->endRow();	
}

foreach ($objects as $object) {
	$writer->startRow(array('kind'=>'object','id'=>$object->getId()));
	$writer->startCell(array('icon'=>$object->getIn2iGuiIcon()))->text($object->getTitle())->endCell();
	$writer->startCell()->text($object->getType())->endCell();
	$writer->startCell(array('wrap'=>false))->button(array('text'=>array('Publish','da'=>'Udgiv')))->endCell();
	$writer->endRow();
}

$writer->endList();
?>