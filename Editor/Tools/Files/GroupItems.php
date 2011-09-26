<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Database.php';
require_once '../../Classes/Interface/In2iGui.php';
require_once '../../Classes/Core/Request.php';

$writer = new ItemsWriter();

$writer->startItems();

$sql="select distinct object.id,object.title,count(file.object_id) as filecount from filegroup, filegroup_file, file,object  where filegroup_file.filegroup_id=filegroup.object_id and filegroup_file.file_id = file.object_id and object.id=filegroup.object_id group by filegroup.object_id union select object.id,object.title,'0' from object left join filegroup_file on filegroup_file.filegroup_id=object.id where object.type='filegroup' and filegroup_file.file_id is null order by title";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$options = array(
		'value'=>$row['id'],
		'title'=>$row['title'],
		'icon'=>'common/folder',
		'kind'=>'filegroup'
	);
	if ($row['filecount']>0) {
		$options['badge']=$row['filecount'];
	}
	$writer->startItem($options)->endItem();
}
Database::free($result);

$writer->endItems();
?>


