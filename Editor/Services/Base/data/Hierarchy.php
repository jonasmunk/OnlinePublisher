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

$icons = array('page'=>'common/page','pageref'=>'common/pagereference','email'=>'common/email','url'=>'monochrome/globe','file'=>'monochrome/file');

header('Content-Type: text/xml;');
echo '<?xml version="1.0"?>
<items>';
foreach ($hierarchies as $hierarchy) {
	$title = $hierarchy->getName();
	if ($hierarchy->getChanged()>$hierarchy->getPublished()) {
		$title.=' !';
	}
	echo '<item icon="common/hierarchy" value="'.$hierarchy->getId().'" title="'.StringUtils::escapeXML($title).'" kind="hierarchy">';
	encodeLevel(0,$hierarchy->getId());
	echo '</item>';
}
echo '</items>';


function encodeLevel($parent,$hierarchyId) {
   	$sql="select hierarchy_item.*,page.disabled,page.path,page.id as pageid from hierarchy_item".
    	" left join page on page.id = hierarchy_item.target_id and (hierarchy_item.target_type='page' or hierarchy_item.target_type='pageref')".
    	" where parent=".$parent.
    	" and hierarchy_id=".$hierarchyId.
    	" order by `index`";
    $result = Database::select($sql);
    while ($row = Database::next($result)) {
		$icon=Hierarchy::getItemIcon($row['target_type']);
		if ($row['target_type']=='page' && !$row['pageid']) {
			$icon = "common/warning";
		}
		echo '<item icon="'.$icon.'" value="'.$row['target_id'].'" title="'.StringUtils::escapeXML($row['title']).'" kind="'.$row['target_type'].'">';
		encodeLevel($row['id'],$hierarchyId);
		echo '</item>';
	}
	Database::free($result);
}
?>