<?
require_once '../../Config/Setup.php';
require_once '../../Editor/Include/Public.php';
require_once('../../Editor/Classes/Request.php');
require_once('../../Editor/Classes/Response.php');
require_once('../../Editor/Classes/Feedback.php');
require_once('../../Editor/Classes/EmailUtil.php');
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
EmailUtil::sendToStandard("Feedback fra website (".$name.")",$body);
?>