<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Object.php');

class ProductOffer extends Object {
	var $offer;
	var $productId=0;
	var $personId=0;
	var $expiry;

	function ProductOffer() {
		parent::Object('productoffer');
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
		
		$list = parent::_find($parts,$query);
		$list['result'] = array();
		foreach ($list['rows'] as $row) {
			$list['result'][] = ProductOffer::load($row['id']);
		}
		return $list;
	}
	

    /////////////////////////// Persistence ////////////////////////

	function load($id) {
		$sql = "select offer,person_id,product_id,UNIX_TIMESTAMP(expiry) as expiry".
		" from productoffer where object_id=".$id;
		$row = Database::selectFirst($sql);
		if ($row) {
			$obj = new ProductOffer();
			$obj->_load($id);
			$obj->offer=$row['offer'];
			$obj->productId=$row['product_id'];
			$obj->personId=$row['person_id'];
			$obj->expiry=$row['expiry'];
			return $obj;
		} else {
			return null;
		}
	}

	function sub_create() {
		$sql="insert into productoffer (object_id,offer,product_id,person_id,expiry) values (".
		$this->id.
		",".Database::text($this->offer).
		",".Database::int($this->productId).
		",".Database::int($this->personId).
		",".Database::datetime($this->expiry).
		")";
		Database::insert($sql);
	}

	function sub_update() {
		$sql = "update productoffer set ".
		"offer=".Database::text($this->offer).
		",product_id=".Database::int($this->productId).
		",person_id=".Database::int($this->personId).
		",expiry=".Database::datetime($this->expiry).
		" where object_id=".$this->id;
		Database::update($sql);
	}

	function sub_publish() {
		$data =
		'<productoffer xmlns="'.parent::_buildnamespace('1.0').'">'.
		'</productoffer>';
		return $data;
	}

	function sub_remove() {
		$sql = "delete from productoffer where object_id=".$this->id;
		Database::delete($sql);
	}
	
	/////////////////////////// GUI /////////////////////////
	
	function getIcon() {
	    return 'Element/Generic';
	}
	
	function getIn2iGuiIcon() {
		return "common/object";
	}
}
?>