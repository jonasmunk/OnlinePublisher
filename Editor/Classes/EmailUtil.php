<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath."Editor/Libraries/phpmailer/class.phpmailer.php");
require_once($basePath."Editor/Classes/Settings.php");
require_once($basePath.'Editor/Classes/Zend.php');
require_once($basePath.'Editor/Libraries/Zend/Mail.php');
require_once($basePath.'Editor/Libraries/Zend/Mail/Transport/Smtp.php');

class EmailUtil {
	
	function send($email,$name,$subject,$body) {
		
		
		$username = EmailUtil::getUsername();
		$password = EmailUtil::getPassword();
		if (strlen($username)>0 && strlen($password)>0) {
			$config = array('auth' => 'login', 'username' => $username, 'password' => $password, 'ssl' => 'tls');
			$tr = new Zend_Mail_Transport_Smtp('smtp.gmail.com',$config);
			Zend_Mail::setDefaultTransport($tr);
		}

		$mail = new Zend_Mail();
		$mail->setBodyText($body);
		$mail->setFrom(EmailUtil::getStandardEmail(), EmailUtil::getStandardName());
		$mail->addTo($email, $name);
		$mail->setSubject($subject);
		try {
			$mail->send();
			return true;
		} catch (Zend_Exception $e) {  
			error_log($e->getMessage());
			return false;
		}
		
	}
	
	function getServer() {
		return Settings::getSetting('system','mail','server');
	}
	
	function setServer($value) {
		Settings::setSetting('system','mail','server',$value);
	}
	
	function getPort() {
		return Settings::getSetting('system','mail','port');
	}
	
	function setPort($value) {
		Settings::setSetting('system','mail','port',$value);
	}
	
	function getUsername() {
		return Settings::getSetting('system','mail','username');
	}
	
	function setUsername($value) {
		Settings::setSetting('system','mail','username',$value);
	}
	
	function getPassword() {
		return Settings::getSetting('system','mail','password');
	}
	
	function setPassword($value) {
		Settings::setSetting('system','mail','password',$value);
	}
	
	function getStandardEmail() {
		return Settings::getSetting('system','mail','standard-email');
	}
	
	function setStandardEmail($value) {
		Settings::setSetting('system','mail','standard-email',$value);
	}
	
	function getStandardName() {
		return Settings::getSetting('system','mail','standard-name');
	}
	
	function setStandardName($value) {
		Settings::setSetting('system','mail','standard-name',$value);
	}
}
?>