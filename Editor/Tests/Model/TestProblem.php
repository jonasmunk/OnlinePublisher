<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestProblem extends AbstractObjectTest {
    
	function TestProblem() {
		parent::AbstractObjectTest('problem');
	}

	function testProperties() {
		$obj = new Problem();
		$obj->setTitle('My problem');
		$obj->save();
		
		$obj2 = Problem::load($obj->getId());
		$this->assertEqual($obj2->getTitle(),'My problem');
		
		$obj2->remove();
		
		$this->assertFalse(Problem::load($obj->getId()));
	}
}
?>