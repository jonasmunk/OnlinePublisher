<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */

function getPersonGroup() {
	if (isset($_SESSION['tools.organisation.person.persongroup'])) {
		return $_SESSION['tools.organisation.person.persongroup'];
	}
	else {
		return -1;
	}
}
	
function setPersonGroup($id) {
	$_SESSION['tools.organisation.person.persongroup']=$id;
}

function setUpdateHierarchy($value) {
	$_SESSION['tools.organisation.person.updateHierarchy']=$value;
}

function getUpdateHierarchy() {
	if (isset($_SESSION['tools.organisation.person.updateHierarchy'])) {
		return $_SESSION['tools.organisation.person.updateHierarchy'];
	}
	else {
		return false;
	}
}

function concatenatePersonName($firstname, $middlename, $surname){
	if(strlen($middlename) > 0){
		$surname = $middlename." ".$surname;
	}
	return $firstname." ".$surname;
}



?>