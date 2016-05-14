<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestSpecialPage extends UnitTestCase {

	function testCreate() {
		$obj = new SpecialPage();
		$obj->setType('home');
		
		$this->assertNull($obj->getId());
		$obj->save();
		
		$this->assertNotNull($obj->getId());
		
		$loaded = SpecialPage::load($obj->getId());
		$this->assertEqual($loaded->getType(),'home');
		$this->assertEqual($loaded->getLanguage(),'');

		$obj->setLanguage('EN');
		$obj->setType('error');
		$obj->save();

		$loaded = SpecialPage::load($obj->getId());
		$this->assertEqual($loaded->getType(),'error');
		$this->assertEqual($loaded->getLanguage(),'EN');
		
		$loaded->remove();
		
		$loaded = SpecialPage::load($obj->getId());
		$this->assertNull($loaded);
	}
}
?>