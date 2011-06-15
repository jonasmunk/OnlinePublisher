<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.General
 */


class TestVCal extends UnitTestCase {
	
	function testIt() {
		global $basePath;
		require_once $basePath.'/Editor/Classes/Formats/VCalParser.php';
		$path = $basePath.'/Editor/Tests/Resources/ical.ics';
		$this->assertTrue(file_exists($path));
		$parser = new VCalParser();
		$cal = $parser->parseUrl($path);
		$this->assertTrue($cal!==false);
		
		$this->assertEqual('2.0',$cal->getVersion());
		$this->assertEqual('My calendar',$cal->getTitle());
		$this->assertEqual('Europe/Copenhagen',$cal->getTimeZone());
		
		$events = $cal->getEvents();
		$this->assertEqual(count($events),3);
		
		$first = $events[0];
		$this->assertEqual($first->getSummary(),'My event');
		$this->assertEqual($first->getStartDate(),1290088800);
		$this->assertEqual($first->getEndDate(),1290096000);
		$this->assertEqual(gmdate("M d Y H:i:s", $first->getStartDate()),"Nov 18 2010 14:00:00");
		$this->assertEqual(date("M d Y H:i:s", $first->getStartDate()),"Nov 18 2010 15:00:00");
		$this->assertEqual(gmdate("M d Y H:i:s", $first->getEndDate()),"Nov 18 2010 16:00:00");
		$this->assertEqual($first->getDuration(),null);
		
		$next = $events[1];
		$this->assertEqual($next->getUrl(),"http://www.jonasmunk.dk");
	}
}