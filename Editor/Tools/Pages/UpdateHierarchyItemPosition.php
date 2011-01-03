<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/InternalSession.php';

require_once 'Functions.php';

$id = requestGetNumber('id');
$newParent = requestGetNumber('newParent');
$return = requestGetText('return');


// Get info about hierarchy item
$sql="select * from hierarchy_item where id=".$id;
$row = Database::selectFirst($sql);
$hierarchyId=$row['hierarchy_id'];
$parent=$row['parent'];

// Find largest position of items under new parent
$sql="select max(`index`) as `index` from hierarchy_item where parent=".$newParent." and hierarchy_id=".$hierarchyId;
if ($row = Database::selectFirst($sql)) {
    $newIndex = $row['index']+1;
} else {
    $newIndex = 1;
}

// Change position to new position
$sql="update hierarchy_item set parent=".$newParent.",`index`=".$newIndex." where id=".$id;
Database::update($sql);

// Fix positions of old parent
$sql="select id from hierarchy_item where parent=".$parent." and hierarchy_id=".$hierarchyId." order by `index`";
$result = Database::select($sql);
$index=1;
while ($row = Database::next($result)) {
	$sql="update hierarchy_item set `index`=".$index." where id=".$row['id'];
	Database::update($sql);
	$index++;
}
Database::free($result);


InternalSession::setToolSessionVar('pages','updateHier',true);
redirect('EditHierarchyItem.php?id='.$id.'&return='.urlencode($return));
?>