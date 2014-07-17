<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Services
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestModelService extends UnitTestCase {
	
	function testBuildSelect() {
        $sql = ModelService::buildSelect('Image');
        $expected = 'select `object`.`id` as object_id,`object`.`title` as object_title,UNIX_TIMESTAMP(`object`.`created`) as object_created,UNIX_TIMESTAMP(`object`.`updated`) as object_updated,UNIX_TIMESTAMP(`object`.`published`) as object_published,`object`.`type` as object_type,`object`.`note` as object_note,`object`.`searchable` as object_searchable,`object`.`owner_id` as object_owner_id,`image`.`filename` as image_filename,`image`.`size` as image_size,`image`.`width` as image_width,`image`.`height` as image_height,`image`.`type` as image_type from `object`,`image` where object.id = image.object_id and `object`.`id` = @int(id)';
		$this->assertEqual($expected,$sql);
	}

	function testLoad() {
        $image = new Image();
        $image->setTitle('My image');
        $image->setFilename('my_image.png');
        $image->setSize(1024);
        $image->save();
        
        
        $loaded = ModelService::load('Image',$image->getId());
		$this->assertEqual($image->getTitle(),$loaded->getTitle());
		$this->assertEqual($image->getSize(),$loaded->getSize());
		$this->assertEqual($image->getFilename(),$loaded->getFilename());
		$this->assertEqual('integer',gettype($loaded->getId()));
		$this->assertEqual('integer',gettype($loaded->getCreated()));
        
        $image->remove();
	}
}
?>