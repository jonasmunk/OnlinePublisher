<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

class TestImage extends AbstractObjectTest {
    
	function TestImage() {
		parent::AbstractObjectTest('image');
	}

	function testProperties() {
		$obj = new Image();
		$obj->setTitle('My photo');
		$obj->save();
		
		$obj2 = Image::load($obj->getId());
		$this->assertEqual($obj2->getTitle(),'My photo');
		
		$obj2->remove();
	}
}
?>