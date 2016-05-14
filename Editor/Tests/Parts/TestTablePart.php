<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Parts
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestTablePart extends UnitTestCase {
    
    function testLoad() {
        $this->assertNull(TablePart::load(0));
    }

    function testCreate() {
        $obj = new TablePart();
		$this->assertFalse($obj->isPersistent());
		$obj->save();
		$this->assertTrue($obj->isPersistent());
		$id = $obj->getId();
        $this->assertNotNull(TablePart::load($id));
		$obj->remove();
        $this->assertNull(TablePart::load($id));
    }

	function testProperties() {
		$html = '<table><thead><tr><th>Header</th><th>Header</th></tr></thead><tbody><tr><td>Cell</td><td>Cell</td></tr></tbody></table>';
		
		$obj = new TablePart();
		$obj->setHtml($html);
		$obj->save();
		
		$obj2 = TablePart::load($obj->getId());
		$this->assertEqual($obj2->getHtml(),$html);
		
		$obj2->remove();
	}
	

	function testDisplay() {
		$html = '<table><thead><tr><th>Header</th><th>Header</th></tr></thead><tbody><tr><td>Cell</td><td>Cell</td></tr></tbody></table>';
		$obj = new TablePart();
		$obj->setHtml($html);
		$ctrl = new TablePartController();
		
		$this->assertTrue(DOMUtils::isValidFragment($html));
		
		$result = $ctrl->display($obj,new PartContext());
		Log::debug('Display:'.$result);
		$this->assertEqual(trim($result),'<div xmlns="http://www.w3.org/1999/xhtml" class="part_table common_font">'.$html.'</div>');
	}

	function testImportValid() {
		$html = '<table><thead><tr><th>Header</th><th>Header</th></tr></thead><tbody><tr><td>Cell</td><td>Cell</td></tr></tbody></table>';
		$obj = new TablePart();
		$obj->setHtml($html);
		$ctrl = new TablePartController();
		
		$xml = $ctrl->build($obj,new PartContext());
		
		$this->assertNull($ctrl->importFromString(null));
		
		$imported = $ctrl->importFromString($xml);
		
		$this->assertNotNull($imported);
		$this->assertIdentical($imported->getHtml(),$obj->getHtml());
	}

	function testImportInvalid() {
		$obj = new TablePart();
		$obj->setHtml('<table>Im in<alid<<>><</table>');
		$ctrl = new TablePartController();
		
		$xml = $ctrl->build($obj,new PartContext());
		
		$this->assertNull($ctrl->importFromString(null));
		
		$imported = $ctrl->importFromString($xml);
		
		$this->assertNotNull($imported);
		$this->assertIdentical($imported->getHtml(),$obj->getHtml());
	}
}
?>