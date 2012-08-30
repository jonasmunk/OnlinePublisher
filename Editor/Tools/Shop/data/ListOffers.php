<?php
/**
 * @package OnlinePublisher
 * @subpackage Customers
 */
require_once '../../../Include/Private.php';

$windowSize = Request::getInt('windowSize',30);
$windowNumber = Request::getInt('windowNumber',1);
$sort = Request::getString('sort');
$direction = Request::getString('direction');
if ($sort=='') $sort='product';
if ($direction=='') $direction='ascending';

$query = array('windowSize' => $windowSize,'windowNumber' => $windowNumber,'sort' => $sort,'direction' => $direction);
$list = ProductOffer::find($query);
$offers = $list['result'];

$writer = new ListWriter();

$writer->startList()->
	sort($sort,$direction)->
	window(array('total'=>$list['total'],'size'=>$list['windowSize'],'number'=>$list['windowNumber']))->
	startHeaders()->
		header(array('title'=>array('Product','da'=>'Produkt'),'key'=>'product','sortable'=>true,'width'=>25))->
		header(array('title'=>array('Offer','da'=>'Bud'),'key'=>'offer','sortable'=>true,'width'=>15))->
		header(array('title'=>array('Deadline','da'=>'Tidsfrist'),'key'=>'expiry','sortable'=>true,'width'=>15))->
		header(array('title'=>array('Person','da'=>'Person'),'key'=>'person','sortable'=>true,'width'=>20))->
		header(array('title'=>array('E-mail','da'=>'E-post'),'key'=>'email','width'=>25))->
	endHeaders();
foreach ($offers as $object) {
	$product = Product::load($object->getProductId());
	$person = Person::load($object->getPersonId());
	$mails = Query::after('emailaddress')->withProperty('containingObjectId',$person->getId())->get();
	$writer->startRow(array('id'=>$object->getId(),'kind'=>$object->getType()))->
		startCell(array('icon'=>$product->getIcon()))->text($product->getTitle())->endCell()->
		startCell()->text($object->getOffer())->endCell()->
		startCell()->text($object->getExpiry())->endCell()->
		startCell()->text($object->getTitle())->endCell()->
		startCell();
	foreach ($mails as $mail) {
		$writer->object(array('icon'=>'common/email','text'=>$mail->getAddress()));
	}
	$writer->endCell()->endRow();
}

$writer->endList();
?>