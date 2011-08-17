<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

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