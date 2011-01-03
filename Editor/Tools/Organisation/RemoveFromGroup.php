<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Request.php';
require_once 'Functions.php';

$id = getPersonGroup();
$persons = Request::getArray('persons');


for ($i=0;$i<count($persons);$i++) {
	$sql="delete from persongroup_person where person_id=".$persons[$i].
	" and persongroup_id=".$id;
	Database::delete($sql);
}

setUpdateHierarchy(true);
redirect('PersonList.php');
?>