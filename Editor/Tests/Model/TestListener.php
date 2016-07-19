<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestListener extends AbstractObjectTest {

	function TestListener() {
		parent::AbstractObjectTest('listener');
	}

	function testProperties() {
		$obj = new Listener();
		$obj->setTitle('My test listener');
		$obj->setEvent('time');
		$obj->setLatestExecution(time());
		$obj->save();

		$loaded = Listener::load($obj->getId());
		$this->assertEqual($obj->getTitle(), $loaded->getTitle());
		$this->assertEqual($obj->getEvent(), $loaded->getEvent());
		$this->assertEqual($obj->getLatestExecution(), $loaded->getLatestExecution());

		$loaded->remove();
	}
}
?>