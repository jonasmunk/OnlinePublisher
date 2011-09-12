<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Parts
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

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

	function testImport() {
		$obj = new ListingPart();
		$obj->setText('* Lorem [s]ipsum[s] dolor [e]sit[e] amet,\n consectetur<tag> [slet]adipisicing[slet] elit\n\nNew paragraph\n\n\nThree & new lines');
		$obj->setColor('#eee');
		$obj->setFontFamily('Verdana');
		$ctrl = new ListingPartController();
		
		$xml = $ctrl->build($obj,new PartContext());
		
		$this->assertNull($ctrl->importFromString(null));
		
		$imported = $ctrl->importFromString($xml);
		
		$this->assertNotNull($imported);
		$this->assertIdentical($imported->getText(),$obj->getText());
		$this->assertIdentical($imported->getColor(),$obj->getColor());
		$this->assertIdentical($imported->getFontFamily(),$obj->getFontFamily());
		$this->assertIdentical($imported->getListStyle(),'disc');
	}
}
?>