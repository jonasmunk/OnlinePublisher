<?php
/**
 * @package OnlinePublisher
 * @subpackage Sites
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Hierarchy.php';
require_once '../../Classes/Frame.php';


$frameId = Request::getInt('frame');

$writer = new ItemsWriter();

$writer->startItems();
if ($frameId>0) {
	$frame = Frame::load($frameId);
	$hierarchy = Hierarchy::load($frame->getHierarchyId());
	$writer->startItem(array(
		'icon'=>'common/hierarchy',
		'value'=>$hierarchy->getId(),
		'title'=>$hierarchy->getName(),
		'kind'=>'hierarchy'
	));
	encodeLevel(0,$hierarchy->getId(),$writer);
	$writer->endItem();
}
$writer->endItems();

function encodeLevel($parent,$hierarchyId,&$writer) {
   	$sql="select hierarchy_item.*,page.disabled,page.path from hierarchy_item".
    	" left join page on page.id = hierarchy_item.target_id and (hierarchy_item.target_type='page' or hierarchy_item.target_type='pageref')".
    	" where parent=".$parent.
    	" and hierarchy_id=".$hierarchyId.
    	" order by `index`";
    $result = Database::select($sql);
    while ($row = Database::next($result)) {
		$writer->startItem(array(
			'icon'=>'common/page',
			'value'=>$row['id'],
			'title'=>$row['title'],
			'kind'=>'hierarchyItem'
		));
		encodeLevel($row['id'],$hierarchyId,$writer);
		$writer->endItem();
	}
	Database::free($result);
}
?>