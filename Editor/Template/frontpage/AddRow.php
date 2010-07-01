<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.FrontPage
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';

require_once 'Functions.php';

$id = getPageId();
$position = requestGetNumber('position',0);


$sql="select * from frontpage_row where page_id=".$id." and position>=".$position;
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$sql="update frontpage_row set position=".($row['position']+1)." where id=".$row['id'];
	Database::update($sql);
}
Database::free($result);

$sql="insert into frontpage_row (page_id,position) values (".$id.",".$position.")";
$rowId=Database::insert($sql);

$cols = getColumnCount();
for ($i=1;$i<=$cols;$i++) {
	$sql="insert into frontpage_cell (page_id,row_id,position) values (".$id.",".$rowId.",".$i.")";
	Database::insert($sql);
}
$sql="update page set changed=now() where id=".$id;
Database::update($sql);


redirect('Editor.php');
?>