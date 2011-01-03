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
	echo '<item icon="common/hierarchy" value="'.$hierarchy->getId().'" title="'.In2iGui::escape($hierarchy->getName()).'" kind="hierarchy">';
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
		echo '<item icon="'.$icon.'" value="'.$row['id'].'" title="'.In2iGui::escape($row['title']).'" kind="hierarchyItem">';
		encodeLevel($row['id'],$hierarchyId);
		echo '</item>';
	}
	Database::free($result);
}
?>