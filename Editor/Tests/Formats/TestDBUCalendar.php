<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Formats
 */

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
		Log::debug($calendar);
    }
}
?>