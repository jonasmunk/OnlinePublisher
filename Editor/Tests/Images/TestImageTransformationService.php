<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.General
 */

class TestImageTransformationService extends UnitTestCase {
	
	function testLoad() {
		global $basePath;
		$image = ImageTransformationService::loadImage($basePath.'Editor/Tests/Resources/jonasmunk.jpg','jpg');
		$this->assertNotNull($image);

		$image = ImageTransformationService::loadImage($basePath.'Editor/Tests/Resources/jonasmunk.jpg');
		$this->assertNotNull($image);

		$image = ImageTransformationService::loadImage($basePath.'Editor/Tests/Resources/jonasmunk.jpg','gif');
		$this->assertNull($image);

		$image = ImageTransformationService::loadImage('');
		$this->assertNull($image);
	}
	
	function testSize() {
		global $basePath;
		$size = ImageTransformationService::getImageInfo($basePath.'Editor/Tests/Resources/jonasmunk.jpg');
		$this->assertEqual($size['width'],548);
		$this->assertEqual($size['height'],448);

		$size = ImageTransformationService::getImageInfo('');
		$this->assertNull($size);

		$this->assertTrue(file_exists($basePath.'Editor/Tests/Resources/twitter.rss'));
		$size = ImageTransformationService::getImageInfo($basePath.'Editor/Tests/Resources/twitter.rss');
		$this->assertNull($size);
	}

	function testType() {
		global $basePath;
		$info = ImageTransformationService::getImageInfo($basePath.'Editor/Tests/Resources/jonasmunk.jpg');
		$this->assertEqual($info['mime'],'image/jpeg');

		$info = ImageTransformationService::getImageInfo($basePath.'Editor/Tests/Resources/logo.png');
		$this->assertEqual($info['mime'],'image/png');
	}

	function testTransform() {
		global $basePath;
		$destination = $basePath.'local/cache/temp/testfile.png';
		$path = $basePath.'Editor/Tests/Resources/jonasmunk.jpg';
		ImageTransformationService::transform(array(
			'path' => $path,
			'destination' => $destination
		));
		$this->assertTrue(file_exists($destination));

		$info = ImageTransformationService::getImageInfo($destination);
		$this->assertEqual($info['width'],548);
		$this->assertEqual($info['height'],448);
		$this->assertEqual($info['mime'],'image/png');

		unlink($destination);
	}

	function testFitInside() {
		$result = ImageTransformationService::fitInside(array('width'=>200,'height'=>300),array('width'=>100,'height'=>100));
		$this->assertEqual($result['height'],100);
		$this->assertEqual($result['width'],67);

		$result = ImageTransformationService::fitInside(array('width'=>2,'height'=>3),array('width'=>100,'height'=>100));
		$this->assertEqual($result['height'],100);
		$this->assertEqual($result['width'],67);

		$result = ImageTransformationService::fitInside(array('width'=>300,'height'=>200),array('width'=>100,'height'=>100));
		$this->assertEqual($result['height'],67);
		$this->assertEqual($result['width'],100);

		$result = ImageTransformationService::fitInside(array('width'=>300,'height'=>200),array('width'=>200,'height'=>100));
		$this->assertEqual($result['height'],100);
		$this->assertEqual($result['width'],150);

		// Rectangular box
		$result = ImageTransformationService::fitInside(array('width'=>300,'height'=>200),array('width'=>200,'height'=>100));
		$this->assertEqual($result['height'],100);
		$this->assertEqual($result['width'],150);

		// Rectangular box (scale up)
		$result = ImageTransformationService::fitInside(array('width'=>30,'height'=>20),array('width'=>200,'height'=>100));
		$this->assertEqual($result['height'],100);
		$this->assertEqual($result['width'],150);

		// Same aspect ratio
		$result = ImageTransformationService::fitInside(array('width'=>400,'height'=>200),array('width'=>200,'height'=>100));
		$this->assertEqual($result['height'],100);
		$this->assertEqual($result['width'],200);
	}
	
	function testCropInside() {
		$result = ImageTransformationService::cropInside(array('width'=>400,'height'=>200),array('width'=>200,'height'=>200));
		$this->assertEqual($result['top'],0);
		$this->assertEqual($result['left'],100);
		$this->assertEqual($result['width'],200);
		$this->assertEqual($result['height'],200);

		$result = ImageTransformationService::cropInside(array('width'=>400,'height'=>200),array('width'=>200,'height'=>400));
		$this->assertEqual($result['top'],0);
		$this->assertEqual($result['left'],150);
		$this->assertEqual($result['width'],100);
		$this->assertEqual($result['height'],200);

		$result = ImageTransformationService::cropInside(array('width'=>500,'height'=>400),array('width'=>200,'height'=>200));
		$this->assertEqual($result['top'],0);
		$this->assertEqual($result['left'],50);
		$this->assertEqual($result['width'],400);
		$this->assertEqual($result['height'],400);
	}
	
	function testTransformScale() {
		global $basePath;
		$destination = $basePath.'local/cache/temp/testfile.jpg';
		$path = $basePath.'Editor/Tests/Resources/jonasmunk.jpg';
		ImageTransformationService::transform(array(
			'path' => $path,
			'destination' => $destination,
			'width' => 200,
			'height' => 200,
			
		));
		$this->assertTrue(file_exists($destination));

		$info = ImageTransformationService::getImageInfo($destination);
		$this->assertEqual($info['width'],200);
		$this->assertEqual($info['height'],200);
		$this->assertEqual($info['mime'],'image/jpeg');

		unlink($destination);
	}
}
?>