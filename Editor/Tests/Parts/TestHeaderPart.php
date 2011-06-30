<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Parts
 */

class TestHeaderPart extends UnitTestCase {
    
    function testLoad() {
        $this->assertNull(HeaderPart::load(0));
    }

    function testCreate() {
        $obj = new HeaderPart();
		$this->assertFalse($obj->isPersistent());
		$obj->save();
		$this->assertTrue($obj->isPersistent());
		$id = $obj->getId();
        $this->assertNotNull(HeaderPart::load($id));
		$obj->remove();
        $this->assertNull(HeaderPart::load($id));
    }

	function testProperties() {
		$obj = new HeaderPart();
		$obj->setText('This is the text');
		$obj->setLevel(3);
		$obj->save();
		
		$obj2 = HeaderPart::load($obj->getId());
		$this->assertEqual($obj2->getText(),'This is the text');
		$this->assertEqual($obj2->getLevel(),3);
		
		$obj2->remove();
	}

	function testIndex() {
		$obj = new HeaderPart();
		$obj->setText("Lorem [s]ipsum[s] dolor [e]sit[e] amet,\n consectetur<tag> [slet]adipisicing[slet] elit\n\nNew paragraph\n\n\nThree & new lines");
		$ctrl = new HeaderPartController();
		$index = $ctrl->getIndex($obj);
		$expected = "Lorem ipsum dolor sit amet,\n consectetur<tag> adipisicing elit\n\nNew paragraph\n\n\nThree & new lines";
		$this->assertEqual($index,$expected);
	}

	function testImport() {
		$obj = new HeaderPart();
		$obj->setText('Lorem [s]ipsum[s] dolor [e]sit[e] amet,\n consectetur<tag> [slet]adipisicing[slet] elit\n\nNew paragraph\n\n\nThree & new lines');
		$obj->setColor('#eee');
		$obj->setFontFamily('Verdana');
		$obj->setLevel(3);
		$ctrl = new HeaderPartController();
		
		$xml = $ctrl->build($obj,new PartContext());
		
		$this->assertNull($ctrl->importFromString(null));
		
		$imported = $ctrl->importFromString($xml);
		
		$this->assertNotNull($imported);
		$this->assertIdentical($imported->getText(),$obj->getText());
		$this->assertIdentical($imported->getColor(),$obj->getColor());
		$this->assertIdentical($imported->getFontFamily(),$obj->getFontFamily());
		$this->assertIdentical($imported->getLevel(),$obj->getLevel());
	}
}
?>