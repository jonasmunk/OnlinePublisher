<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

class TestNewssource extends AbstractObjectTest {
    
	function TestSecurityzone() {
		parent::AbstractObjectTest('newssource');
	}

	function testProperties() {
		$obj = new Newssource();
		$obj->setTitle('My zone');
		$obj->setUrl('https://github.com/in2isoft/OnlinePublisher/commits/master.atom');
		$obj->save();
		
		$obj2 = Securityzone::load($obj->getId());
		$this->assertEqual($obj2->getTitle(),$obj->getTitle());
		$this->assertEqual($obj2->getUrl(),$obj->getUrl());
		
		$obj2->remove();
	}
}
?>