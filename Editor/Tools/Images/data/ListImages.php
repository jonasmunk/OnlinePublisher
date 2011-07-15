<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Include/Private.php';

$main = Request::getString('main');
$queryString = Request::getUnicodeString('query');

if ($main=='pages') {
	listPages();
} else if ($main=='products') {
	listProducts();
} else if ($main=='persons') {
	listPersons();
}

function listProducts() {

	$writer = new ListWriter();

	$writer->
	startList(array('unicode'=>true))->
		startHeaders()->
			header(array('title'=>'Billede','width'=>40))->
			header(array('title'=>'Produkt'))->
		endHeaders();

	$result = ImageService::getProductImageRelations();

	foreach ($result as $row) {
		$writer->
		startRow(array('kind'=>'image','id'=>$row['image_id'],'icon'=>'common/image','title'=>$row['image_title']))->
			startCell(array('icon'=>'common/image'))->
				startLine()->text($row['image_title'])->endLine()->
			endCell()->
			startCell(array('icon'=>'common/product'))->
				text($row['product_title'])->
				/*startIcons()->
					icon(array(
						'icon' => 'monochrome/info_light',
						'action' => true,
						'data' => array('action' => 'editProduct','id' => $row['product_id']),
						'revealing' => true)
					)->
				endIcons()->*/
			endCell()->
		endRow();	
	}
	Database::free($result);

	$writer->endList();
}

function listPersons() {

	$writer = new ListWriter();

	$writer->
	startList(array('unicode'=>true))->
		startHeaders()->
			header(array('title'=>'Billede','width'=>40))->
			header(array('title'=>'Person'))->
		endHeaders();

	$result = ImageService::getPersonImageRelations();

	foreach ($result as $row) {
		$writer->
		startRow(array('kind'=>'image','id'=>$row['image_id'],'icon'=>'common/image','title'=>$row['image_title']))->
			startCell(array('icon'=>'common/image'))->
				startLine()->text($row['image_title'])->endLine()->
			endCell()->
			startCell(array('icon'=>'common/person'))->
				text($row['person_title'])->
				startIcons()->
					icon(array(
						'icon' => 'monochrome/info_light',
						'action' => true,
						'data' => array('action' => 'editPerson','id' => $row['person_id']),
						'revealing' => true)
					)->
				endIcons()->
			endCell()->
		endRow();	
	}
	Database::free($result);

	$writer->endList();
}

function listPages() {
	$parts = PartService::getParts();

	$writer = new ListWriter();

	$writer->
	startList(array('unicode'=>true))->
		startHeaders()->
			header(array('title'=>'Billede','width'=>40))->
			header(array('title'=>'Side'))->
			header(array('title'=>'Afsnit'))->
		endHeaders();

	$result = ImageService::getPageImageRelations();

	foreach ($result as $row) {
		$writer->
		startRow(array('kind'=>'image','id'=>$row['image_id'],'icon'=>'common/image','title'=>$row['image_title']))->
			startCell(array('icon'=>'common/image'))->
				startLine()->text($row['image_title'])->endLine()->
			endCell()->
			startCell(array('icon'=>'common/page'))->
				text($row['page_title'])->
				badge($row['template'])->
				startIcons()->
					icon(array(
						'icon' => 'monochrome/edit',
						'action' => true,
						'data' => array('action' => 'editPage','id' => $row['page_id']),
						'revealing' => true)
					)->
				endIcons()->
			endCell()->
			startCell()->text($parts[$row['part']]['name']['da'])->endCell()->
		endRow();	
	}
	Database::free($result);

	$writer->endList();
}
?>