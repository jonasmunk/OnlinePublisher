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

$writer = new ItemsWriter();

$writer->startItems();
foreach ($hierarchies as $hierarchy) {
	$title = $hierarchy->getName();
	if ($hierarchy->getChanged()>$hierarchy->getPublished()) {
		$title.=' !';
	}
    $writer->startItem(array(
        'icon' => 'common/hierarchy',
        'value' => $hierarchy->getId(),
        'title' => $title,
        'kind' => 'hierarchy'
    ));
	encodeLevel(0,$hierarchy->getId(),$writer);
    $writer->endItem();
}
$writer->endItems();

function encodeLevel($parent,$hierarchyId,$writer) {
   	$sql="select hierarchy_item.*,page.disabled,page.path,page.id as pageid from hierarchy_item".
    	" left join page on page.id = hierarchy_item.target_id and (hierarchy_item.target_type='page' or hierarchy_item.target_type='pageref')".
    	" where parent=".Database::int($parent).
    	" and hierarchy_id=".Database::int($hierarchyId).
    	" order by `index`";
    $result = Database::select($sql);
    while ($row = Database::next($result)) {
		$icon=Hierarchy::getItemIcon($row['target_type']);
		if ($row['target_type']=='page' && !$row['pageid']) {
			$icon = "common/warning";
		}
        $writer->startItem(array(
            'icon' => $icon,
            'value' => $row['target_id'],
            'title' => $row['title'],
            'kind' => $row['target_type']
        ));
		encodeLevel($row['id'],$hierarchyId,$writer);
		$writer->endItem();
	}
	Database::free($result);
}
?>