<?
require_once $basePath.'Editor/Classes/InternalSession.php';

class ImageChooserController {
	
	function getGroupId() {
		return InternalSession::getServiceSessionVar('imagechooser','group',0);
	}
	
	function setGroupId($id) {
		InternalSession::setServiceSessionVar('imagechooser','group',$id);
	}

	function getViewType() {
		return InternalSession::getServiceSessionVar('images','viewtype','lastadded');
	}
	
	function setViewType($value) {
		InternalSession::setServiceSessionVar('images','viewtype',$value);
	}

	function getImageView() {
		return InternalSession::getServiceSessionVar('images','imageview','info');
	}
	
	function setImageView($value) {
		InternalSession::setServiceSessionVar('images','imageview',$value);
	}

	function getViewMode() {
		return InternalSession::getServiceSessionVar('images','viewmode','icon');
	}
	
	function setViewMode($value) {
		if (strlen($value)>0) {
			InternalSession::setServiceSessionVar('images','viewmode',$value);
		}
	}
	
	function getUsedImageIds() {
	    $used = array();
	    $sql = "select image_id as id from part_image".
	    " union select image.object_id as id from imagegallery_object,image".
	        " where imagegallery_object.object_id = image.object_id".
	    " union select image_id as id from person,image".
	        " where person.image_id=image.object_id".
	    " union select image_id as id from product,image".
	        " where product.image_id=image.object_id".
	    " union select image_id as id from imagegallery_object,imagegroup_image".
	        " where imagegroup_image.imagegroup_id = imagegallery_object.object_id";
        $result = Database::select($sql);
        while ($row = Database::next($result)) {
            $used[] = $row['id'];
        }
        Database::free($result);
	    return $used;
	}
	
	function getUnusedImagesCount() {
        $used = ImageChooserController::getUsedImageIds();
    	$sql= "SELECT count(object.id) as num FROM object,image".
    	" where image.object_id=object.id".
    	(count($used)>0 ? " and object.id not in (".implode(",",$used).")" : "").
    	" order by title";
        if ($row = Database::selectFirst($sql)) {
            return $row['num'];
        } else {
            return 0;
        }
	}
	
	function buildSql($type) {
        if ($type=='group') {
        	return "select object.title,object.id,unix_timestamp(object.updated) as updated,".
        	"image.* from object,image,imagegroup_image where object.id=image.object_id".
        	" and imagegroup_image.image_id=object.id and imagegroup_image.imagegroup_id=".
        	ImageChooserController::getGroupId().
        	" order by object.title";
        }
        else if ($type=='nogroup') {
        	return "select object.title,object.id,unix_timestamp(object.updated) as updated,".
        	"image.* from object,image left join imagegroup_image on imagegroup_image.image_id=image.object_id".
        	" where object.id = image.object_id and imagegroup_image.imagegroup_id is null".
        	" order by object.title";
        }
        else if ($type=='all') {
        	return "SELECT object.title,object.id,unix_timestamp(object.updated) as updated,".
        	"image.* FROM object,image where image.object_id=object.id order by title";
        }
		else if ($type=='notused') {
            $used = ImageChooserController::getUsedImageIds();
        	return "SELECT object.title,object.id,unix_timestamp(object.updated) as updated,".
        	"image.* FROM object,image where image.object_id=object.id".
    	    (count($used)>0 ? " and object.id not in (".implode(",",$used).")" : "").
        	" order by title";
        }
		else if ($type=='lastadded') {
        	return "SELECT distinct object.title,object.id,unix_timestamp(object.updated) as updated,image.* from image,object where image.object_id=object.id limit 10 union SELECT object.title,object.id,unix_timestamp(object.updated) as updated,image.* from image,object where image.object_id=object.id and DATE_FORMAT(object.updated,'%Y-%m-%d') = CURRENT_DATE() order by updated desc";
        }
	}
}
?>