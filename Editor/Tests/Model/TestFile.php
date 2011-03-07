<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

class TestFile extends AbstractObjectTest {
    
	function TestImage() {
		parent::AbstractObjectTest('file');
	}

	function testProperties() {
		$obj = new File();
		$obj->setTitle('My file');
		$obj->save();
		
		$obj2 = File::load($obj->getId());
		$this->assertEqual($obj2->getTitle(),'My file');
		
		$obj2->remove();
	}
}
?>