<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Model
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class Hierarchy extends Entity {
    
    var $name;
    var $language;
	var $changed;
	var $published;
    
    function Hierarchy() {
        
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
		return HierarchyService::canDeleteHierarchy($this->id);
    }
    
    ////////////////// Persistence //////////////////
    
    static function load($id) {
        $sql = "select id,name,language,UNIX_TIMESTAMP(changed) as changed,UNIX_TIMESTAMP(published) as published from hierarchy where id=".Database::int($id);
        if ($row = Database::selectFirst($sql)) {
            return Hierarchy::_populate($row);
        } else {
            return null;
        }
    }
	
	static function loadFromItemId($id) {
		$sql="select hierarchy_id from hierarchy_item where id=".Database::int($id);
		if ($row = Database::selectFirst($sql)) {
			return Hierarchy::load($row['hierarchy_id']);
		}
		return null;		
	}
    
    static function _populate(&$row) {
        $hier = new Hierarchy();
        $hier->setId($row['id']);
        $hier->setName($row['name']);
        $hier->setLanguage($row['language']);
        $hier->setPublished($row['published']);
        $hier->setChanged($row['changed']);
        return $hier;        
    }
    
    static function search() {
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
		HierarchyService::createHierarchy($this);
    }

	function remove() {
		return $this->delete();
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
		if ($this->id > 0) {
			$this->update();
		} else {
			$this->create();
		}
	}
    
    function update() {
		HierarchyService::updateHierarchy($this);
    }

	function createItemForPage($pageId,$title,$parentId) {
		return $this->createItem(array('targetType'=>'page','targetValue'=>$pageId,'title'=>$title,'parent'=>$parentId,'hidden'=>false));
	}

	function createItem($options) {
		$options['hierarchyId'] = $this->id;
		return HierarchyService::createItem($options);
	}

	function markChanged() {
		HierarchyService::markHierarchyChanged($this->id);
	}
	
    /////////////////// Publishing //////////////////
    
    function publish($allowDisabled=false) {
	    $data = $this->build($this->id,$allowDisabled);
	    $sql="update hierarchy set published=now(),data=".Database::text($data)." where id=".Database::int($this->id);
	    Database::update($sql);
		CacheService::clearCompletePageCache();
    }
    
    static function build($id,$allowDisabled=true) {
    	return '<hierarchy xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/hierarchy/1.0/">'.
    	Hierarchy::hierarchyTraveller($id,0,$allowDisabled).
    	'</hierarchy>';
    }

    static function hierarchyTraveller($id,$parent,$allowDisabled) {
    	$output="";
    	$sql="select hierarchy_item.*,page.disabled,page.path from hierarchy_item".
    	" left join page on page.id = hierarchy_item.target_id and (hierarchy_item.target_type='page' or hierarchy_item.target_type='pageref')".
    	" where parent=".Database::int($parent).
    	" and hierarchy_id=".Database::int($id).
    	" order by `index`";
    	$result = Database::select($sql);
    	while ($row = Database::next($result)) {
    	    if ($row['disabled']!=1 || $allowDisabled) {
        		$output.='<item title="'.Strings::escapeEncodedXML($row['title']).
        					'" alternative="'.Strings::escapeEncodedXML($row['alternative']).'"';
        		if ($row['target_type']=='page') {
        			$output.=' page="'.$row['target_id'].'"';
					if (strlen($row['path'])>0) {
        				$output.=' path="'.Strings::escapeEncodedXML($row['path']).'"';
					}
        		}
        		if ($row['target_type']=='pageref') {
        			$output.=' page-reference="'.$row['target_id'].'"';
					if (strlen($row['path'])>0) {
        				$output.=' path="'.Strings::escapeEncodedXML($row['path']).'"';
					}
        		}
        		else if ($row['target_type']=='file') {
        			$output.=' file="'.$row['target_id'].'" filename="'.Strings::escapeEncodedXML(FileService::getFileFilename($row['target_id'])).'"';
        		}
        		else if ($row['target_type']=='url') {
        			$output.=' url="'.Strings::escapeEncodedXML($row['target_value']).'"';
        		}
        		else if ($row['target_type']=='email') {
        			$output.=' email="'.Strings::escapeEncodedXML($row['target_value']).'"';
        		}
        		if ($row['target']!='') {
        			$output.=' target="'.Strings::escapeEncodedXML($row['target']).'"';
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

	static function getItemIcon($type) {
		$icons = array('page'=>'common/page','pageref'=>'common/pagereference','email'=>'common/email','url'=>'monochrome/globe','file'=>'monochrome/file');
		return $icons[$type];
	}
    
    static function moveItem($id,$dir) {
		if ($dir<0) {
			$dir = -1;
		} else {
			$dir = 1;
		}
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
				EventService::fireEvent('update','hierarchy',null,$id);
	        }
	        Database::free($result);
		} else {
			error_log('could not load: '.$sql);
		}
        return $output;
    }
    
    static function deleteItem($id) {
		return HierarchyService::deleteItem($id);
    }
    
    static function getAncestorPath($id) {
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
	    return array_reverse($output);
	}

    static function getItemPath($id) {
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
	
	static function markHierarchyOfItemChanged($id) {
	    $sql = "update hierarchy,hierarchy_item set hierarchy.changed=now() where hierarchy.id=hierarchy_item.hierarchy_id and hierarchy_item.id=".Database::int($id);
        Database::update($sql);
	}
	
	static function markHierarchyOfPageChanged($id) {
		$sql = "update hierarchy,hierarchy_item set hierarchy.changed=now() where hierarchy_item.hierarchy_id=hierarchy.id and hierarchy_item.target_id = ".Database::int($id)." and (target_type in ('page','pageref'))";
        Database::update($sql);
	}
	
	static function relocateItem($move,$targetItem=0,$targetHierarchy=0) {

		// Get info about hierarchy item
		$sql = "select * from hierarchy_item where id=".Database::int($move);
		if ($row = Database::selectFirst($sql)) {
			$moveHierarchy = $row['hierarchy_id'];
			$moveParent = $row['parent'];
		} else {
			return array('success'=>false,'message'=>array('en'=>'The item could not be found', 'da'=>'Punktet findes ikke'));
		}
		if ($targetItem>0) {
			$sql = "select * from hierarchy_item where id=".Database::int($targetItem);
			if ($row = Database::selectFirst($sql)) {
				$targetHierarchy = $row['hierarchy_id'];
			} else {
				return array('success'=>false,'message'=>array('en'=>'The target could not be found', 'da'=>'Punktet findes ikke'));
			}
		}

		if ($targetHierarchy>0 && $targetHierarchy!=$moveHierarchy) {
			return array('success'=>false,'message'=>array('en'=>'The item cannot be moved to another hierarchy','da'=>'Punktet kan ikke flyttes til et andet hierarki'));
		} else if ($moveParent==$targetItem) {
			return array('success'=>false,'message'=>array('en'=>'The item is already located here', 'da'=>'Punktet har allerede denne position'));
		} else if ($move==$targetItem) {
			return array('success'=>false,'message'=>array('en'=>'The item cannot be moved to itself','da'=>'Punktet kan ikke flyttes til sig selv'));
		} else {
			$path = Hierarchy::getAncestorPath($targetItem);
			if (in_array($move,$path)) {
				return array('success'=>false,'message'=>array('en' => 'The item cannot be moved to a sub item', 'da'=>'Punktet kan ikke flyttes til et underpunkt'));
			}
		}

		// Find largest position of items under new parent
		if ($targetHierarchy>0 && $targetItem==0) {
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