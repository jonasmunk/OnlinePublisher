<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Services
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestSchemaService extends UnitTestCase {
    
    function testIt() {
		$obj = new ImagegalleryPart();
	
		$sql = SchemaService::buildSqlColumns(Entity::$schema['ImagegalleryPart']);
		$this->assertEqual($sql,'`variant`,`height`,`width`,`imagegroup_id`,`framed`,`frame`,`show_title`');
		
		$obj->setVariant('block');
		$obj->setHeight(100);
		$obj->setImageGroupId(78);
		$obj->setFramed(false);
		$obj->setShowTitle(true);
		
		$sql = SchemaService::buildSqlValues($obj,Entity::$schema['ImagegalleryPart']);
		$this->assertEqual($sql,"'block',100,0,78,0,'',1");
		
		
		$sql = SchemaService::buildSqlSetters($obj,Entity::$schema['ImagegalleryPart']);
		$this->assertEqual($sql,"`variant`='block',`height`=100,`width`=0,`imagegroup_id`=78,`framed`=0,`frame`='',`show_title`=1");
    }
}
?>