<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Parts
 */

class TestHorizontalRulePart extends UnitTestCase {
    
    function testLoad() {
        $this->assertNull(HorizontalrulePart::load(0));
    }

    function testCreate() {
        $obj = new HorizontalrulePart();
		$this->assertFalse($obj->isPersistent());
		$obj->save();
		$this->assertTrue($obj->isPersistent());
		$id = $obj->getId();
        $this->assertNotNull(HorizontalrulePart::load($id));
		$obj->remove();
        $this->assertNull(HorizontalrulePart::load($id));
    }
}
?>