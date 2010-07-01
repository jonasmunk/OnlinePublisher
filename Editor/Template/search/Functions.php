<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Search
 */
function getSearchId() {
	if (isset($_SESSION['template.search.id'])) {
		return $_SESSION['template.search.id'];
	}
	else {
		return -1;
	}
}
	
function setSearchId($id) {
	$_SESSION['template.search.id']=$id;
}
?>