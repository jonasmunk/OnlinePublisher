<?
require_once '../../Config/Setup.php';
require_once '../../Editor/Include/Public.php';
require_once('../../Editor/Classes/Product.php');
require_once('../../Editor/Classes/Person.php');
require_once('../../Editor/Classes/Productoffer.php');
require_once('../../Editor/Classes/Objects/Emailaddress.php');
require_once('../../Editor/Classes/Request.php');
require_once('../../Editor/Classes/Response.php');
require_once('../../Editor/Classes/Utilities/ValidateUtils.php');

$name = Request::getUnicodeString('name');
$email = Request::getUnicodeString('email');
$pageId = Request::getInt('pageId');
$productId = Request::getInt('productId');
$bid = Request::getUnicodeString('offer');

if ($name=='') {
	Response::redirect('../../?id='.$pageId.'&makeOffer='.$productId.'&productlistingError=noName&productlistingEmail='.$email.'&productlistingOffer='.$bid.'&productlistingName='.$name);
} else if ($email=='') {
	Response::redirect('../../?id='.$pageId.'&makeOffer='.$productId.'&productlistingError=noEmail&productlistingEmail='.$email.'&productlistingOffer='.$bid.'&productlistingName='.$name);
} else if (!ValidateUtils::validateEmail($email)) {
	Response::redirect('../../?id='.$pageId.'&makeOffer='.$productId.'&productlistingError=invalidEmail&productlistingEmail='.$email.'&productlistingOffer='.$bid.'&productlistingName='.$name);
} else if ($bid=='') {
	Response::redirect('../../?id='.$pageId.'&makeOffer='.$productId.'&productlistingError=noOffer&productlistingEmail='.$email.'&productlistingOffer='.$bid.'&productlistingName='.$name);
} else {

	$product = Product::load($productId);
	if ($product) {
		if ($product->isAllowOffer()) {
			$person = Person::loadByEmail($email);
			if ($person==null) {
				$person = new Person();
				$person->setFullName($name);
				$person->save();
				$person->publish();
				
				$emailAddress = new EmailAddress();
				$emailAddress->setAddress($email);
				$emailAddress->setContainingObjectId($person->getId());
				$emailAddress->save();
				$emailAddress->publish();
			}
			$offer = new ProductOffer();
			$offer->setProductId($product->getId());
			$offer->setOffer($bid);
			$offer->setPersonId($person->getId());
			$offer->save();
			$offer->publish();
			Response::redirect('../../?id='.$pageId.'&makeOffer='.$productId.'&productlistingSuccess=true');
		} else {
			echo 'Not allowed!';
		}
	} else {
		echo 'Does no exist';
	}
}
?>