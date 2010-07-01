<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.PersonListing
 */
function getPersonListingId() {
	if (isset($_SESSION['template.personlisting.id'])) {
		return $_SESSION['template.personlisting.id'];
	}
	else {
		return -1;
	}
}
	
function setPersonListingId($id) {
	$_SESSION['template.personlisting.id']=$id;
}
?>