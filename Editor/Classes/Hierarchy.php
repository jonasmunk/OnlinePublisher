<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/EventManager.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');
require_once($basePath.'Editor/Classes/Services/FileService.php');
require_once($basePath.'Editor/Classes/Services/CacheService.php');

class Hierarchy {
    
    var $id;
    var $name;
    var $language;
	var $changed;
	var $published;
    
    function Hierarchy() {
        
    }
    
    function setId($id) {
        $this->id = $id;
    }

    function getId() {
        return $this->id;
    }
    
    function setName($name) {
        $this->name = $name;
    }

    function getName() {
        return $this->name;
    }
    
    function setLanguage($language) {
        $this->language = $language;
    }

    function getLanguage() {
        return $this->language;
    }

	function setChanged($changed) {
	    $this->changed = $changed;
	}

	function getChanged() {
	    return $this->changed;
	}
	
	function setPublished($published) {
	    $this->published = $published;
	}

	function getPublished() {
	    return $this->published;
	}
	
	
    
    //////////////////// Special ////////////////////
    
    function canDelete() {
        $out = false;
        $sql="select count(id) as num from hierarchy_item where hierarchy_id=".Database::int($this->id);
        if ($row = Database::selectFirst($sql)) {
            if ($row['num']==0) {
                $out = true;
            }
        }
        return $out;
    }
    
    ////////////////// Persistence //////////////////
    
    function load($id) {
        $sql = "select id,name,language,UNIX_TIMESTAMP(changed) as changed,UNIX_TIMESTAMP(published) as published from hierarchy where id=".Database::int($id);
        if ($row = Database::selectFirst($sql)) {
            return Hierarchy::_populate($row);
        } else {
            return false;
        }
    }
	
	function loadFromItemId($id) {
		$sql="select hierarchy_id from hierarchy_item where id=".Database::int($id);
		if ($row = Database::selectFirst($sql)) {
			return Hierarchy::load($row['hierarchy_id']);
		}
		return null;		
	}
    
    function _populate(&$row) {
        $hier = new Hierarchy();
        $hier->setId($row['id']);
        $hier->setName($row['name']);
        $hier->setLanguage($row['language']);
        $hier->setPublished($row['published']);
        $hier->setChanged($row['changed']);
        return $hier;        
    }
    
    function search() {
        $out = array();
        $sql = "select id,name,language,UNIX_TIMESTAMP(changed) as changed,UNIX_TIMESTAMP(published) as published from hierarchy order by name";
        $result = Database::select($sql);
        while ($row = Database::next($result)) {
            $out[] = Hierarchy::_populate($row);
        }
        Database::free($result);
        return $out;
    }
	
	function create() {
        $data='<hierarchy xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/hierarchy/1.0/"/>';

		$sql = "insert into hierarchy (name,language,data,changed,published) values (".
		Database::text($this->name).",".
		Database::text($this->language).",".
		Database::text($data).",".
		"now(),now()".
		")";		
		$this->id = Database::insert($sql);
    }
    
    function delete() {
        if ($this->canDelete()) {
            $sql='delete from hierarchy where id='.Database::int($this->id);
            return Database::delete($sql);
        } else {
			Log::debug('The hierarchy cannot be deleted');
            return false;
        }
    }

	function save() {
		if ($this->id>0) {
			$this->update();
		} else {
			$this->create();
		}
	}
    
    function update() {
        $sql="update hierarchy set ".
        "name=".Database::text($this->name).
        ",language=".Database::text($this->language).
        " where id=".$this->id;
        return Database::update($sql);
    }

	function createItemForPage($pageId,$title,$parentId) {
		
		// find index
		$sql="select max(`index`) as `index` from hierarchy_item where parent=".Database::int($parentId)." and hierarchy_id=".Database::int($this->id);
		if ($row = Database::selectFirst($sql)) {
			$index=$row['index']+1;
		} else {
			$index=1;
		}
		
		$sql="insert into hierarchy_item (title,type,hierarchy_id,parent,`index`,target_type,target_id) values (".
		Database::text($title).
		",'item'".
		",".Database::int($this->id).
		",".Database::int($parentId).
		",".Database::int($index).
		",'page'".
		",".Database::int($pageId).
		")";
		Database::insert($sql);
	}

