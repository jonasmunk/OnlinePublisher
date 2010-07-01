<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/EventManager.php');
require_once($basePath.'Editor/Include/Publishing.php');

class Hierarchy {
    
    var $id;
    var $name;
    var $language;
    
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
    
    //////////////////// Special ////////////////////
    
    function canDelete() {
        $out = false;
        $sql="select count(id) as num from hierarchy_item where hierarchy_id=".$this->id;
        if ($row = Database::selectFirst($sql)) {
            if ($row['num']==0) {
                $out = true;
            }
        }
        return $out;
    }
    
    ////////////////// Persistence //////////////////
    
    function load($id) {
        $sql = "select * from hierarchy where id=".$id;
        if ($row = Database::selectFirst($sql)) {
            return Hierarchy::_populate($row);
        } else {
            return false;
        }
    }
	
	function loadFromItemId($id) {
		$sql="select hierarchy_id from hierarchy_item where id=".$id;
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
        return $hier;        
    }
    
    function search() {
        $out = array();
        $sql = "select * from hierarchy order by name";
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
		sqlText($this->name).",".
		sqlText($this->language).",".
		sqlText($data).",".
		"now(),now()".
		")";		
		$this->id = Database::insert($sql);
    }
    
    function delete() {
        if ($this->canDelete()) {
            $sql='delete from hierarchy where id='.$this->id;
            return Database::delete($sql);
        } else {
            return false;
        }
    }
    
    function update() {
        $sql="update hierarchy set ".
        "name=".sqlText($this->name).
        ",language=".sqlText($this->language).
        " where id=".$this->id;
        return Database::update($sql);
    }

	function createItemForPage($pageId,$title,$parentId) {
		
		// find index
		$sql="select max(`index`) as `index` from hierarchy_item where parent=".$parentId." and hierarchy_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			$index=$row['index']+1;
		} else {
			$index=1;
		}
		
		$sql="insert into hierarchy_item (title,type,hierarchy_id,parent,`index`,target_type,target_id) values (".
		sqlText($title).
		",'item'".
		",".$this->id.
		",".$parentId.
		",".$index.
		",'page'".
		",".$pageId.
		")";
		Database::insert($sql);
	}

	function markChanged() {
		$sql="update hierarchy set changed=now() where id=".$this->id;
		Database::update($sql);
	}
	
    /////////////////// Publishing //////////////////
    
    function publish($allowDisabled=false) {
	    $data = $this->build($this->id,$allowDisabled);
	    $sql="update hierarchy set published=now(),data=".sqlText($data)." where id=".$this->id;
	    Database::update($sql);
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
    	" where parent=".$parent.
    	" and hierarchy_id=".$id.
    	" order by `index`";
    	$result = Database::select($sql);
    	while ($row = Database::next($result)) {
    	    if ($row['disabled']!=1 || $allowDisabled) {
        		$output.='<item title="'.encodeXML($row['title']).
        					'" alternative="'.encodeXML($row['alternative']).'"';
        		if ($row['target_type']=='page') {
        			$output.=' page="'.$row['target_id'].'"';
					if (strlen($row['path'])>0) {
        				$output.=' path="'.encodeXML($row['path']).'"';
					}
        		}
        		if ($row['target_type']=='pageref') {
        			$output.=' page-reference="'.$row['target_id'].'"';
					if (strlen($row['path'])>0) {
        				$output.=' path="'.encodeXML($row['path']).'"';
					}
        		}
        		else if ($row['target_type']=='file') {
        			$output.=' file="'.$row['target_id'].'" filename="'.encodeXML(getFileFilename($row['target_id'])).'"';
        		}
        		else if ($row['target_type']=='url') {
        			$output.=' url="'.encodeXML($row['target_value']).'"';
        		}
        		else if ($row['target_type']=='email') {
        			$output.=' email="'.encodeXML($row['target_value']).'"';
        		}
        		if ($row['target']!='') {
        			$output.=' target="'.encodeXML($row['target']).'"';
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
        $sql="select * from hierarchy_item where id=".$id;
        if ($row = Database::selectFirst($sql)) {
	        $index=$row['index'];
	        $hierarchyId=$row['hierarchy_id'];
	        $parent=$row['parent'];

	        $sql="select id from hierarchy_item where parent=".$parent." and hierarchy_id=".$hierarchyId." and `index`=".($index+$dir);
	        $result = Database::select($sql);
	        if ($row = Database::next($result)) {
	        	$otherid=$row['id'];

	        	$sql="update hierarchy_item set `index`=".($index+$dir)." where id=".$id;
	        	Database::update($sql);

	        	$sql="update hierarchy_item set `index`=".$index." where id=".$otherid;
	        	Database::update($sql);

	        	$sql="update hierarchy set changed=now() where id=".$hierarchyId;
	        	Database::update($sql);
	        	$output = true;
	        }
	        Database::free($result);
		} else {
			error_log('could not load: '.$sql);
		}
		EventManager::fireEvent('update','hierarchy',null,$id);
        return $output;
    }
    
    function deleteItem($id) {

        // Load info about item
        $sql="select * from hierarchy_item where id=".$id;
        $row = Database::selectFirst($sql);
		if (!$row) {
			return null;
		}
        $parent=$row['parent'];
        $hierarchyId=$row['hierarchy_id'];

        // Delete item
        $sql="delete from hierarchy_item where id=".$id;
        Database::delete($sql);

        // Fix positions
        $sql="select id from hierarchy_item where parent=".$parent." and hierarchy_id=".$hierarchyId." order by `index`";
        $result = Database::select($sql);

        $index=1;
        while ($row = Database::next($result)) {
        	$sql="update hierarchy_item set `index`=".$index." where id=".$row['id'];
        	Database::update($sql);
        	$index++;
        }
        Database::free($result);

        // Mark hierarchy as changed
        $sql="update hierarchy set changed=now() where id=".$hierarchyId;
        Database::update($sql);
	
		EventManager::fireEvent('update','hierarchy',null,$id);
        return $hierarchyId;
    }
    
    function getItemPath($id) {
	    $output = array();
	    $parent = $id;
	    while ($parent>0) {
    	    $sql = "select * from hierarchy_item where id=".$parent;
    	    if ($row=Database::selectFirst($sql)) {
    	        $output[] = array('type'=>'item','id'=>$row['id'],'title'=>$row['title']);
    	        $parent = $row['parent'];
    	    } else {
    	        $parent = 0;
    	    }
	    }
        $sql = "select hierarchy.id,hierarchy.name from hierarchy,hierarchy_item where hierarchy.id=hierarchy_item.hierarchy_id and hierarchy_item.id=".$id;
    	if ($row=Database::selectFirst($sql)) {
            $output[] = array('type' => 'hierarchy','id' => $row['id'],'title' => $row['name']);    	
    	}
	    return array_reverse($output);
	}
	
	function markHierarchyOfItemChanged($id) {
	    $sql = "update hierarchy,hierarchy_item set hierarchy.changed=now() where hierarchy.id=hierarchy_item.hierarchy_id and hierarchy_item.id=".$id;
        Database::update($sql);
	}
}
?>