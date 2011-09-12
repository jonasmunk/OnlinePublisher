<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestDesign extends AbstractObjectTest {
    
	function TestDesign() {
		parent::AbstractObjectTest('design');
	}

	function testProperties() {
		$obj = new Design();
		$obj->setTitle('My design');
		$obj->save();
		
		$obj2 = Design::load($obj->getId());
		$this->assertEqual($obj2->getTitle(),'My design');
		
		$obj2->remove();
		
		$this->assertFalse(Design::load($obj->getId()));
	}
}
?>