<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

class TestFormulaPart extends UnitTestCase {
    
    function testLoad() {
        $this->assertNull(HtmlPart::load(0));
    }

    function testCreate() {
        $obj = new FormulaPart();
		$this->assertFalse($obj->isPersistent());
		$obj->save();
		$this->assertTrue($obj->isPersistent());
		$id = $obj->getId();
        $this->assertNotNull(FormulaPart::load($id));
		$obj->remove();
        $this->assertNull(FormulaPart::load($id));
    }

	function testProperties() {
		$obj = new FormulaPart();
		$obj->setReceiverName('Jonas Munk');
		$obj->setReceiverEmail('user@domain.com');
		$obj->save();
		
		$obj2 = FormulaPart::load($obj->getId());
		$this->assertEqual($obj2->getReceiverName(),$obj->getReceiverName());
		$this->assertEqual($obj2->getReceiverEmail(),$obj->getReceiverEmail());
		
		$obj2->remove();
	}
}
?>