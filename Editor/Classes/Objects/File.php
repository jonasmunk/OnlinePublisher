<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
*/

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Object::$schema['file'] = array(
	'filename'	=> array('type'=>'string'),
	'size'		=> array('type'=>'int'),
	'mimetype'	=> array('type'=>'string','column'=>'type')
);
class File extends Object {
	var $filename;
	var $size;
	var $mimetype;

	function File() {
		parent::Object('file');
	}
	
	function getIcon() {
        return "file/generic";
	}

	function load($id) {
		return Object::get($id,'file');
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

	function setMimetype($type) {
		$this->mimetype = $type;
	}

	function getMimetype() {
		return $this->mimetype;
	}
	
	function sub_publish() {
		$data =
		'<file xmlns="'.parent::_buildnamespace('1.0').'">'.
		'<filename>'.Strings::escapeXML($this->filename).'</filename>'.
		'<size>'.Strings::escapeXML($this->size).'</size>'.
		'<mimetype>'.Strings::escapeXML($this->mimetype).'</mimetype>'.
		'</file>';
		return $data;
	}

	function removeMore() {
		global $basePath;
		$path = $basePath.'files/'.$this->filename;
		if (file_exists($path)) {
			!@unlink ($path);
		}
		$sql="delete from filegroup_file where file_id=".Database::int($this->id);
		Database::delete($sql);
	}
	
	/***************** Groups ****************/
	
	function getGroupIds() {
		$sql = "select filegroup_id as id from filegroup_file where file_id=".Database::int($this->id);
		return Database::getIds($sql);
	}
	
	function updateGroupIds($ids) {
		$ids = ObjectService::getValidIds($ids);
		$sql = "delete from filegroup_file where file_id=".Database::int($this->id);
		Database::delete($sql);
		foreach ($ids as $id) {
			$sql = "insert into filegroup_file (filegroup_id,file_id) values (".Database::int($id).",".Database::int($this->id).")";
			Database::insert($sql);
		}
	}
	
	function addGroupId($id) {
		$sql = "delete from filegroup_file where file_id=".Database::int($this->id)." and filegroup_id=".Database::int($id);
		Database::delete($sql);
		$sql = "insert into filegroup_file (filegroup_id,file_id) values (".Database::int($id).",".Database::int($this->id).")";
		Database::insert($sql);
	}
	
	/********************** Search *****************/
	
	
	function addCustomSearch($query,&$parts) {
		$custom = $query->getCustom();
		if (isset($custom['group'])) {
			$parts['tables'][] = 'filegroup_file';
			$parts['limits'][] = 'filegroup_file.file_id=object.id';
			$parts['limits'][] = 'filegroup_file.filegroup_id='.$custom['group'];
		}
	}
	
    function find($query = array()) {
    	$parts = array();
		$parts['columns'] = 'object.id';
		$parts['tables'] = 'file,object';
		$parts['limits'] = array();
		$parts['ordering'] = 'object.title';
		$parts['direction'] = $query['direction'];
		
		$parts['limits'][] = "object.id=file.object_id";
		if (isset($query['filegroup'])) {
			$parts['tables'].=",filegroup_file";
			$parts['limits'][] = "filegroup_file.file_id = object.id";
			$parts['limits'][] = "filegroup_file.filegroup_id=".$query['filegroup'];
		}
		if (isset($query['type'])) {
			$parts['limits'][]='`file`.`type` = '.Database::text($query['type']);
		}
		if (is_array($query['mimetypes'])) {
			$ors = array();
			foreach ($query['mimetypes'] as $type) {
				$ors[]='`file`.`type` = '.Database::text($type);
			}
			$parts['limits'][]='('.implode(' or ',$ors).')';
		}
		if (isset($query['query'])) {
			$parts['limits'][]='`object`.`index` like '.Database::search($query['query']);
		}
		if (isset($query['createdMin'])) {
			$parts['limits'][]='`object`.`created` > '.Database::datetime($query['createdMin']);
		}
		if ($query['sort']=='title') {
			$parts['ordering']="object.title";
		}
		$list = ObjectService::_find($parts,$query);
		$list['result'] = array();
		foreach ($list['rows'] as $row) {
			$list['result'][] = File::load($row['id']);
		}
		return $list;
	}
	
	function getTypeCounts() {
		$sql="select type,count(object_id) as count from file group by type order by type";
		return Database::selectAll($sql);
	}
		
}
?>