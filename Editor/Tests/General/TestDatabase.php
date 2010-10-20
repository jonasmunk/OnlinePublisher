<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.General
 */

class TestDatabase extends UnitTestCase {
	
	function testIt() {
		$query = array(
			'table' => 'frame',
			'values' => array(
				'title' => Database::text('my title'),
				'name' => Database::text('my name'),
				'hierarchy_id' => Database::int('9')
			),
			'where' => array( 'id' => '89')
		);
		
		$sql = Database::buildUpdateSql($query);
		$this->assertEqual($sql,"update frame set `title`='my title',`name`='my name',`hierarchy_id`=9 where `id`=89");
		
		$sql = Database::buildInsertSql($query);
		$this->assertEqual($sql,"insert into frame (`title`,`name`,`hierarchy_id`) values ('my title','my name',9)");
	}
}