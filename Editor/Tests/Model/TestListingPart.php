<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

class TestListingPart extends UnitTestCase {
    
    function testLoad() {
        $this->assertNull(ListingPart::load(0));
    }

    function testCreate() {
        $obj = new ListingPart();
		$this->assertFalse($obj->isPersistent());
		$obj->save();
		$this->assertTrue($obj->isPersistent());
		$id = $obj->getId();
        $this->assertNotNull(ListingPart::load($id));
		$obj->remove();
        $this->assertNull(ListingPart::load($id));
    }

	function testProperties() {
		$obj = new ListingPart();
		$obj->setText('This is the text');
		$obj->setListStyle('disc');
		$obj->save();
		
		$obj2 = ListingPart::load($obj->getId());
		$this->assertEqual($obj2->getText(),'This is the text');
		$this->assertEqual($obj2->getListStyle(),'disc');
		
		$obj2->remove();
	}
}
?>