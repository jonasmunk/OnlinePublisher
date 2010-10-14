<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

class TestPersonPart extends UnitTestCase {
    
    function testLoad() {
        $this->assertNull(PersonPart::load(0));
    }

    function testCreate() {
        $obj = new PersonPart();
		$this->assertFalse($obj->isPersistent());
		$obj->save();
		$this->assertTrue($obj->isPersistent());
		$id = $obj->getId();
        $this->assertNotNull(PersonPart::load($id));
		$obj->remove();
        $this->assertNull(PersonPart::load($id));
    }

	function testProperties() {
		$obj = new PersonPart();
		$obj->save();
		
		$obj2 = PersonPart::load($obj->getId());
		
		$obj2->remove();
	}
}
?>