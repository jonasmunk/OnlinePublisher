<?php

require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once('../../Classes/Zend.php');
require_once('../../Libraries/Zend/Json.php');
require_once('../../Libraries/Zend/Mail.php');
require_once('../../Libraries/Zend/Mail/Transport/Smtp.php');

$config = array('auth' => 'login',
                'username' => 'jonasmunk',
                'password' => 'cyberdog',
                'ssl' => 'tls'    ); 

$tr = new Zend_Mail_Transport_Smtp('smtp.gmail.com',$config);
Zend_Mail::setDefaultTransport($tr);

$mail = new Zend_Mail();
$mail->setBodyText('This is the text of the mail.');
$mail->setFrom('jonasmunk@gmail.com', 'Jonas Munk');
$mail->addTo('jonasmunk@me.com', 'Jonas B Munk');
$mail->setSubject('TestSubject');
$mail->send();

echo Zend_Json::encode(array('name'=>'æøå'));
?>