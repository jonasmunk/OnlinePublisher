<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Parts
 */

class TestPosterPart extends UnitTestCase {
    
    function testLoad() {
        $this->assertNull(PosterPart::load(0));
    }

    function testCreate() {
        $obj = new PosterPart();
		$this->assertFalse($obj->isPersistent());
		$obj->save();
		$this->assertTrue($obj->isPersistent());
		$id = $obj->getId();
        $this->assertNotNull(PosterPart::load($id));
		$obj->remove();
        $this->assertNull(PosterPart::load($id));
    }

	function testProperties() {
		$obj = new PosterPart();
		$obj->setRecipe('<h1>Test</h1>');
		$obj->save();
		
		$obj2 = PosterPart::load($obj->getId());
		$this->assertEqual($obj2->getRecipe(),'<h1>Test</h1>');
		
		$obj2->remove();
	}

	function testImport() {
		$obj = new PosterPart();
		$obj->setRecipe('<h1>Please get me back!</h1>');
		$ctrl = new PosterPartController();
		
		$xml = $ctrl->build($obj,new PartContext());
		
		$this->assertNull($ctrl->importFromString(null));
		
		$imported = $ctrl->importFromString($xml);
		
		$this->assertNotNull($imported);
		$this->assertIdentical($imported->getRecipe(),$obj->getRecipe());
	}
	
	function testRendering() {
		$part = new PosterPart();
		
		$ctrl = new PosterPartController();
		$html = $ctrl->render($part,new PartContext());
		//$this->assertEqual(trim($html),'<div class="part_poster"></div>');
	}
}
?>