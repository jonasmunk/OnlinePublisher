<?
require_once '../../Config/Setup.php';
require_once '../../Editor/Include/Public.php';
require_once('../../Editor/Classes/Core/Request.php');
require_once('../../Editor/Classes/Core/Response.php');
require_once('../../Editor/Classes/Objects/Feedback.php');
require_once('../../Editor/Classes/Services/MailService.php');
require_once('../../Editor/Classes/Utilities/StringUtils.php');

$name = Request::getUnicodeString('name');
$email = Request::getUnicodeString('email');
$message = Request::getUnicodeString('message');

$feedback = new Feedback();
$feedback->setName($name);
$feedback->setEmail($email);
$feedback->setMessage($message);
$feedback->save();

$body = "Besked fra ".$name." (".$email.")\n\n".$message;
MailService::sendToFeedback("Feedback fra website (".$name.")",$body);
?>