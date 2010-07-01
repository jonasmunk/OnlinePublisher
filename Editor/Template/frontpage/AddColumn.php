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

$sql="select frontpage_row.id,max(frontpage_cell.position) as position from frontpage_row left join frontpage_cell on frontpage_cell.row_id= frontpage_row.id where frontpage_row.page_id=".$id." group by frontpage_row.id";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	if ($row['position']=='')
		$position = 0;
	else
		$position = $row['position'];
	$sql="insert into frontpage_cell (page_id,position,row_id) values (".$id.",".($position+1).",".$row['id'].")";
	Database::insert($sql);
}
Database::free($result);


redirect('Editor.php');
?>