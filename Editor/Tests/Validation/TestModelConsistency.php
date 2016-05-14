<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Validation
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestModelConsistency extends UnitTestCase {

	function testEntities() {
        $entities = ClassService::getBySuper('Entity');
        foreach ($entities as $entity) {
            ClassService::load($entity);
            if (isset(Entity::$schema[$entity]) && is_array(Entity::$schema[$entity])) {
                
            } else {
                //$this->fail($entity.' has no schema');
            }
        }
    }
}
?>