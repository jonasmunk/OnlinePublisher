<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.ImageGallery
 */
function getImageGalleryId() {
	if (isset($_SESSION['template.imagegallery.id'])) {
		return $_SESSION['template.imagegallery.id'];
	}
	else {
		return -1;
	}
}
	
function setImageGalleryId($id) {
	$_SESSION['template.imagegallery.id']=$id;
}
?>