<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestMilestone extends AbstractObjectTest {
    
	function TestMilestone() {
		parent::AbstractObjectTest('milestone');
	}

	function testProperties() {
		$obj = new Milestone();
		$obj->setTitle('My stone');
		$obj->save();
		
		$obj2 = Milestone::load($obj->getId());
		$this->assertEqual($obj2->getTitle(),'My stone');
		
		$obj2->remove();
		
		$this->assertFalse(Milestone::load($obj->getId()));
	}
}
?>