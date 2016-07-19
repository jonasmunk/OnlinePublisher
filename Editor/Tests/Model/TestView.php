<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestView extends AbstractObjectTest {

	function TestView() {
		parent::AbstractObjectTest('view');
	}

	function testProperties() {
		$obj = new View();
		$obj->setTitle('My stream');
		$obj->setPath('path/to/folder');
		$obj->save();

		$loaded = View::load($obj->getId());
		$this->assertEqual($loaded->getTitle(),$obj->getTitle());
		$this->assertEqual($loaded->getPath(),$obj->getPath());

		$loaded->remove();
	}
}
?>