<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

class TestFeedback extends UnitTestCase {
    
    function testLoad() {
        $this->assertNull(Feedback::load(0));
    }

    function testCreate() {
        $obj = new Feedback();
		$this->assertFalse($obj->isPersistent());
		$obj->save();
		$this->assertTrue($obj->isPersistent());
		$id = $obj->getId();
        $this->assertNotNull(Feedback::load($id));
		$obj->remove();
        $this->assertNull(Feedback::load($id));
    }

	function testProperties() {
		$obj = new Feedback();
		$obj->setName('Jonas Munk');
		$obj->setEmail('jonas@munk.dk');
		$obj->setMessage('This is the text');
		$obj->save();
		
		$obj2 = Feedback::load($obj->getId());
		$this->assertEqual($obj2->getName(),'Jonas Munk');
		$this->assertEqual($obj2->getEmail(),'jonas@munk.dk');
		$this->assertEqual($obj2->getMessage(),'This is the text');
		
		$obj2->remove();
	}
}
?>