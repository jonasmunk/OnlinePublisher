<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

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