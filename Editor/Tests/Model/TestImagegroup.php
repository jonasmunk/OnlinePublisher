<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

class TestImagegroup extends AbstractObjectTest {
    
	function TestImagegroup() {
		parent::AbstractObjectTest('imagegroup');
	}

	function testProperties() {
		$obj = new Imagegroup();
		$obj->setTitle('My photo');
		$obj->save();
		
		$obj2 = Imagegroup::load($obj->getId());
		$this->assertEqual($obj2->getTitle(),'My photo');
		
		$obj2->remove();
	}
}
?>