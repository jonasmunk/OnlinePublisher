<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

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