<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

class TestFrame extends UnitTestCase {
    
    function testLoad() {
        $this->assertNull(Frame::load(0));
    }

    function testCreate() {
        $obj = new Frame();
		$this->assertFalse($obj->isPersistent());
		$obj->save();
		$this->assertTrue($obj->isPersistent());
		$id = $obj->getId();
		$this->assertNotNull($id);
        $this->assertNotNull(Frame::load($id));
		$obj->remove();
        $this->assertNull(Frame::load($id));
    }

	function testProperties() {
		$obj = new Frame();
		$obj->setTitle('My frame');
		$obj->save();
		
		$obj2 = Frame::load($obj->getId());
		$this->assertEqual($obj2->getTitle(),'My frame');
		$this->assertTrue($obj->getChanged()<=time());
		
		$obj2->remove();
	}
}
?>