<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestNewsgroup extends AbstractObjectTest {
    
	function TestNewsgroup() {
		parent::AbstractObjectTest('newsgroup');
	}

	function testProperties() {
		$obj = new Newsgroup();
		$obj->setTitle('My group');
		$obj->save();
		
		$obj2 = Newsgroup::load($obj->getId());
		$this->assertEqual($obj2->getTitle(),'My group');
		
		$obj2->remove();
	}
}
?>