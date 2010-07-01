<?
require_once($basePath."Editor/Libraries/phpmailer/class.phpmailer.php");
require_once($basePath."Editor/Classes/Settings.php");

class Mailer {

/**
 * @static
 */
	function canSendMail() {
		$server = Settings::getSetting('system','mail','server');
		$port = Settings::getSetting('system','mail','port');
		
		return (strlen($server)>0 && strlen($port)>0);
	}
}
?>