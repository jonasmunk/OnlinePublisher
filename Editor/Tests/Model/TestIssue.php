<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestIssue extends AbstractObjectTest {
    
	function TestIssue() {
		parent::AbstractObjectTest('issue');
	}

	function testProperties() {
		$obj = new Issue();
		$obj->setTitle('My issue');
		$obj->setKind(Issue::$improvement);
		$obj->save();
		
		$obj2 = Issue::load($obj->getId());
		$this->assertEqual($obj2->getTitle(),'My issue');
		$this->assertEqual($obj2->getKind(),Issue::$improvement);
		
		$obj2->remove();
		
		$this->assertFalse(Issue::load($obj->getId()));
		
	}
}
?>