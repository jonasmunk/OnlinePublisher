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
	
		$sql = SchemaService::buildSqlColumns(Part::$schema['imagegallery']);
		$this->assertEqual($sql,'`variant`,`height`,`imagegroup_id`,`framed`,`show_title`');
		
		$obj->setVariant('block');
		$obj->setHeight(100);
		$obj->setImageGroupId(78);
		$obj->setFramed(false);
		$obj->setShowTitle(true);
		
		$sql = SchemaService::buildSqlValues($obj,Part::$schema['imagegallery']);
		$this->assertEqual($sql,"'block',100,78,0,1");
		
		
		$sql = SchemaService::buildSqlSetters($obj,Part::$schema['imagegallery']);
		$this->assertEqual($sql,"`variant`='block',`height`=100,`imagegroup_id`=78,`framed`=0,`show_title`=1");
    }
}
?>