<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

class TestFrame extends AbstractObjectTest {
    
	function TestFrame() {
		parent::AbstractObjectTest('frame');
	}

	function testProperties() {
		$obj = new Frame();
		$obj->setTitle('My frame');
		$obj->save();
		
		$obj2 = Frame::load($obj->getId());
		$this->assertEqual($obj2->getTitle(),'My frame');
		$this->assertTrue($obj->getChanged()<=time());
		
		$obj2->remove();
	}
}
?>