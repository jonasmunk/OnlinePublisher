<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestStreamItem extends AbstractObjectTest {

	function TestStreamItem() {
		parent::AbstractObjectTest('stream');
	}

	function testProperties() {
		$obj = new StreamItem();
		$obj->setTitle('My stream item');
		$obj->setHash('fa6f76f86f8af6da8fd6a8');
		$obj->setData('{"hep":"hey"}');
		$obj->setOriginalDate(67867);
		$obj->setRetrievalDate(5676675);
		$obj->save();

		$loaded = StreamItem::load($obj->getId());
		$this->assertEqual($loaded->getTitle(),$obj->getTitle());
		$this->assertEqual($loaded->getHash(),$obj->getHash());
		$this->assertEqual($loaded->getData(),$obj->getData());
		$this->assertEqual($loaded->getOriginalDate(),$obj->getOriginalDate());
		$this->assertEqual($loaded->getRetrievalDate(),$obj->getRetrievalDate());

		$loaded->remove();
	}
}
?>