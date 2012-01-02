<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Utilities
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestSelectBuilder extends UnitTestCase {

	function testBasic() {
		$sql = new SelectBuilder();
		$sql->addTable('person')->addTable('photo');
		$sql->addColumn('person.id')->addColumn('photo.name as photo_name');
		
		$this->assertEqual($sql->toSQL(),'select person.id,photo.name as photo_name from person,photo');
	}
	
	function testLimits() {
		$sql = new SelectBuilder();
		$sql->addTable('person')->addTable('photo');
		$sql->addColumn('person.id')->addColumn('photo.name as photo_name');

		$sql->addLimit('person.photo_id=photo.id');
		$sql->addOrdering('person.name');
		$sql->addOrdering('photo.title',true);
		
		$sql->setFrom(5)->setTo(10);
		
		$this->assertEqual($sql->toSQL(),'select person.id,photo.name as photo_name from person,photo where person.photo_id=photo.id order by person.name asc,photo.title desc limit 5,10');
	}

}