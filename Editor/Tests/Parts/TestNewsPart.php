<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Parts
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestNewsPart extends UnitTestCase {
    
    function testLoad() {
        $this->assertNull(NewsPart::load(0));
    }

    function testCreate() {
        $obj = new NewsPart();
		$this->assertFalse($obj->isPersistent());
		$obj->save();
		$this->assertTrue($obj->isPersistent());
		$id = $obj->getId();
        $this->assertNotNull(NewsPart::load($id));
		$obj->remove();
        $this->assertNull(NewsPart::load($id));
    }

	function testProperties() {
		$obj = new NewsPart();
		$obj->setVariant('box');
		$obj->save();
		
		$obj2 = NewsPart::load($obj->getId());
		$this->assertEqual($obj2->getVariant(),'box');
		
		$obj2->remove();
	}

	function testGroups() {
		$obj = new NewsPart();
		$obj->setNewsGroupIds(array(10,12,345));
		$obj->save();
		
		$obj2 = NewsPart::load($obj->getId());
		$ids = $obj2->getNewsGroupIds();
		
		$this->assertTrue(in_array(10,$ids));
		$this->assertTrue(in_array(12,$ids));
		$this->assertTrue(in_array(345,$ids));
		
		$obj2->remove();
	}
}
?>