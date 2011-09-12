<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

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