<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Parts
 */

class TestNewsPartController extends UnitTestCase {
    
    function testLoad() {
        $this->assertNotNull(PartService::getController('news'));
    }

	function testBuild() {
		$part = new NewsPart();
		$part->setVariant('list');
		$part->setTitle('Todays news');
		
		$ctrl = new NewsPartController();
		$this->assertTrue($ctrl->isDynamic($part));
		$this->assertEqual($ctrl->getIndex($part),'Todays news');
		
		$xml = $ctrl->build($part,new PartContext());
		$expected = '<part xmlns="http://uri.in2isoft.com/onlinepublisher/part/1.0/" type="news" id="">'.
		'<sub><news xmlns="http://uri.in2isoft.com/onlinepublisher/part/news/1.0/">'.
		'<list><title>Todays news</title></list>'.
		'</news></sub>'.
		'</part>';
		$this->assertEqual($xml,$expected);
	}
}
?>