	function createItem($options) {
		if (StringUtils::isBlank($options['title'])) {
			Log::debug('No title');
			return false;
		}
		if (!in_array($options['targetType'],array('page','pageref','file','email','url'))) {
			Log::debug('Invalid targetType');
			return false;
		}
		if (!isset($options['hidden'])) {
			Log::debug('hidden not set');
			return false;
		}
		if (!isset($options['targetValue'])) {
			Log::debug('targetValue not set');
			return false;
		}
		if (!isset($options['parent'])) {
			Log::debug('parent not set');
			return false;
		}
		if ($options['parent']>0) {
			$sql="select id from hierarchy_item where id=".Database::int($options['parent'])." and hierarchy_id=".Database::int($this->id);
			if (!$row = Database::selectFirst($sql)) {
				Log::debug('parent not found');
				return false;
			}
		}
		// find index
		$sql="select max(`index`) as `index` from hierarchy_item where parent=".Database::int($options['parent'])." and hierarchy_id=".Database::int($this->id);
		if ($row = Database::selectFirst($sql)) {
			$index=$row['index']+1;
		} else {
			$index=1;
		}
		
		if ($options['targetType']=='page' || $options['targetType']=='pageref' || $options['targetType']=='file') {
			$target_id = $options['targetValue'];
		} else {
			$target_value = $options['targetValue'];
		}
		
		$sql="insert into hierarchy_item (title,hidden,type,hierarchy_id,parent,`index`,target_type,target_id,target_value) values (".
		Database::text($options['title']).
		",".Database::boolean($options['hidden']).
		",'item'".
		",".Database::int($this->id).
		",".Database::int($options['parent']).
		",".Database::int($index).
		",".Database::text($options['targetType']).
		",".Database::int($target_id).
		",".Database::text($target_value).
		")";
		Log::debug($sql);
		Database::insert($sql);
		return true;
	}

	function markChanged() {
		$sql="update hierarchy set changed=now() where id=".Database::int($this->id);
		Database::update($sql);
	}
	
    /////////////////// Publishing //////////////////
    
    function publish($allowDisabled=false) {
	    $data = $this->build($this->id,$allowDisabled);
	    $sql="update hierarchy set published=now(),data=".Database::text($data)." where id=".Database::int($this->id);
	    Database::update($sql);
		CacheService::clearCompletePageCache();
    }
    
    // Static!
    function build($id,$allowDisabled=true) {
    	return '<hierarchy xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/hierarchy/1.0/">'.
    	Hierarchy::hierarchyTraveller($id,0,$allowDisabled).
    	'</hierarchy>';
    }

    // Static
    function hierarchyTraveller($id,$parent,$allowDisabled) {
    	$output="";
    	$sql="select hierarchy_item.*,page.disabled,page.path from hierarchy_item".
    	" left join page on page.id = hierarchy_item.target_id and (hierarchy_item.target_type='page' or hierarchy_item.target_type='pageref')".
    	" where parent=".Database::int($parent).
    	" and hierarchy_id=".Database::int($id).
    	" order by `index`";
    	$result = Database::select($sql);
    	while ($row = Database::next($result)) {
    	    if ($row['disabled']!=1 || $allowDisabled) {
        		$output.='<item title="'.StringUtils::escapeXML($row['title']).
        					'" alternative="'.StringUtils::escapeXML($row['alternative']).'"';
        		if ($row['target_type']=='page') {
        			$output.=' page="'.$row['target_id'].'"';
					if (strlen($row['path'])>0) {
        				$output.=' path="'.StringUtils::escapeXML($row['path']).'"';
					}
        		}
        		if ($row['target_type']=='pageref') {
        			$output.=' page-reference="'.$row['target_id'].'"';
					if (strlen($row['path'])>0) {
        				$output.=' path="'.StringUtils::escapeXML($row['path']).'"';
					}
        		}
        		else if ($row['target_type']=='file') {
        			$output.=' file="'.$row['target_id'].'" filename="'.StringUtils::escapeXML(FileService::getFileFilename($row['target_id'])).'"';
        		}
        		else if ($row['target_type']=='url') {
        			$output.=' url="'.StringUtils::escapeXML($row['target_value']).'"';
        		}
        		else if ($row['target_type']=='email') {
        			$output.=' email="'.StringUtils::escapeXML($row['target_value']).'"';
        		}
        		if ($row['target']!='') {
        			$output.=' target="'.StringUtils::escapeXML($row['target']).'"';
        		}
        		if ($row['hidden']) {
        			$output.=' hidden="true"';
        		}
        		$output.='>'.Hierarchy::hierarchyTraveller($id,$row['id'],$allowDisabled).'</item>';
		    }
    	}
    	Database::free($result);
    	return $output;
    }

    
    ///////////////////// Static ////////////////////

