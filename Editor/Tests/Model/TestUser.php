<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

class TestUser extends AbstractObjectTest {
    
	function TestUser() {
		parent::AbstractObjectTest('user');
	}

	function testProperties() {
		$obj = new User();
		$obj->setTitle('Jonas Munk');
		$obj->save();
		
		$obj2 = User::load($obj->getId());
		$this->assertEqual($obj2->getTitle(),$obj->getTitle());
		
		$obj2->remove();
	}
	
	function testAuthentication() {
		$username = "u".time();
		$obj = new User();
		$obj->setTitle('Testo Samplo');
		$obj->setUsername($username);
		$obj->setPassword(AuthenticationService::encryptPassword('$ecret'));
		$obj->setSecure(true);
		$obj->save();
		
		$this->assertNull(AuthenticationService::getUser('dsfgashfsahdfghaj','fsdhagdfjhgdfjhgfja'));

		$found = AuthenticationService::getUser($username,'$ecret');
		$this->assertNotNull($found);
		if ($found) {
			$this->assertEqual($obj->getId(),$found->getId());
		}

		// test internal / external
		$this->assertNull(AuthenticationService::getExternalUser($username,'$ecret'));
		$this->assertNull(AuthenticationService::getInternalUser($username,'$ecret'));
		
		$obj->setInternal(true);
		$obj->save();
		$this->assertNotNull(AuthenticationService::getInternalUser($username,'$ecret'));
				
		$obj->setInternal(false);
		$obj->setExternal(true);
		$obj->save();
		$this->assertNotNull(AuthenticationService::getExternalUser($username,'$ecret'));

		$obj->remove();
	}
	
	function testEnsureSecure() {
		$username = "2u".time();
		$obj = new User();
		$obj->setTitle('Testo Samplo');
		$obj->setUsername($username);
		$obj->setPassword('$ecret');
		$obj->setSecure(false);
		$obj->save();
		
		$loaded = User::load($obj->getId());
		$this->assertFalse($loaded->getSecure());
		
		AuthenticationService::ensureSecurity();
		
		$loaded = User::load($obj->getId());
		$this->assertTrue($loaded->getSecure());
		
		$obj->remove();
	}
	
	function testGetUserByEmailOrUsername() {
		$obj = new User();
		$obj->setTitle('Testo Samplo');
		$obj->setUsername('testatron');
		$obj->setEmail('test@mydomain.com');
		$obj->save();
		
		$byUsername = AuthenticationService::getUserByEmailOrUsername('testatron');
		$this->assertNotNull($byUsername);
		Log::debug($byUsername);
		$this->assertEqual($obj->getId(),$byUsername->getId());
		
		$byEmail = AuthenticationService::getUserByEmailOrUsername('test@mydomain.com');
		$this->assertNotNull($byEmail);
		$this->assertEqual($obj->getId(),$byEmail->getId());
		
		$obj->remove();
	}
}
?>