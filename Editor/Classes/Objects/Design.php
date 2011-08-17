<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Objects
 */
require_once($basePath.'Editor/Classes/Object.php');

Object::$schema['design'] = array(
	'unique' => array('type'=>'string')
);
class Design extends Object {
    
    var $unique;
    
    function Design() {
		parent::Object('design');
    }

	function load($id) {
		return Object::get($id,'design');
	}
    
    function setUnique($unique) {
        $this->unique = $unique;
    }

    function getUnique() {
        return $this->unique;
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

	function removeMore() {
        $sql='delete from design_parameter where design_id='.Database::int($this->id);
		Database::delete($sql);
	}
}
?>