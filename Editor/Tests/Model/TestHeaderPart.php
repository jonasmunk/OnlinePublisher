<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

class TestHeaderPart extends UnitTestCase {
    
    function testLoad() {
        $this->assertNull(HeaderPart::load(0));
    }

    function testCreate() {
        $obj = new HeaderPart();
		$this->assertFalse($obj->isPersistent());
		$obj->save();
		$this->assertTrue($obj->isPersistent());
		$id = $obj->getId();
        $this->assertNotNull(HeaderPart::load($id));
		$obj->remove();
        $this->assertNull(HeaderPart::load($id));
    }

	function testProperties() {
		$obj = new HeaderPart();
		$obj->setText('This is the text');
		$obj->setLevel(3);
		$obj->save();
		
		$obj2 = HeaderPart::load($obj->getId());
		$this->assertEqual($obj2->getText(),'This is the text');
		$this->assertEqual($obj2->getLevel(),3);
		
		$obj2->remove();
	}
}
?>