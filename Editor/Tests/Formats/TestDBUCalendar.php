<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Formats
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestDBUCalendar extends UnitTestCase {
    
    function testIt() {
		global $baseUrl,$basePath;
		require_once($basePath.'Editor/Classes/Formats/DBUCalendarParser.php');
		require_once($basePath.'Editor/Classes/Formats/DBUCalendar.php');
		require_once($basePath.'Editor/Classes/Formats/DBUCalendarEvent.php');
		$url = $baseUrl.'Editor/Tests/Resources/Kampprogram.xls';
		if ($url[0]=='/') {
			$url = 'http://localhost'.$url;
		}
		$calendar = DBUCalendarParser::parseUrl($url);
		$this->assertTrue($calendar!=false);
		$events = $calendar->getEvents();
		$this->assertEqual(count($events),30,'There must be 30 events');
    }
}
?>