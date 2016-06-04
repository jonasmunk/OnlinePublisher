<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestStream extends AbstractObjectTest {

	function TestStream() {
		parent::AbstractObjectTest('stream');
	}

	function testProperties() {
		$obj = new Stream();
		$obj->setTitle('My stream');
		$obj->save();

		$loaded = Stream::load($obj->getId());
		$this->assertEqual($loaded->getTitle(),$obj->getTitle());

		$loaded->remove();
	}
}
?>