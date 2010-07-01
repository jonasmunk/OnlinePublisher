<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.ProductListing
 */
function getProductListingId() {
	if (isset($_SESSION['template.productlisting.id'])) {
		return $_SESSION['template.productlisting.id'];
	}
	else {
		return -1;
	}
}
	
function setProductListingId($id) {
	$_SESSION['template.productlisting.id']=$id;
}
?>