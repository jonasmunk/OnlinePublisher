<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Parts
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestMailinglistPart extends UnitTestCase {
    
    function testLoad() {
        $this->assertNull(MailinglistPart::load(0));
    }

    function testCreate() {
        $obj = new MailinglistPart();
		$this->assertFalse($obj->isPersistent());
		$obj->save();
		$this->assertTrue($obj->isPersistent());
		$id = $obj->getId();
        $this->assertNotNull(MailinglistPart::load($id));
		$obj->remove();
        $this->assertNull(MailinglistPart::load($id));
    }

	function testMailinglists() {
		$obj = new MailinglistPart();
		$obj->setMailinglistIds(array(10,12,345));
		$obj->save();
		
		$obj2 = MailinglistPart::load($obj->getId());
		$ids = $obj2->getMailinglistIds();
		
		$this->assertTrue(in_array(10,$ids));
		$this->assertTrue(in_array(12,$ids));
		$this->assertTrue(in_array(345,$ids));
		
		$obj2->remove();
	}
}
?>