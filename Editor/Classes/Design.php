<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');

class Design extends Object {
    
    var $unique;
	var $parameters;
    
    function Design() {
		parent::Object('design');
        $this->parameters = array();
    }
    
    function setUnique($unique) {
        $this->unique = $unique;
    }

    function getUnique() {
        return $this->unique;
    }
    
	function setParameter($key,$type,$value) {
		if ($param = $this->getParameter($key)) {
			$this->parameters[$key]['value'] = $value;
			$this->parameters[$key]['type'] = $type;
		} else {
			$this->parameters[$key] = array('key' => $key, 'type' => $type, 'value' => $value);
		}
	}

	function getParameter($key) {
		$output = false;
		foreach ($this->parameters as $parm) {
			if ($parm['key']==$key) {
				$output = $parm;
			}
		}
		return $output;
	}
	
	function getIn2iGuiIcon() {
		return 'common/color';
	}

    //////////////////// Special ////////////////////
    
    function canRemove() {
        $sql="select count(id) as num from page where design_id=".Database::int($this->id);
        if ($row = Database::selectFirst($sql)) {
            return $row['num']==0;
        }
        return true;
    }

    ////////////////// Persistence //////////////////
    
	function load($id) {
		$obj = new Design();
		if ($obj->_load($id)) {
			$sql = "select * from design where object_id=".$id;
			if ($row = Database::selectFirst($sql)) {
	        	$obj->setUnique($row['unique']);
				$obj->_loadParameters();
				return $obj;
			}
		}
		return null;
	}
    
    function _populate(&$row) {
        $design = new Design();
        $design->setId($row['id']);
        $design->setName($row['name']);
        $design->setUnique($row['unique']);
		$design->_loadParameters();
        return $design;        
    }
    
	function _loadParameters() {
        $sql = "select * from design_parameter where design_id=".Database::int($this->id)." order by `key`";
        $result = Database::select($sql);
        while ($row = Database::next($result)) {
            $this->parameters[$row['key']] = array('key' => $row['key'],'value' => $row['value'],'type' => $row['type']);
        }
        Database::free($result);
	}

	function _saveParameters() {
		$sql = "delete from design_parameter where design_id=".$this->id;
		Database::delete($sql);
		foreach ($this->parameters as $parm) {
			$sql = "insert into design_parameter (design_id,`key`,`type`,value) values (".$this->id.",".Database::text($parm['key']).",".Database::text($parm['type']).",".Database::text($parm['value']).")";
			Database::insert($sql);
		}
	}

    function search() {
        $out = array();
        $sql = "select id from object where type='design' order by title";
        $result = Database::select($sql);
		$ids = array();
        while ($row = Database::next($result)) {
            $ids[] = $row['id'];
        }
        Database::free($result);
		foreach ($ids as $id) {
			$out[] = Design::load($id);
		}
        return $out;
    }

	function sub_create() {
		$sql="insert into design (object_id,`unique`) values (".
		$this->id.
		",".Database::text($this->unique).
		")";
		Database::insert($sql);
		$this->_saveParameters();
	}

	function sub_remove() {
        $sql='delete from design where id='.$this->id;
		Database::delete($sql);
        $sql='delete from design_parameter where design_id='.$this->id;
		Database::delete($sql);
	}

	function sub_update() {
		$sql = "update design set ".
		"`unique`=".Database::text($this->unique).
		" where object_id=".$this->id;
		Database::update($sql);
		$this->_saveParameters();
	}
}
?>