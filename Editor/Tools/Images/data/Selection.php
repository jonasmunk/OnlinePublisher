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
$latest = Query::after('image')->withCustom('createdAfter',DateUtils::addDays(mktime(),-1))->search()->getTotal();

$writer->
startItems()->
	startItem(array('title'=>'Alle billeder','badge'=>ImageService::getTotalImageCount(),'icon'=>'common/image','value'=>'all'))->endItem()->
	startItem(array('title'=>'Seneste døgn','icon'=>'common/time','value'=>'latest','badge'=>$latest))->endItem()->
	title('Anvendelse')->
	item(array(
		'title' => 'Ikke anvendt',
		'badge' => ImageService::getUnusedImagesCount(),
		'icon' => 'monochrome/round_question',
		'value' => 'unused')
	);
if ($pages > 0) {
	$writer->item(array(
		'title' => 'Sider',
		'badge' => $pages,
		'icon' => 'monochrome/file',
		'value' => 'pages')
	);
}
if ($persons > 0) {
	$writer->item(array(
		'title' => 'Personer',
		'badge' => $persons,
		'icon' => 'monochrome/person',
		'value' => 'persons')
	);	
}
if ($products > 0) {
	$writer->item(array(
		'title' => 'Produkter',
		'badge' => $products,
		'icon' => 'monochrome/package',
		'value' => 'products')
	);
}
$writer->endItems();
?>