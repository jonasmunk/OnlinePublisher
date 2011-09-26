<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Model
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Interface/In2iGui.php';
require_once '../../Classes/Model/Object.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Objects/Emailaddress.php';
require_once '../../Classes/Objects/Person.php';
require_once '../../Classes/Core/Query.php';
require_once '../../Classes/Objects/Phonenumber.php';

$windowSize = Request::getInt('windowSize',30);
$windowPage = Request::getInt('windowPage',0);
$queryString = Request::getUnicodeString('query');
$sort = Request::getString('sort');
$direction = Request::getString('direction');
if ($sort=='') $sort='title';
if ($direction=='') $direction='ascending';

$result = Query::after('person')->orderBy('title')->withWindowSize($windowSize)->withWindowPage($windowPage)->withDirection($direction)->withText($queryString)->search();
$persons = $result->getList();

$writer = new ListWriter();

$writer->startList(array('unicode'=>true))->
	sort($sort,$direction)->
	window(array('total'=>$result->getTotal(),'size'=>$windowSize,'page'=>$windowPage))->
	startHeaders()->
		header(array('title'=>'Navn','width'=>60,'key'=>'title','sortable'=>true))->
		header(array('title'=>'Adresse','width'=>40))->
	endHeaders();

foreach ($persons as $object) {
	$writer->
		startRow(array('id'=>$object->getId(),'kind'=>$object->getType(),'icon'=>"common/person",'title'=>In2iGui::escape($object->getTitle())))->
			startCell(array('icon'=>'common/person'))->startWrap()->text($object->getTitle())->endWrap()->endCell()->
			startCell();
			buildAddress($object,$writer);
			buildEmails($object,$writer);
			buildPhones($object,$writer);
			$writer->endCell()->
		endRow();
}	
$writer->endList();

function buildAddress($person,$writer) {
	$addr = $person->getStreetname();
	$zipcode = $person->getZipcode();
	$city = $person->getCity();
	$country = $person->getCountry();
	if ($zipcode!='' || $city!='') {
		if ($addr!='') $addr.="\n";
		$addr.=$zipcode.' '.$city;
	}
	if ($country!='') {
		if ($addr!='') $addr.="\n";
		$addr.=$country;
	}
	$writer->text($addr);
}

function buildEmails($person,$writer) {
	$mails = Query::after('emailaddress')->withProperty('containingObjectId',$person->getId())->get();
	foreach ($mails as $mail) {
		$writer->object(array('icon'=>"common/email",'text'=>In2iGui::escape($mail->getAddress())));
	}
}

function buildPhones($person,$writer) {
	$phones = Query::after('phonenumber')->withProperty('containingObjectId',$person->getId())->get();
	foreach ($phones as $phone) {
		$writer->object(array('icon'=>"common/phone",'text'=>In2iGui::escape($phone->getNumber())));
	}
}
?>