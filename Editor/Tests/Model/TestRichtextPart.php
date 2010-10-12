<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

class TestRichtextPart extends UnitTestCase {
    
    function testLoad() {
        $this->assertNull(RichtextPart::load(0));
    }

    function testCreate() {
        $obj = new RichtextPart();
		$this->assertFalse($obj->isPersistent());
		$obj->save();
		$this->assertTrue($obj->isPersistent());
		$id = $obj->getId();
        $this->assertNotNull(RichtextPart::load($id));
		$obj->remove();
        $this->assertNull(RichtextPart::load($id));
    }

	function testProperties() {
		$obj = new RichtextPart();
		$obj->setHtml('<h1>Test</h1>');
		$obj->save();
		
		$obj2 = RichtextPart::load($obj->getId());
		$this->assertEqual($obj2->getHtml(),'<h1>Test</h1>');
		
		$obj2->remove();
	}
}
?>