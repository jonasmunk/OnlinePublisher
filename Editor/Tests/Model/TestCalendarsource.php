<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

class TestCalendarsource extends AbstractObjectTest {
    
	function TestCalendarsource() {
		parent::AbstractObjectTest('calendarsource');
	}

	function testProperties() {
		$obj = new Calendarsource();
		$obj->setTitle('My source');
		$obj->save();
		
		$obj2 = Calendarsource::load($obj->getId());
		$this->assertEqual($obj2->getTitle(),'My source');
		
		$obj2->remove();
	}
}
?>