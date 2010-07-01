<?php
/**
 * @package OnlinePublisher
 * @subpackage Customers
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Productoffer.php';
require_once '../../Classes/Product.php';
require_once '../../Classes/Person.php';
require_once '../../Classes/Emailaddress.php';

$windowSize = Request::getInt('windowSize',30);
$windowNumber = Request::getInt('windowNumber',1);
$sort = Request::getString('sort');
$direction = Request::getString('direction');
if ($sort=='') $sort='product';
if ($direction=='') $direction='ascending';

$query = array('windowSize' => $windowSize,'windowNumber' => $windowNumber,'sort' => $sort,'direction' => $direction);
$list = ProductOffer::find($query);
$offers = $list['result'];

header('Content-Type: text/xml;');
echo '<?xml version="1.0"?>';
echo '<list>';
echo '<sort key="'.$sort.'" direction="'.$direction.'"/>';
echo '<window total="'.$list['total'].'" size="'.$list['windowSize'].'" number="'.$list['windowNumber'].'"/>';
echo '<headers>
	<header title="Product" key="product" sortable="true" width="25"/>
	<header title="Bud" key="offer" sortable="true" width="15"/>
	<header title="Deadline" key="expiry" sortable="true" width="15"/>
	<header title="Person" key="person" sortable="true" width="20"/>
	<header title="E-mail" width="25"/>
</headers>';
foreach ($offers as $object) {
	$product = Product::load($object->getProductId());
	$person = Person::load($object->getPersonId());
	$query = array('containingObjectId'=>$person->getId());
	$mails = EmailAddress::search($query);
	echo '<row id="'.$object->getId().'" kind="'.$object->getType().'">'.
	'<cell icon="'.$product->getIn2iGuiIcon().'">'.In2iGui::escape($product->getTitle()).'</cell>'.
	'<cell>'.In2iGui::escape($object->getOffer()).'</cell>'.
	'<cell>'.In2iGui::presentDate($object->getExpiry()).'</cell>'.
	'<cell>'.In2iGui::escape($person->getTitle()).'</cell>'.
	'<cell>';
	
	foreach ($mails as $mail) {
		echo '<object icon="common/email">'.In2iGui::escape($mail->getAddress()).'</object>';
	}
	echo '</cell>'.
	'</row>';
}

echo '</list>';
?>