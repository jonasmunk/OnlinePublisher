<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Entity::$schema['Parameter'] = array(
	'table' => 'parameter',
	'properties' => array(
		'id' => array('type'=>'int'),
		'name' => array('type'=>'string'),
		'level' => array('type'=>'string'),
		'language' => array('type'=>'string'),
		'value' => array('type'=>'string'),
	)
);
class Parameter extends Entity implements Loadable {

    var $name;
    var $level;
    var $language;
    var $value;

    //$domain
    //$target_id
    //$value


    function setName($name) {
        $this->name = $name;
    }

    function getName() {
        return $this->name;
    }

    function setLevel($level) {
        $this->level = $level;
    }

    function getLevel() {
        return $this->level;
    }

    function setLanguage($language) {
        $this->language = $language;
    }

    function getLanguage() {
        return $this->language;
    }

    function setValue($value) {
        $this->value = $value;
    }

    function getValue() {
        return $this->value;
    }


	static function load($id) {
		return ModelService::load('Parameter',$id);
	}

	function save() {
		return ModelService::save($this);
	}

	function remove() {
		return ModelService::remove($this);
	}

}