<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Parts
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

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
	

	function testDisplay() {
		$obj = new PersonPart();
		$obj->setPersonId(ObjectService::getLatestId('person'));
		$ctrl = new PersonPartController();
		
		$html = $ctrl->display($obj,new PartContext());
		$this->assertNotNull($html);
	}
}
?>