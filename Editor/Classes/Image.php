<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');
require_once($basePath.'Editor/Classes/FileSystemUtil.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

Object::$schema['image'] = array(
	'filename'   => array('type'=>'text'),
	'size'  => array('type'=>'int'),
	'width'  => array('type'=>'int'),
	'height'  => array('type'=>'int'),
	'mimetype'  => array('type'=>'text', 'column' => 'type')
);
class Image extends Object {
	var $filename;
	var $size;
	var $width;
	var $height;
	var $mimetype;

	function Image() {
		parent::Object('image');
	}
	
	function getIcon() {
	    return 'Element/Image';
	}
	
	function getIn2iGuiIcon() {
	    return 'common/image';
	}

	function setFilename($filename) {
		$this->filename = $filename;
	}

	function getFilename() {
		return $this->filename;
	}

	function setSize($size) {
		$this->size = $size;
	}

	function getSize() {
		return $this->size;
	}

	function setWidth($width) {
		$this->width = $width;
	}

	function getWidth() {
		return $this->width;
	}

	function setHeight($height) {
		$this->height = $height;
	}

	function getHeight() {
		return $this->height;
	}

	function setMimetype($type) {
		$this->mimetype = $type;
	}

	function getMimetype() {
		return $this->mimetype;
	}

    function clearCache() {
        global $basePath;
        $dir = $basePath.'local/cache/images/';
        $files = FileSystemUtil::listFiles($dir);
        foreach ($files as $file) {
            if (preg_match('/'.$this->id.'[a-z]/i',$file)) {
                @unlink($dir.$file);
            }
        }
    }

	function getGroupIds() {
		$ids = array();
		$sql = "select imagegroup_id from imagegroup_image where image_id=".$this->id;
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$ids[]=$row['imagegroup_id'];
		}
		Database::free($result);
		return $ids;
	}

	function changeGroups($groups) {
		$sql="delete from imagegroup_image where image_id=".$this->id;
		Database::delete($sql);
		foreach ($groups as $id) {
			$sql="insert into imagegroup_image (image_id,imagegroup_id) values (".$this->id.",".$id.")";
			Database::insert($sql);
		}
		foreach ($groups as $id) {
			EventManager::fireEvent('relation_change','object','imagegroup',$id);
		}
	}
	
	function search($options = null) {
		$sql = "select object.id from image,object where object.id=image.object_id order by object.title";
		$result = Database::select($sql);
		$ids = array();
		while ($row = Database::next($result)) {
			$ids[] = $row['id'];
		}
		Database::free($result);
		
		$list = array();
		foreach ($ids as $id) {
			$image = Image::load($id);
			if ($image) {
				$list[] = $image;
			}
		}
		return $list;
	}
	
	function addCustomSearch($query,&$parts) {
		$custom = $query->getCustom();
		if (isset($custom['group'])) {
			$parts['tables'][] = 'imagegroup_image';
			$parts['limits'][] = 'imagegroup_image.image_id=object.id';
			$parts['limits'][] = 'imagegroup_image.imagegroup_id='.$custom['group'];
		}
		if ($custom['unused']===true) {
			$ids = ImageService::getUsedImageids();
			if (count($ids)>0) {
				$parts['limits'][] = 'object.id not in ('.implode(',',$ids).')';
			}
		}
	}

//////////////////////////// Persistence //////////////////////////
	function load($id) {
		return Object::get($id,'image');
/*
		$sql = "select * from image where object_id=".$id;
		$row = Database::selectFirst($sql);
		if ($row) {
			$obj = new Image();
			$obj->_load($id);
			$obj->filename=$row['filename'];
			$obj->size=$row['size'];
			$obj->width=$row['width'];
			$obj->height=$row['height'];
			$obj->mimetype=$row['type'];
			return $obj;
		} else {
			return null;
		}*/
	}
/*
	function sub_create() {
		$sql="insert into image (object_id,filename,size,width,height,type) values (".
		$this->id.
		",".Database::text($this->filename).
		",".Database::int($this->size).
		",".Database::int($this->width).
		",".Database::int($this->height).
		",".Database::text($this->mimetype).
		")";
		Database::insert($sql);
	}

	function sub_update() {
		$sql = "update image set ".
		"filename=".Database::text($this->filename).
		",size=".Database::int($this->size).
		",width=".Database::int($this->width).
		",height=".Database::int($this->height).
		",type=".Database::text($this->mimetype).
		" where object_id=".$this->id;
		Database::update($sql);
	}
*/
	function sub_publish() {
		$data =
		'<image xmlns="'.parent::_buildnamespace('1.0').'">'.
		'<filename>'.StringUtils::escapeXML($this->filename).'</filename>'.
		'<size>'.StringUtils::escapeXML($this->size).'</size>'.
		'<width>'.StringUtils::escapeXML($this->width).'</width>'.
		'<height>'.StringUtils::escapeXML($this->height).'</height>'.
		'<mimetype>'.StringUtils::escapeXML($this->mimetype).'</mimetype>'.
		'</image>';
		return $data;
	}
/*
	function sub_remove() {
        global $basePath;
		@unlink ($basePath.'images/'.$this->filename);
	    $this->clearCache();

		$this->fireRelationChangeEventOnGroups();
		
		$sql="delete from imagegroup_image where image_id=".$this->id;
		Database::delete($sql);
		$sql = "delete from image where object_id=".$this->id;
		Database::delete($sql);
	}
*/	
	function removeMore() {
		@unlink ($basePath.'images/'.$this->filename);
	    $this->clearCache();

		$this->fireRelationChangeEventOnGroups();
		
		$sql="delete from imagegroup_image where image_id=".$this->id;
		Database::delete($sql);		
	}
	
	function fireRelationChangeEventOnGroups() {
		$sql = "select imagegroup_id from imagegroup_image where image_id=".$this->id;
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			EventManager::fireEvent('relation_change','object','imagegroup',$row['imagegroup_id']);
		}
		Database::free($result);
	}
	
}
?>