<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.FrontPage
 */
if (($event=='publish' || $event=='delete') && $type=='object' && $subType=='image') {
	$sql = "select distinct page_id from frontpage_section,part_image where frontpage_section.part_id=part_image.part_id and part_image.image_id=".$id;
	$result = Database::select($sql);
	while ($row=Database::next($result)) {
		$sql = "update page set changed=now() where id=".$row['page_id'];
		Database::update($sql);
	}
	Database::free($result);
} elseif (($event=='publish' || $event=='delete') && $type=='object' && $subType=='person') {
	$sql = "select distinct page_id from frontpage_section,part_person where frontpage_section.part_id=part_person.part_id and part_person.person_id=".$id;
	$result = Database::select($sql);
	while ($row=Database::next($result)) {
		$sql = "update page set changed=now() where id=".$row['page_id'];
		Database::update($sql);
	}
	Database::free($result);
}
?>