<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class EventService {

	/**
	 * Fires an event that can be picked up by others
	 * @param string $event The kind of event (create,update,publish,delete,relation_change)
	 * @param string $type The type of the entity that changed (page,object,part,...)
	 * @param string $subType The sub type of the entity that changed (image,person,document,...)
	 * @param int $id The ID of the entity that changed
	 */
	function fireEvent($event,$type,$subType,$id) {
		if (($event=='publish' || $event=='delete') && $type=='object' && $subType=='image') {
			$sql = "select distinct page_id from document_section,part_image where document_section.part_id=part_image.part_id and part_image.image_id=".$id;
			$result = Database::select($sql);
			while ($row=Database::next($result)) {
				PageService::markChanged($row['page_id']);
			}
			Database::free($result);
		} elseif (($event=='publish' || $event=='delete') && $type=='object' && $subType=='person') {
			$sql = "select distinct page_id from document_section,part_person where document_section.part_id=part_person.part_id and part_person.person_id=".$id;
			$result = Database::select($sql);
			while ($row=Database::next($result)) {
				PageService::markChanged($row['page_id']);
			}
			Database::free($result);
		} elseif (($event=='publish' || $event=='delete' || $event=='update') && $type=='object' && $subType=='file') {
			$sql = "select distinct page_id from document_section,part_file where document_section.part_id=part_file.part_id and part_file.file_id=".$id;
			$result = Database::select($sql);
			while ($row=Database::next($result)) {
				PageService::markChanged($row['page_id']);
			}
			Database::free($result);
		}
		if ($type=='hierarchy') {
			$sql = "update page,template set page.changed=now() where page.template_id=template.id and template.unique='sitemap'";
			Database::update($sql);
		}
		if ($type=='object' && $subType=='image' && ($event=='publish' || $event=='delete')) {
			// Update persons and news with images	
		    $sql = "update object,person set object.updated=now() where object.id=person.object_id and image_id=".$id;
		    Database::update($sql);
		    $sql = "update object,news set object.updated=now() where object.id=news.object_id and news.image_id=".$id;
		    Database::update($sql);
		} 
		else if ($type=='page' && $event=='update') {
			// Mark objects changed if they link to a changed page
		    $sql = "update object,object_link set object.updated=now() where object.id=object_link.object_id and object_link.target_type='page' and object_link.target_value=".Database::text($id);
		    Database::update($sql);
			
			// Mark hierarchy changed
			Hierarchy::markHierarchyOfPageChanged($id);
			
			// Mark pages that link to it as changed
			$sql = "select page_id from link where target_type='page' and target_id=".Database::int($id);
			$result = Database::select($sql);
			while ($row = Database::next($result)) {
				PageService::markChanged($row['page_id']);
			}
			Database::free($result);
		}
		else if ($type=='page' && $event=='delete') {
			// Remove special pages if page is deleted
		    $sql = "delete from specialpage where page_id=".Database::int($id);
		    Database::delete($sql);
		    $sql = "update page set next_page=0 where next_page=".Database::int($id);
		    Database::update($sql);
		    $sql = "update page set previous_page=0 where next_page=".Database::int($id);
		    Database::update($sql);
		}
		
		else if ($type=='object' && $subType=='imagegroup' && $event=='relation_change') {
		    $sql = "update object,design_parameter set object.updated=now() where object.id=design_parameter.design_id and design_parameter.type='images' and design_parameter.value=".$id;
		    Database::update($sql);			
		}
	}
}
?>