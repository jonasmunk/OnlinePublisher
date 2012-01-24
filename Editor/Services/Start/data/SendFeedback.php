<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Include/Private.php';

$message = Request::getEncodedString('message');

$user = User::load(InternalSession::getUserId());

$success = postIt(,);

$request = new HttpRequest('http://localhost/~jbm/Publisher/services/issues/create/');
$request->setParameters(array('description'=>$message,'site'=>$baseUrl,'user'=>$user->getUsername()));

$client = new HttpClient();

$response = $client->send($request);

//$success = MailService::sendToFeedback('Feedback',$message);

if (!$success) {
	In2iGui::respondFailure();
}


function postIt($url,$parameters) {
	$body = http_build_query($parameters,'','&');
	
	$session = curl_init($url);
	curl_setopt ($session, CURLOPT_POST, 1);
	curl_setopt ($session, CURLOPT_POSTFIELDS, $body);
	curl_setopt ($session, CURLOPT_FOLLOWLOCATION, 1);
	curl_exec ($session);
	curl_close ($session);
	return true;
}
?>