	function getItemIcon($type) {
		$icons = array('page'=>'common/page','pageref'=>'common/pagereference','email'=>'common/email','url'=>'monochrome/globe','file'=>'monochrome/file');
		return $icons[$type];
	}
    
    function moveItem($id,$dir) {
        $output = false;
        $sql="select * from hierarchy_item where id=".Database::int($id);
        if ($row = Database::selectFirst($sql)) {
	        $index=$row['index'];
	        $hierarchyId=$row['hierarchy_id'];
	        $parent=$row['parent'];

	        $sql="select id from hierarchy_item where parent=".Database::int($parent)." and hierarchy_id=".Database::int($hierarchyId)." and `index`=".Database::int($index+$dir);
	        $result = Database::select($sql);
	        if ($row = Database::next($result)) {
	        	$otherid=$row['id'];

	        	$sql="update hierarchy_item set `index`=".Database::int($index+$dir)." where id=".Database::int($id);
	        	Database::update($sql);

	        	$sql="update hierarchy_item set `index`=".Database::int($index)." where id=".Database::int($otherid);
	        	Database::update($sql);

	        	$sql="update hierarchy set changed=now() where id=".Database::int($hierarchyId);
	        	Database::update($sql);
	        	$output = true;
				EventManager::fireEvent('update','hierarchy',null,$id);
	        }
	        Database::free($result);
		} else {
			error_log('could not load: '.$sql);
		}
        return $output;
    }
    
    function deleteItem($id) {

        // Load info about item
        $sql="select * from hierarchy_item where id=".Database::int($id);
        $row = Database::selectFirst($sql);
		if (!$row) {
			Log::debug('Cannot find item');
			return null;
		}
		// Check that no children exists
        $sql="select * from hierarchy_item where parent=".Database::int($id);
		if (Database::selectFirst($sql)) {
			Log::debug('Will not delete item with parents');
			return null;
		}
        $parent = $row['parent'];
        $hierarchyId = $row['hierarchy_id'];

        // Delete item
        $sql="delete from hierarchy_item where id=".Database::int($id);
        Database::delete($sql);

        // Fix positions
        $sql="select id from hierarchy_item where parent=".Database::int($parent)." and hierarchy_id=".Database::int($hierarchyId)." order by `index`";
        $result = Database::select($sql);

        $index=1;
        while ($row = Database::next($result)) {
        	$sql="update hierarchy_item set `index`=".Database::int($index)." where id=".Database::int($row['id']);
        	Database::update($sql);
        	$index++;
        }
        Database::free($result);

        // Mark hierarchy as changed
        $sql="update hierarchy set changed=now() where id=".Database::int($hierarchyId);
        Database::update($sql);
	
		EventManager::fireEvent('update','hierarchy',null,$hierarchyId);
        return $hierarchyId;
    }
    
    function getAncestorPath($id) {
	    $output = array();
	    $parent = $id;
	    while ($parent>0) {
    	    $sql = "select * from hierarchy_item where id=".Database::int($parent);
    	    if ($row=Database::selectFirst($sql)) {
    	        $output[] = intval($row['id']);
    	        $parent = $row['parent'];
    	    } else {
    	        $parent = 0;
    	    }
	    }
/*        $sql = "select hierarchy.id,hierarchy.name from hierarchy,hierarchy_item where hierarchy.id=hierarchy_item.hierarchy_id and hierarchy_item.id=".Database::int($id);
    	if ($row=Database::selectFirst($sql)) {
            $output[] = array('type' => 'hierarchy','id' => $row['id'],'title' => $row['name']);    	
    	}*/
	    return array_reverse($output);
	}

