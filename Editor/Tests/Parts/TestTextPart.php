<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Parts
 */

class TestTextPart extends UnitTestCase {
    
    function testLoad() {
        $this->assertNull(TextPart::load(0));
    }

    function testCreate() {
        $obj = new TextPart();
		$this->assertFalse($obj->isPersistent());
		$obj->save();
		$this->assertTrue($obj->isPersistent());
		$id = $obj->getId();
        $this->assertNotNull(TextPart::load($id));
		$obj->remove();
        $this->assertNull(TextPart::load($id));
    }

	function testProperties() {
		$obj = new TextPart();
		$obj->setText('This is the text');
		$obj->save();
		
		$obj2 = TextPart::load($obj->getId());
		$this->assertEqual($obj2->getText(),'This is the text');
		
		$obj2->remove();
	}
	
	function testBuild() {
		$obj = new TextPart();
		$obj->setId(20);
		$obj->setText("Lorem [s]ipsum[s] dolor [e]sit[e] amet,\n consectetur<tag> [slet]adipisicing[slet] elit\n\nNew paragraph\n\n\nThree & new lines");
		$obj->setColor('#eee');
		$obj->setFontFamily('Verdana');
		$ctrl = new TextPartController();
		$xml = $ctrl->build($obj,new PartContext());
		$expected = '<part xmlns="http://uri.in2isoft.com/onlinepublisher/part/1.0/" type="text" id="20">'.
			'<sub><text xmlns="http://uri.in2isoft.com/onlinepublisher/part/text/1.0/">'.
			'<style color="#eee" font-family="Verdana"/>'.
			'<p>Lorem <strong>ipsum</strong> dolor <em>sit</em> amet,<break/> consectetur&lt;tag&gt; <del>adipisicing</del> elit</p>'.
			'<p>New paragraph</p>'.
			'<p><break/>Three &amp; new lines</p>'.
			'</text></sub>'.
			'</part>';
		$this->assertEqual($xml,$expected);
	}

	function testIndex() {
		$obj = new TextPart();
		$obj->setText("Lorem [s]ipsum[s] dolor [e]sit[e] amet,\n consectetur<tag> [slet]adipisicing[slet] elit\n\nNew paragraph\n\n\nThree & new lines");
		$ctrl = new TextPartController();
		$index = $ctrl->getIndex($obj);
		$expected = "Lorem ipsum dolor sit amet,\n consectetur<tag> adipisicing elit\n\nNew paragraph\n\n\nThree & new lines";
		$this->assertEqual($index,$expected);
	}

	function testImport() {
		$obj = new TextPart();
		$obj->setText('Lorem [s]ipsum[s] dolor [e]sit[e] amet,\n consectetur<tag> [slet]adipisicing[slet] elit\n\nNew paragraph\n\n\nThree & new lines');
		$obj->setColor('#eee');
		$obj->setFontFamily('Verdana');
		$ctrl = new TextPartController();
		
		$xml = $ctrl->build($obj,new PartContext());
		
		$this->assertNull($ctrl->importFromString(null));
		
		$imported = $ctrl->importFromString($xml);
		
		$this->assertNotNull($imported);
		$this->assertIdentical($imported->getText(),$obj->getText());
		$this->assertIdentical($imported->getColor(),$obj->getColor());
		$this->assertIdentical($imported->getFontFamily(),$obj->getFontFamily());
	}
}
?>