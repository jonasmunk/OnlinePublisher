<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestSource extends AbstractObjectTest {

	function TestSource() {
		parent::AbstractObjectTest('source');
	}

	function testProperties() {
		$obj = new Source();
		$obj->setTitle('My source');
		$obj->setUrl('http://daringfireball.net/feeds/main');
		$obj->save();

		$obj2 = Source::load($obj->getId());
		$this->assertEqual($obj2->getTitle(),$obj->getTitle());
		$this->assertEqual($obj2->getUrl(),$obj->getUrl());

		$obj2->remove();
	}
}
?>