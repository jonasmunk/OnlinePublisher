<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

class TestTextPart extends UnitTestCase {
    
    function testLoad() {
        $this->assertNull(TextPart::load(0));
    }

    function testCreate() {
        $obj = new TextPart();
		$this->assertFalse($obj->isPersistent());
		$obj->save();
		$this->assertTrue($obj->isPersistent());
		$id = $obj->getId();
        $this->assertNotNull(TextPart::load($id));
		$obj->remove();
        $this->assertNull(TextPart::load($id));
    }

	function testProperties() {
		$obj = new TextPart();
		$obj->setText('This is the text');
		$obj->save();
		
		$obj2 = TextPart::load($obj->getId());
		$this->assertEqual($obj2->getText(),'This is the text');
		
		$obj2->remove();
	}
}
?>