<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestPath extends AbstractObjectTest {
    
	function TestPath() {
		parent::AbstractObjectTest('path');
	}

	function testProperties() {
		$obj = new Path();
		$obj->setTitle('My path');
		$obj->setPath('en/path/to/file.html');
		$obj->setPageId(100);
		$obj->save();
		
		$obj2 = Path::load($obj->getId());
		$this->assertEqual($obj2->getTitle(),'en/path/to/file.html -> 100');
		$this->assertEqual($obj2->getPath(),'en/path/to/file.html');
		$this->assertEqual($obj2->getPageId(),100);
		
		$obj2->remove();
	}
}
?>