<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Request.php';

$writer = new ItemsWriter();

$writer->
startItems()->
	startItem(array('title'=>'Alle billeder','badge'=>ImageService::getTotalImageCount(),'icon'=>'common/image','value'=>'all'))->endItem()->
	startItem(array('title'=>'Seneste døgn','icon'=>'common/time','value'=>'latest'))->endItem()->
	title('Anvendelse')->
	item(array(
		'title'=>'Ikke anvendt',
		'badge'=>ImageService::getUnusedImagesCount(),
		'icon'=>'monochrome/round_question',
		'value'=>'unused')
	)->
	item(array(
		'title'=>'Sider',
		'badge'=>ImageService::getNumberOfPagesWithImages(),
		'icon'=>'common/page',
		'value'=>'pages')
	)->
	item(array(
		'title'=>'Personer',
		'badge'=>ImageService::getNumberOfPersonsWithImages(),
		'icon'=>'common/person',
		'value'=>'persons')
	)->
	item(array(
		'title'=>'Produkter',
		'badge'=>ImageService::getNumberOfProductsWithImages(),
		'icon'=>'common/product',
		'value'=>'products')
	)->
endItems();
?>