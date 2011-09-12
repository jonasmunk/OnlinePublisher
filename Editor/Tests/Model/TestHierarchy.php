<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestHierarchy extends UnitTestCase {

	function testCreate() {
		
		$hierarchy = new Hierarchy();
		$hierarchy->setName('My test hierarchy');
		$this->assertNull($hierarchy->getId());
		$hierarchy->save();
		
		$hierarchy->setLanguage('EN');
		$hierarchy->save();
		
		$this->assertNotNull($hierarchy->getId());
		
		$loaded = Hierarchy::load($hierarchy->getId());
		$this->assertNotNull($loaded);
		$this->assertEqual('My test hierarchy',$loaded->getName());
		$this->assertEqual('EN',$loaded->getLanguage());

		$this->assertFalse($loaded->createItem(array()));
		$this->assertFalse($loaded->createItem(array('title'=>'My item')));
		$this->assertFalse($loaded->createItem(array('title'=>'My item','targetType'=>'url')));
		$this->assertFalse($loaded->createItem(array('title'=>'My item','targetType'=>'url','targetValue'=>'http://www.onlineobjects.com/')));
		
		$itemId = $loaded->createItem(array('title'=>'My item','targetType'=>'url','targetValue'=>'http://www.onlineobjects.com/','parent'=>0,'hidden'=>false));
		$this->assertTrue($itemId!==false);
		$this->assertFalse($hierarchy->canDelete());
		
		$response = Hierarchy::deleteItem($itemId);
		$this->assertEqual($response,$loaded->getId());
		
		$itemId = $loaded->createItemForPage(1,'My item',0);
		$this->assertTrue($itemId!==false);
		$this->assertFalse($hierarchy->canDelete());
		
		$response = Hierarchy::deleteItem($itemId);
		$this->assertEqual($response,$loaded->getId());

		$this->assertTrue($hierarchy->canDelete());
		$hierarchy->delete();

		$loaded = Hierarchy::load($hierarchy->getId());
		$this->assertNull($loaded);
	}
}
?>