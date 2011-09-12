<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestFilegroup extends AbstractObjectTest {
    
	function TestFilegroup() {
		parent::AbstractObjectTest('filegroup');
	}

	function testProperties() {
		$obj = new Filegroup();
		$obj->setTitle('My group');
		$obj->save();
		
		$obj2 = Filegroup::load($obj->getId());
		$this->assertEqual($obj2->getTitle(),'My group');
		
		$obj2->remove();
	}
}
?>