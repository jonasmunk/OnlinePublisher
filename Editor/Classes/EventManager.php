<?

class EventManager {
	
	function EventManager() {
	}
	
	/**
	 * Fires an event that can be picked up by others
	 * @param string $event The kind of event (create,update,publish,delete,relation_change)
	 * @param string $type The type of the entity that changed (page,object,part,...)
	 * @param string $subType The sub type of the entity that changed (image,person,document,...)
	 * @param int $id The ID of the entity that changed
	 */
	function fireEvent($event,$type,$subType,$id) {
		global $basePath;
		require($basePath.'Editor/Template/document/Events.php');
		require($basePath.'Editor/Template/frontpage/Events.php');
		require($basePath.'Editor/Template/sitemap/Events.php');
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
		}
		else if ($type=='page' && $event=='delete') {
			// Remove special pages if page is deleted
		    $sql = "delete from specialpage where page_id=".$id;
		    Database::delete($sql);
		    $sql = "update page set next_page=0 where next_page=".$id;
		    Database::update($sql);
		    $sql = "update page set previous_page=0 where next_page=".$id;
		    Database::update($sql);
		}
		
		else if ($type=='object' && $subType=='imagegroup' && $event=='relation_change') {
		    $sql = "update object,design_parameter set object.updated=now() where object.id=design_parameter.design_id and design_parameter.type='images' and design_parameter.value=".$id;
		    Database::update($sql);			
		}
	}
}
?>