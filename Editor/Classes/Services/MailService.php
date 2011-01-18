<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
require_once($basePath."Editor/Libraries/phpmailer/class.phpmailer.php");
require_once($basePath."Editor/Classes/Settings.php");
require_once($basePath."Editor/Classes/Log.php");
require_once($basePath.'Editor/Classes/Zend.php');
require_once($basePath.'Editor/Libraries/Zend/Mail.php');
require_once($basePath.'Editor/Libraries/Zend/Mail/Transport/Smtp.php');

class MailService {
	
	function sendToStandard($subject,$body) {
		MailService::send(MailService::getStandardEmail(), MailService::getStandardName(),$subject,$body);
	}
	
	function sendToFeedback($subject,$body) {
		MailService::send(MailService::getFeedbackEmail(), MailService::getFeedbackName(),$subject,$body);
	}
	
	function send($email,$name,$subject,$body) {
		$username = MailService::getUsername();
		$password = MailService::getPassword();
		$port = MailService::getPort();
		if (strlen($username)>0 && strlen($password)>0) {
			$config = array('auth' => 'login', 'username' => $username, 'password' => $password, 'ssl' => 'tls');
			if ($port) {
				$config['port'] = $port;
			}
			// 'ssl' => 'ssl', 'port' => '995'
			$tr = new Zend_Mail_Transport_Smtp(MailService::getServer(),$config);
			Zend_Mail::setDefaultTransport($tr);
		}

		$mail = new Zend_Mail(Zend_Mime::ENCODING_BASE64);
		$mail->setBodyText($body);
		$mail->setFrom(MailService::getStandardEmail(), MailService::getStandardName());
		$mail->addTo($email, $name);
		$mail->setSubject($subject);
		try {
			$mail->send();
			return true;
		} catch (Zend_Exception $e) {  
			Log::debug($e->getMessage());
			return false;
		}
		
	}
	
	function getEnabled() {
		return Settings::getSetting('system','mail','enabled')=='true';
	}
	
	function setEnabled($value) {
		Settings::setSetting('system','mail','enabled',$value ? 'true' : 'false');
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
	
	function getFeedbackEmail() {
		return Settings::getSetting('system','mail','feedback-email');
	}
	
	function setFeedbackEmail($value) {
		Settings::setSetting('system','mail','feedback-email',$value);
	}
	
	function getFeedbackName() {
		return Settings::getSetting('system','mail','feedback-name');
	}
	
	function setFeedbackName($value) {
		Settings::setSetting('system','mail','feedback-name',$value);
	}
}