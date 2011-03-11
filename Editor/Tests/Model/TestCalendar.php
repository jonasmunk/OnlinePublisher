<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

class TestCalendar extends AbstractObjectTest {
    
	function TestCalendarsource() {
		parent::AbstractObjectTest('calendar');
	}

	function testProperties() {
		$obj = new Calendar();
		$obj->setTitle('My source');
		$obj->save();
		
		$loaded = Calendar::load($obj->getId());
		$this->assertEqual($loaded->getTitle(),'My source');
		
		$loaded->remove();
	}
}
?>