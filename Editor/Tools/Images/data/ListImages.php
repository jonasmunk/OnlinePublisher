<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Include/Private.php';

$subset = Request::getString('subset');

if ($subset=='pages') {
	listPages();
} else if ($subset=='products') {
	listProducts();
} else if ($subset=='persons') {
	listPersons();
} else {
	listImages($text);
}

function listImages($text) {

	$subset = Request::getString('subset');
	$group = Request::getInt('group',null);
	InternalSession::setToolSessionVar('images','group',$group);
	$text = Request::getString('text');
	$windowSize = Request::getInt('windowSize',30);
	$windowPage = Request::getInt('windowPage',0);
	$sort = Request::getString('sort','title');
	$direction = Request::getString('direction','ascending');
	
	$query = Query::after('image')->withText($text)->withWindowSize($windowSize)->withWindowPage($windowPage)->withDirection($direction)->orderBy($sort);
	if ($group===-1) {
		$query->withCustom('nogroup',true);
	} else if ($group) {
		$query->withCustom('group',$group);
	}
	if ($subset=='latest') {
		$query->withCustom('createdAfter',Dates::addDays(mktime(),-1));
	} else if ($subset=='unused') {
		$query->withCustom('unused',true);
	}


	$result = $query->search();
	$list = $result->getList();
	
	$writer = new ListWriter();

	$writer->
	startList(array('unicode'=>true,'checkboxes'=>true))->
		sort($sort,$direction)->
		window(array('total'=>$result->getTotal(),'size'=>$windowSize,'page'=>$windowPage))->
		startHeaders()->
			header(array('title'=>array('Image','da'=>'Billede'),'width'=>40))->
			header(array('title'=>array('Size', 'da'=>'Størrelse')))->
			header(array('title'=>array('Height', 'da'=>'Højde')))->
			header(array('title'=>array('Width', 'da'=>'Bredde')))->
			header(array('title'=>'Type'))->
		endHeaders();


	foreach ($list as $image) {
		$writer->
		startRow(array('kind'=>'image','id'=>$image->getId(),'icon'=>'common/image','title'=>$image->getTitle()))->
			startCell(array('icon'=>'common/image'))->
				startLine()->text($image->getTitle())->endLine()->
			endCell()->
			startCell()->
				text(GuiUtils::bytesToString($image->getSize()))->
			endCell()->
			startCell()->
				text($image->getHeight())->
			endCell()->
			startCell()->
				text($image->getWidth())->
			endCell()->
			startCell()->
				text(FileService::mimeTypeToLabel($image->getMimetype()))->
			endCell()->
		endRow();	
	}
	Database::free($result);

	$writer->endList();
}

function listProducts() {

	$writer = new ListWriter();

	$writer->
	startList(array('unicode'=>true))->
		startHeaders()->
			header(array('title'=>array('Image','da'=>'Billede'),'width'=>40))->
			header(array('title'=>array('Product','da'=>'Produkt')))->
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
			header(array('title'=>array('Image','da'=>'Billede'),'width'=>40))->
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
			header(array('title'=>array('Image','da'=>'Billede'),'width'=>40))->
			header(array('title'=>array('Page','da'=>'Side')))->
			header(array('title'=>array('Section','da'=>'Afsnit')))->
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