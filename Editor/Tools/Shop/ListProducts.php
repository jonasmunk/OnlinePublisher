<?php
/**
 * @package OnlinePublisher
 * @subpackage Customers
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Object.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Product.php';
require_once '../../Classes/Producttype.php';

$producttype = Request::getInt('producttype');
$productgroup = Request::getInt('productgroup');
$windowSize = Request::getInt('windowSize',30);
$windowNumber = Request::getInt('windowNumber',1);

$query = array('windowSize' => $windowSize,'windowNumber' => $windowNumber);
if ($producttype>0) {
	$query['producttype'] = $producttype;
}
if ($productgroup>0) {
	$query['productgroup'] = $productgroup;
}

$list = Product::find($query);
$products = $list['result'];

header('Content-Type: text/xml;');
echo '<?xml version="1.0"?>';
echo '<list>';
echo '<window total="'.$list['total'].'" size="'.$list['windowSize'].'" number="'.$list['windowNumber'].'"/>';
echo '<headers><header title="Produkt" width="40"/><header title="Nummer" width="30"/><header title="Type" width="30"/></headers>';
foreach ($products as $product) {
	$type = ProductType::load($product->getProductTypeId());
	echo '<row id="'.$product->getId().'" kind="'.$product->getType().'" icon="common/product" title="'.In2iGui::escape($product->getTitle()).'">'.
	'<cell icon="common/product">'.In2iGui::escape($product->getTitle()).'</cell>'.
	'<cell>'.In2iGui::escape($product->getNumber()).'</cell>'.
	'<cell>'.In2iGui::escape($type->getTitle()).'</cell>'.
	'</row>';
}
error_log('x');
echo '</list>';
?>