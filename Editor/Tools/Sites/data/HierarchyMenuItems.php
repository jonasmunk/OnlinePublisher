<?php
/**
 * @package OnlinePublisher
 * @subpackage Sites
 */
require_once '../../../Include/Private.php';

$frameId = Request::getInt('frame');
if ($frameId>0) {
	$frame = Frame::load($frameId);
	$hierarchies = array(Hierarchy::load($frame->getHierarchyId()));
} else {
	$hierarchies = Hierarchy::search();
}

$writer = new ItemsWriter();

$writer->startItems();

foreach ($hierarchies as $hierarchy) {
	$title = $hierarchy->getName();
	if ($hierarchy->getChanged()>$hierarchy->getPublished()) {
		$title.=' !';
	}
	$writer->startItem(array('icon'=>'common/hierarchy','kind'=>'hierarchy','value'=>$hierarchy->getId(),'title'=>$title));
	encodeLevel(0,$hierarchy->getId(),$writer);
	$writer->endItem();
}
$writer->endItems();


function encodeLevel($parent,$hierarchyId,&$writer) {
   	$sql="select hierarchy_item.*,page.disabled,page.path,page.id as pageid from hierarchy_item".
    	" left join page on page.id = hierarchy_item.target_id and (hierarchy_item.target_type='page' or hierarchy_item.target_type='pageref')".
    	" where parent=".Database::int($parent).
    	" and hierarchy_id=".Database::int($hierarchyId).
    	" order by `index`";
    $result = Database::select($sql);
    while ($row = Database::next($result)) {
		$icon = Hierarchy::getItemIcon($row['target_type']);
		if ($row['target_type']=='page' && !$row['pageid']) {
			$icon = "common/warning";
		}
		$writer->startItem(array('icon' => $icon, 'kind' => 'hierarchyItem', 'value' => $row['id'], 'title' => $row['title']));
		encodeLevel($row['id'],$hierarchyId,$writer);
		$writer->endItem();
	}
	Database::free($result);
}
?>