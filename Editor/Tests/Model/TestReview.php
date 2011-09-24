<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestReview extends AbstractObjectTest {
    
	function TestReview() {
		parent::AbstractObjectTest('review');
	}

	function testProperties() {
		$date = DateUtils::parse('15-04-1980');
		$this->assertNotNull($date);
		$obj = new Review();
		$obj->setTitle('My review');
		$obj->setAccepted(true);
		$obj->setDate($date);
		$obj->save();
		
		$obj2 = Review::load($obj->getId());
		$this->assertEqual($obj2->getTitle(),'My review');
		$this->assertEqual($obj2->getAccepted(),true);
		$this->assertEqual($obj2->getDate(),$date);
		
		$obj2->remove();
		
		$this->assertFalse(Review::load($obj->getId()));
		
	}
	
	function testRelations() {
		$page = TestService::createTestPage();
		
		$user = new User();
		$user->setUsername('testReview');
		$user->save();
		
		$review = new Review();
		$review->setTitle('My review');
		$review->setAccepted(true);
		$review->setDate(DateUtils::parse('15-04-1980'));
		$review->save();
		
		RelationsService::relatePageToObject($page,$review,'reviewed');
		RelationsService::relateObjectToObject($review,$user,'reviewer');
		
		$reviews = Query::after('review')->withRelationTo($user,'reviewer')->withRelationFromPage($page,'reviewed')->get();
		$this->assertTrue(count($reviews)==1);
		
		TestService::removeTestPage($page);
		
		$user->remove();
		$review->remove();
	}
}
?>