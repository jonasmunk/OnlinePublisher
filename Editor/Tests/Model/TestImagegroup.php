<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

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