    function getItemPath($id) {
	    $output = array();
	    $parent = $id;
	    while ($parent>0) {
    	    $sql = "select * from hierarchy_item where id=".Database::int($parent);
    	    if ($row=Database::selectFirst($sql)) {
    	        $output[] = array('type'=>'item','id'=>$row['id'],'title'=>$row['title']);
    	        $parent = $row['parent'];
    	    } else {
    	        $parent = 0;
    	    }
	    }
        $sql = "select hierarchy.id,hierarchy.name from hierarchy,hierarchy_item where hierarchy.id=hierarchy_item.hierarchy_id and hierarchy_item.id=".Database::int($id);
    	if ($row=Database::selectFirst($sql)) {
            $output[] = array('type' => 'hierarchy','id' => $row['id'],'title' => $row['name']);    	
    	}
	    return array_reverse($output);
	}
	
	function markHierarchyOfItemChanged($id) {
	    $sql = "update hierarchy,hierarchy_item set hierarchy.changed=now() where hierarchy.id=hierarchy_item.hierarchy_id and hierarchy_item.id=".Database::int($id);
        Database::update($sql);
	}
	
	function markHierarchyOfPageChanged($id) {
		$sql = "update hierarchy,hierarchy_item set hierarchy.changed=now() where hierarchy_item.hierarchy_id=hierarchy.id and hierarchy_item.target_id = ".Database::int($id)." and (target_type in ('page','pageref'))";
        Database::update($sql);
	}
	
	function relocateItem($move,$targetItem,$targetHierarchy) {

		// Get info about hierarchy item
		$sql = "select * from hierarchy_item where id=".Database::int($move);
		if ($row = Database::selectFirst($sql)) {
			$moveHierarchy = $row['hierarchy_id'];
			$moveParent = $row['parent'];
		} else {
			return array('success'=>false,'message'=>'Punktet findes ikke');
		}

		if ($targetHierarchy>0 && $targetHierarchy!=$moveHierarchy) {
			return array('success'=>false,'message'=>'Du kan ikke flyttet et punkt til et andet hierarki');
		} else if ($moveParent==$targetItem) {
			return array('success'=>false,'message'=>'Punktet har allerede denne position');
		} else if ($move==$targetItem) {
			return array('success'=>false,'message'=>'Du kan ikke flyttet et punkt til sig selv');
		} else {
			$path = Hierarchy::getAncestorPath($targetItem);
			if (in_array($move,$path)) {
				return array('success'=>false,'message'=>'Du kan ikke flyttet et punkt til et punkt under sig selv');
			}
		}

		// Find largest position of items under new parent
		if ($targetHierarchy>0) {
			$sql = "select max(`index`) as `index` from hierarchy_item where parent=0 and hierarchy_id=".Database::int($targetHierarchy);
			$newParent = 0;
		} else {
			$sql = "select max(`index`) as `index` from hierarchy_item where parent=".Database::int($targetItem);
			$newParent = $targetItem;
		}
		if ($row = Database::selectFirst($sql)) {
		    $newIndex = $row['index']+1;
		} else {
		    $newIndex = 1;
		}

		// Change position to new position
		$sql="update hierarchy_item set parent=".Database::int($newParent).",`index`=".Database::int($newIndex)." where id=".Database::int($move);
		Database::update($sql);

		// Fix positions of old parent
		$sql="select id from hierarchy_item where parent=".Database::int($moveParent)." and hierarchy_id=".Database::int($moveHierarchy)." order by `index`";
		$result = Database::select($sql);
		$index=1;
		while ($row = Database::next($result)) {
			$sql="update hierarchy_item set `index`=".Database::int($index)." where id=".Database::int($row['id']);
			Database::update($sql);
			$index++;
		}
		Database::free($result);

		Hierarchy::markHierarchyOfItemChanged($move);
		
		return array('success'=>true);
	}
}
?>