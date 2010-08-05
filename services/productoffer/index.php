<?
require_once '../../Config/Setup.php';
require_once '../../Editor/Include/Public.php';
require_once '../../Editor/Include/Functions.php';
require_once('../../Editor/Classes/Product.php');
require_once('../../Editor/Classes/Person.php');
require_once('../../Editor/Classes/Productoffer.php');
require_once('../../Editor/Classes/Emailaddress.php');
require_once('../../Editor/Classes/Request.php');
require_once('../../Editor/Classes/ValidateUtil.php');

$name = Request::getUnicodeString('name');
$email = Request::getUnicodeString('email');
$pageId = Request::getInt('pageId');
$productId = Request::getInt('productId');
$bid = Request::getUnicodeString('offer');

if ($name=='') {
	redirect('../../?id='.$pageId.'&makeOffer='.$productId.'&productlistingError=noName&productlistingEmail='.$email.'&productlistingOffer='.$bid.'&productlistingName='.$name);
} else if ($email=='') {
	redirect('../../?id='.$pageId.'&makeOffer='.$productId.'&productlistingError=noEmail&productlistingEmail='.$email.'&productlistingOffer='.$bid.'&productlistingName='.$name);
} else if (!ValidateUtil::validateEmail($email)) {
	redirect('../../?id='.$pageId.'&makeOffer='.$productId.'&productlistingError=invalidEmail&productlistingEmail='.$email.'&productlistingOffer='.$bid.'&productlistingName='.$name);
} else if ($bid=='') {
	redirect('../../?id='.$pageId.'&makeOffer='.$productId.'&productlistingError=noOffer&productlistingEmail='.$email.'&productlistingOffer='.$bid.'&productlistingName='.$name);
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
			redirect('../../?id='.$pageId.'&makeOffer='.$productId.'&productlistingSuccess=true');
		} else {
			echo 'Not allowed!';
		}
	} else {
		echo 'Does no exist';
	}
}
?>