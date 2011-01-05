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
require_once '../../Classes/Emailaddress.php';
require_once '../../Classes/Person.php';
require_once '../../Classes/Phonenumber.php';

$kind = Request::getString('kind');
$value = Request::getInt('value');
$windowSize = Request::getInt('windowSize',30);
$windowPage = Request::getInt('windowPage',0);
$queryString = Request::getUnicodeString('query');
$sort = Request::getString('sort');
$direction = Request::getString('direction');
if ($sort=='') $sort='title';
if ($direction=='') $direction='ascending';

$query = array('windowSize' => $windowSize,'windowPage' => $windowPage,'sort' => $sort,'direction' => $direction, 'query' => $query);
if ($kind=='mailinglist') {
	$query['mailinglist'] = $value;
}
if ($kind=='persongroup') {
	$query['persongroup'] = $value;
}
if ($queryString!='') $query['query'] = $queryString;

$list = Person::find($query);
$persons = $list['result'];

header('Content-Type: text/xml;');
echo '<?xml version="1.0"?>
<list>
<sort key="'.$sort.'" direction="'.$direction.'"/>
<window total="'.$list['total'].'" size="'.$list['windowSize'].'" page="'.$list['windowPage'].'"/>
<headers>
	<header title="Navn" width="30" key="title" sortable="true"/>
	<header title="E-post" width="20" sortable="true"/>
	<header title="Telefon" width="20"/>
	<header title="Adresse" width="30"/>
</headers>';
foreach ($persons as $object) {
	echo '<row id="'.$object->getId().'" kind="'.$object->getType().'" icon="common/person" title="'.In2iGui::escape($object->getTitle()).'">'.
	'<cell icon="common/person">'.In2iGui::escape($object->getTitle()).'</cell>'.
	'<cell>'.buildEmails($object).'</cell>'.
	'<cell>'.buildPhones($object).'</cell>'.
	'<cell>'.buildAddress($object).'</cell>'.
	'</row>';
}

echo '</list>';

function buildAddress($person) {
	$addr = In2iGui::escape($person->getStreetname());
	$zipcode = In2iGui::escape($person->getZipcode());
	$city = In2iGui::escape($person->getCity());
	$country = In2iGui::escape($person->getCountry());
	if ($zipcode!='' || $city!='') {
		if ($addr!='') $addr.='<break/>';
		$addr.=$zipcode.' '.$city;
	}
	if ($country!='') {
		if ($addr!='') $addr.='<break/>';
		$addr.=$country;
	}
	return $addr;
}

function buildEmails($person) {
	$out ='';
	$mails = EmailAddress::search(array('containingObjectId'=>$person->getId()));
	foreach ($mails as $mail) {
		$out.= '<object icon="common/email">'.In2iGui::escape($mail->getAddress()).'</object>';
	}
	return $out;
}

function buildPhones($person) {
	$out = '';
	$phones = PhoneNumber::search(array('containingObjectId'=>$person->getId()));
	foreach ($phones as $phone) {
		$out.= '<object icon="common/phone">'.In2iGui::escape($phone->getNumber()).'</object>';
	}
	return $out;
}
?>