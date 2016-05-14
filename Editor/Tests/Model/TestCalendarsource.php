<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestCalendarsource extends AbstractObjectTest {
    
	function TestCalendarsource() {
		parent::AbstractObjectTest('calendarsource');
	}

	function testProperties() {
		$obj = new Calendarsource();
		$obj->setTitle('My source');
		$obj->save();
		
		$loaded = Calendarsource::load($obj->getId());
		$this->assertEqual($loaded->getTitle(),'My source');
		
		$loaded->remove();
	}
}
?>