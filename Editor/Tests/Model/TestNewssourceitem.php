<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

class TestNewssourceitem extends AbstractObjectTest {
    
	function TestNewssourceitem() {
		parent::AbstractObjectTest('newssourceitem');
	}

	function testProperties() {
		$obj = new Newssourceitem();
		$obj->setTitle('My zone');
		$obj->setUrl('https://github.com/in2isoft/OnlinePublisher/commits/master.atom');
		$obj->save();
		
		$loaded = Newssourceitem::load($obj->getId());
		$this->assertEqual($loaded->getTitle(),$obj->getTitle());
		$this->assertEqual($loaded->getUrl(),$obj->getUrl());
		
		$loaded->remove();
	}
}
?>