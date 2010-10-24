<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

class TestUser extends UnitTestCase {
    
    function testLoad() {
        $this->assertNull(Securityzone::load(0));
    }

    function testCreate() {
        $obj = new Securityzone();
		$this->assertFalse($obj->isPersistent());
		$obj->save();
		$this->assertTrue($obj->isPersistent());
		$id = $obj->getId();
        $this->assertNotNull(Securityzone::load($id));
		$obj->remove();
        $this->assertNull(Securityzone::load($id));
    }

	function testProperties() {
		$obj = new Securityzone();
		$obj->setTitle('My zone');
		$obj->setAuthenticationPageId(789);
		$obj->save();
		
		$obj2 = Securityzone::load($obj->getId());
		$this->assertEqual($obj2->getTitle(),$obj->getTitle());
		$this->assertEqual($obj2->getAuthenticationPageId(),$obj->getAuthenticationPageId());
		
		$obj2->remove();
	}
}
?>