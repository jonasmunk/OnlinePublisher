<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Include/Private.php';

$writer = new ItemsWriter();

$pages = ImageService::getNumberOfPagesWithImages();
$persons = ImageService::getNumberOfPersonsWithImages();
$products = ImageService::getNumberOfProductsWithImages();
$latest = Query::after('image')->withCustom('createdAfter',Dates::addDays(time(),-1))->search()->getTotal();

$writer->
startItems()->
	startItem(array('title'=>array('All images', 'da'=>'Alle billeder'),'badge'=>ImageService::getTotalImageCount(),'icon'=>'common/image','value'=>'all'))->endItem()->
	startItem(array('title'=>array('Latest 24 hours','da'=>'Seneste døgn'),'icon'=>'common/time','value'=>'latest','badge'=>$latest))->endItem()->
	title(array('Usage', 'da'=>'Anvendelse'))->
	item(array(
		'title' => array('Unused','da'=>'Ikke anvendt'),
		'badge' => ImageService::getUnusedImagesCount(),
		'icon' => 'monochrome/round_question',
		'value' => 'unused')
	);
if ($pages > 0) {
	$writer->item(array(
		'title' => array('Pages', 'da'=>'Sider'),
		'badge' => $pages,
		'icon' => 'monochrome/file',
		'value' => 'pages')
	);
}
if ($persons > 0) {
	$writer->item(array(
		'title' => array('Persons','da'=>'Personer'),
		'badge' => $persons,
		'icon' => 'monochrome/person',
		'value' => 'persons')
	);	
}
if ($products > 0) {
	$writer->item(array(
		'title' => array('Products','da'=>'Produkter'),
		'badge' => $products,
		'icon' => 'monochrome/package',
		'value' => 'products')
	);
}
$writer->endItems();
?>