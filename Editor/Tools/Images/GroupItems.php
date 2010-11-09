<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Templates.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Request.php';

$writer = new ItemsWriter();

$writer->startItems();
$sql="select distinct object.id,object.title,count(image.object_id) as imagecount from imagegroup, imagegroup_image, image,object  where imagegroup_image.imagegroup_id=imagegroup.object_id and imagegroup_image.image_id = image.object_id and object.id=imagegroup.object_id group by imagegroup.object_id union select object.id,object.title,'0' from object left join imagegroup_image on imagegroup_image.imagegroup_id=object.id where object.type='imagegroup' and imagegroup_image.image_id is null order by title";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$options = array(
		'value'=>$row['id'],
		'title'=>$row['title'],
		'icon'=>'common/folder',
		'kind'=>'imagegroup'
	);
	if ($row['imagecount']>0) {
		$options['badge']=$row['imagecount'];
	}
	$writer->startItem($options)->endItem();
}
Database::free($result);

$writer->endItems();
?>


