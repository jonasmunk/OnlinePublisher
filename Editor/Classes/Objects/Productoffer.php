<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Object::$schema['productoffer'] = array(
	'offer' => array('type'=>'string'),
	'productId' => array('type'=>'int','column'=>'product_id'),
	'personId' => array('type'=>'int','column'=>'person_id'),
	'expiry' => array('type'=>'datetime')
);
class ProductOffer extends Object {
	var $offer;
	var $productId = 0;
	var $personId = 0;
	var $expiry;

	function ProductOffer() {
		parent::Object('productoffer');
	}

	static function load($id) {
		return Object::get($id,'productoffer');
	}

	function updateTitle() {
		$this->title = $this->offer;
	}
	
	function setOffer($offer) {
		$this->offer = $offer;
		$this->updateTitle();
	}

	function getOffer() {
		return $this->offer;
	}

	function setProductId($id) {
		$this->productId = $id;
	}

	function getProductId() {
		return $this->productId;
	}
	
	function setPersonId($personId) {
	    $this->personId = $personId;
	}

	function getPersonId() {
	    return $this->personId;
	}
	
	function setExpiry($expiry) {
	    $this->expiry = $expiry;
	}

	function getExpiry() {
	    return $this->expiry;
	}
	
	
	function search($options = null) {
		if (!is_array($options)) {
			$options = array();
		}
		$sql = "select object.id from productoffer,object where object.id=productoffer.object_id";
		if (isset($options['productId'])) {
			$sql.=" and productoffer.product_id=".$options['productId'];
		}
		$sql.=" order by object.title";
		$result = Database::select($sql);
		$ids = array();
		while ($row = Database::next($result)) {
			$ids[] = $row['id'];
		}
		Database::free($result);
		
		$list = array();
		foreach ($ids as $id) {
			$list[] = ProductOffer::load($id);
		}
		return $list;
	}

    function find($query = array()) {
    	$parts = array();
		$parts['columns'] = 'object.id';
		$parts['tables'] = 'productoffer,object,object as product,object as person';
		$parts['limits'] = 'productoffer.object_id=object.id and productoffer.product_id=product.id and productoffer.person_id=person.id';
		$parts['ordering'] = '';
    	
		if ($query['sort']=='offer') {
			$parts['ordering']="productoffer.offer";
		} else if ($query['sort']=='product') {
			$parts['ordering']="product.title";
		} else if ($query['sort']=='person') {
			$parts['ordering']="person.title";
		} else if ($query['sort']=='expiry') {
			$parts['ordering']="productoffer.expiry";
		}
		if ($query['direction']=='descending') {
			$parts['ordering'].=' desc';
		} else {
			$parts['ordering'].=' asc';
		}
		
		$list = ObjectService::_find($parts,$query);
		$list['result'] = array();
		foreach ($list['rows'] as $row) {
			$list['result'][] = ProductOffer::load($row['id']);
		}
		return $list;
	}
	
	/////////////////////////// GUI /////////////////////////
	
	function getIcon() {
		return "common/object";
	}
}
?>