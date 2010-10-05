<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

class TestNewsPartController extends UnitTestCase {
    
    function testLoad() {
        $this->assertNotNull(PartService::getController('news'));
    }

	function testBuild() {
		$part = new NewsPart();
		$context = new PartContext();
		
		$ctrl = new NewsPartController();
		Log::debug($ctrl->build($part,$context));
		$this->assertTrue($ctrl->isDynamic($part));
		$this->assertEqual($ctrl->getIndex($part),'');
	}
}
?>