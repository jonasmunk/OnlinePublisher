<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

class TestCalendar extends AbstractObjectTest {
    
	function TestCalendar() {
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