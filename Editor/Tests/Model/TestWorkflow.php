<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestWorkflow extends AbstractObjectTest {

	function TestWorkflow() {
		parent::AbstractObjectTest('workflow');
	}

	function testProperties() {
		$obj = new Workflow();
		$obj->setTitle('My flow');
		$obj->setRecipe('<?xml version="1.0"?><workflow/>');
		$obj->save();

		$obj2 = Workflow::load($obj->getId());
		$this->assertEqual($obj2->getTitle(),$obj->getTitle());
		$this->assertEqual($obj2->getRecipe(),$obj->getRecipe());

		$obj2->remove();
	}
}
?>