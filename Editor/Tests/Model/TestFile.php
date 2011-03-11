<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

class TestFile extends AbstractObjectTest {
    
	function TestFile() {
		parent::AbstractObjectTest('file');
	}

	function testProperties() {
		$obj = new File();
		$obj->setTitle('My file');
		$obj->save();
		
		$loaded = File::load($obj->getId());
		$this->assertEqual($loaded->getTitle(),'My file');
		
		$loaded->remove();
	}
}
?>