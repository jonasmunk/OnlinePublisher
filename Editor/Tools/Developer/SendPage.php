<?
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Publishing.php';

require("../../Libraries/phpmailer/class.phpmailer.php");

$id = 3;


$page = buildPage($id);
$html = applyStylesheet($page['xml'],'in2isoft','document',$baseUrl,$baseUrl,$baseUrl,'');

$mail = new PHPMailer();

$mail->From     = "jonasmunk@mac.com";
$mail->FromName = "Jonas Munk";
$mail->Host     = "mail1.stofanet.dk";
$mail->Mailer   = "smtp";


// HTML body
$body = $html;
/*
$body  = "Hello <font size=\"4\">JonasMunk</font>, <p>";
$body .= "<i>Your</i> personal photograph to this message.<p>";
$body .= "Sincerely, <br>";
$body .= "PHPMailer List manager";
*/
    // Plain text body (for mail clients that cannot read HTML)
$text_body  = "Hello JonasMunk, \n\n";
$text_body .= "Your personal photograph to this message.\n\n";
$text_body .= "Sincerely, \n";
$text_body .= "PHPMailer List manager";

$mail->Body    = $body;
$mail->AltBody = $text_body;
$mail->AddAddress("jonasmunk@mac.com", "Jonas Munk");
//$mail->AddAddress("jbm@ah.dk", "Jonas B. Munk");
//$mail->AddStringAttachment($row["photo"], "YourPhoto.jpg");

if(!$mail->Send()) {
   echo "There has been a mail error sending to " . $row["email"] . "<br>";
}
else {
	echo "No problemo";
}

?>