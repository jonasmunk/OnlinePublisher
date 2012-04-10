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
		$url = TestService::getResourceUrl('Kampprogram.xls');
		$calendar = DBUCalendarParser::parseUrl($url);
		$this->assertTrue($calendar!=false);
		$events = $calendar->getEvents();
		$this->assertEqual(count($events),30,'There must be 30 events');
		$first = $events[0];
		$this->assertEqual("Chang Stadion",$first->getLocation());
		$this->assertEqual("Aalborg Chang",$first->getHomeTeam());
		$this->assertEqual("Aalborg KFUM",$first->getGuestTeam());
		$this->assertEqual(1302278400,$first->getStartDate());
		$this->assertEqual(1302284700,$first->getEndDate());

		$this->assertEqual(' 8. Apr 2011 kl. 18:00',DateUtils::formatLongDateTime($first->getStartDate()));
		$this->assertEqual(' 8. Apr 2011 kl. 19:45',DateUtils::formatLongDateTime($first->getEndDate()));


    }

    function testOtherDate() {
		global $baseUrl,$basePath;
		require_once($basePath.'Editor/Classes/Formats/DBUCalendarParser.php');
		require_once($basePath.'Editor/Classes/Formats/DBUCalendar.php');
		require_once($basePath.'Editor/Classes/Formats/DBUCalendarEvent.php');

		$url = TestService::getResourceUrl('Kampprogram_other_date.xls');
		$calendar = DBUCalendarParser::parseUrl($url);
		$this->assertTrue($calendar!=false);
		$events = $calendar->getEvents();
		$this->assertEqual(count($events),30,'There must be 30 events');
		$first = $events[0];
		$this->assertEqual("Hals Stadion",$first->getLocation());
		$this->assertEqual("Hals FS  (2)",$first->getHomeTeam());
		$this->assertEqual("Hals FS (1)",$first->getGuestTeam());
		$this->assertEqual(1334336400,$first->getStartDate());
		$this->assertEqual(1334342700,$first->getEndDate());
		$this->assertEqual('13. Apr 2012 kl. 19:00',DateUtils::formatLongDateTime($first->getStartDate()));
		$this->assertEqual('13. Apr 2012 kl. 20:45',DateUtils::formatLongDateTime($first->getEndDate()));
    }

    function testHandball() {
		global $baseUrl,$basePath;
		require_once($basePath.'Editor/Classes/Formats/DBUCalendarParser.php');
		require_once($basePath.'Editor/Classes/Formats/DBUCalendar.php');
		require_once($basePath.'Editor/Classes/Formats/DBUCalendarEvent.php');

		$url = TestService::getResourceUrl('Kampprogram_haandbold.xls');
		$calendar = DBUCalendarParser::parseUrl($url);
		$this->assertTrue($calendar!=false);
		$events = $calendar->getEvents();
		$this->assertEqual(count($events),62,'There must be 62 events, there are: '.count($events));
		$first = $events[0];
		$this->assertEqual("Flauenskjoldhallen",$first->getLocation());
		$this->assertEqual("Dybvad Håndbold Flauenskjold IF",$first->getHomeTeam());
		$this->assertEqual("Vestbjerg IF",$first->getGuestTeam());
		$this->assertEqual(1317556200,$first->getStartDate());
		$this->assertEqual(1317559500,$first->getEndDate());

		$this->assertEqual(' 2. Okt 2011 kl. 13:50',DateUtils::formatLongDateTime($first->getStartDate()));
		$this->assertEqual(' 2. Okt 2011 kl. 14:45',DateUtils::formatLongDateTime($first->getEndDate()));
    }

    function testU19drenge() {
		global $baseUrl,$basePath;
		require_once($basePath.'Editor/Classes/Formats/DBUCalendarParser.php');
		require_once($basePath.'Editor/Classes/Formats/DBUCalendar.php');
		require_once($basePath.'Editor/Classes/Formats/DBUCalendarEvent.php');

		$url = TestService::getResourceUrl('Kampprogram_U19drenge.xls');
		$calendar = DBUCalendarParser::parseUrl($url);
		$this->assertTrue($calendar!=false);
		$events = $calendar->getEvents();
		$this->assertEqual(count($events),24,'There must be 24 events, there are: '.count($events));
		$first = $events[0];
		$this->assertEqual("Holtet Stadion",$first->getLocation());
		$this->assertEqual("VHG/GS/UB/HIF/HFS",$first->getHomeTeam());
		$this->assertEqual("Støvring IF",$first->getGuestTeam());
		$this->assertEqual(1334334600,$first->getStartDate());
		$this->assertEqual(1334339100,$first->getEndDate());

		$this->assertEqual('13. Apr 2012 kl. 18:30',DateUtils::formatLongDateTime($first->getStartDate()));
		$this->assertEqual('13. Apr 2012 kl. 19:45',DateUtils::formatLongDateTime($first->getEndDate()));
    }

}
?>