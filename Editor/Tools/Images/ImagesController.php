<?
require_once $basePath.'Editor/Include/Session.php';

class ImagesController {
	
	function getGroupId() {
		if (isset($_SESSION['tools.images.group'])) {
			return $_SESSION['tools.images.group'];
		}
		else {
			return -1;
		}
	}
	
	function setGroupId($id) {
		$_SESSION['tools.images.group']=$id;
	}

	function getViewType() {
		return getToolSessionVar('images','viewtype','lastadded');
	}
	
	function setViewType($value) {
		setToolSessionVar('images','viewtype',$value);
	}

	function getImageView() {
		return getToolSessionVar('images','imageview','info');
	}
	
	function setImageView($value) {
		setToolSessionVar('images','imageview',$value);
	}

	function getViewMode() {
		return getToolSessionVar('images','viewmode','icon');
	}
	
	function setViewMode($value) {
		if (strlen($value)>0) {
			setToolSessionVar('images','viewmode',$value);
		}
	}
	
	function setUpdateHierarchy($value) {
		$_SESSION['tools.images.updateHierarchy']=$value;
	}

	function getUpdateHierarchy() {
		if (isset($_SESSION['tools.images.updateHierarchy'])) {
			return $_SESSION['tools.images.updateHierarchy'];
		}
		else {
			return false;
		}
	}
	
	function getBaseWindow() {
		$type = ImagesController::getViewType();
		if ($type=='group') {
			return 'Group.php';
		} elseif ($type=='nogroup') {
			return 'NoGroup.php';
		} elseif ($type=='groups') {
			return 'Groups.php';
		} elseif ($type=='notused') {
			return 'NotUsed.php';
		} elseif ($type=='lastadded') {
			return 'LastAdded.php';
		} else {
			return 'LastAdded.php';
		}
	}
	
	function getBaseTitle() {
		$type = ImagesController::getViewType();
		if ($type=='group') {
			return 'Grouppe';
		} elseif ($type=='nogroup') {
			return 'Billeder ikke i gruppe';
		} elseif ($type=='groups') {
			return 'Oversigt over grupper';
		} elseif ($type=='notused') {
			return 'Billeder ikke i brug';
		} elseif ($type=='lastadded') {
			return 'Seneste billeder';
		} else {
			return 'Alle billeder';
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
        $used = ImagesController::getUsedImageIds();
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
        	ImagesController::getGroupId().
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
            $used = ImagesController::getUsedImageIds();
        	return "SELECT object.title,object.id,unix_timestamp(object.updated) as updated,".
        	"image.* FROM object,image where image.object_id=object.id".
    	    (count($used)>0 ? " and object.id not in (".implode(",",$used).")" : "").
        	" order by title";
        }
		else if ($type=='lastadded') {
        	return "SELECT distinct object.title,object.id,unix_timestamp(object.updated) as updated,image.* from image,object where image.object_id=object.id limit 10 union SELECT object.title,object.id,unix_timestamp(object.updated) as updated,image.* from image,object where image.object_id=object.id and DATE_FORMAT(object.updated,'%Y-%m-%d') = CURRENT_DATE() order by updated desc";
        }
	}
	
	function getGroups() {
		$groups = array();
		$sql="select title,id from object where type='imagegroup' order by title";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$groups[] = array('id' => $row['id'],'title' => $row['title']);
		}
		Database::free($result);
		return $groups;
	}
}
?>