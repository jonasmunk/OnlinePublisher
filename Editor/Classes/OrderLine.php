<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');

class OrderLine extends Object {

	var $orders_id;
	var $product_id;
	var $product_model;
	var $product_name;
	var $product_price;	
	var $final_price;
	var $product_tax;
	var $product_quantity;
	
	function OrderLine() {
		parent::Object('orderLine');
	}
	
	function setOrders_id($orders_id) {
		$this->orders_id = $orders_id;
	}
	
	function getOrders_id() {
		return $this->orders_id;
	}
	
	function getProduct_id() {
		return $this->product_id;
	}
	
	function setProduct_id($product_id) {
		$this->product_id = $product_id;
	}	
	
	function setProduct_model($product_model) {
		$this->product_model = $product_model;
	}
	
	function getProduct_model() {
		return $this->product_model;
	}
	function setProduct_name($product_name) {
		$this->product_name = $product_name;
	}
	
	function getProduct_name() {
		return $this->product_name;
	}
	function setProduct_price($product_price) {
		$this->product_price = $product_price;
	}
	
	function getProduct_price() {
		return $this->product_price;
	}
	function setFinal_price($final_price) {
		$this->final_price = $final_price;
	}
	
	function getFinal_price() {
		return $this->final_price;
	}
	function setProduct_tax($product_tax) {
		$this->product_tax = $product_tax;
	}
	
	function getProduct_tax() {
		return $this->product_tax;
	}
	function setProduct_quantity($product_quantity) {
		$this->product_quantity = $product_quantity;
	}
	
	function getProduct_quantity() {
		return $this->product_quantity;
	}
		
	function load($id) {
		$obj = new OrderLine();
		$obj->_load($id);
		$sql = "select orders_id, product_id, product_model, product_name, product_price, final_price,product_tax, product_quantity, from orderLine where object_id=".$id;
		$row = Database::selectFirst($sql);
		if ($row) {
			$obj->orders_id=$row['orders_id'];
			$obj->product_id=$row['product_id'];
			$obj->product_model=$row['product_model'];
			$obj->product_name=$row['product_name'];
			$obj->product_price=$row['product_price'];
			$obj->final_price=$row['final_price'];
			$obj->product_tax=$row['product_tax'];
			$obj->product_quantity,=$row['product_quantity'];			
		}
		return $obj;
	}
	
	function sub_create() {
		$sql = 	"insert into orderLine (object_id, orders_id, product_id, product_model, product_name, product_price, final_price,product_tax, product_quantity)".
				" values (".$this->id.",".Database::text($this->orders_id).",".$this->product_id.",".$this->product_model.",".Database::text($this->product_name).",".$this->product_price.",".$this->final_price.",".$this->product_tax.",".$this->product_quantity.")";
		Database::insert($sql);
	}
	orders_id, product_id, product_model, product_name,	product_price, final_price,product_tax, product_quantity,
	function sub_update() {
		$sql = "update orderLine set ".
		"orders_id=".Database::text($this->orders_id).
		",product_id=".$this->product_id.
		",product_model=".$this->product_model.
		",product_name=".$this->product_name.
		",product_price=".$this->product_price.
		",final_price=".$this->final_price.
		",product_tax=".$this->product_tax.
		",product_quantity=".$this->product_quantity.
		" where object_id=".$this->id;
		Database::update($sql);
	}
	
	function sub_publish() {
		$data = '<orderLine xmlns="'.parent::_buildnamespace('1.0').'">'.
		'<orders_id>'.encodeXML($this->orders_id).'</orders_id>'.
		'<product_id>'.encodeXML($this->product_id).'</product_id>'.
		'<product_model>'.encodeXML($this->product_model).'</product_model>'.
		'<product_name>'.encodeXML($this->product_name).'</product_name>'.
		'<product_price>'.encodeXML($this->product_price).'</product_price>'.
		'<final_price>'.encodeXML($this->final_price).'</final_price>'.
		'<product_tax>'.encodeXML($this->product_tax).'</product_tax>'.
		'<product_quantity>'.encodeXML($this->product_quantity).'</product_quantity>'.
		}
		$data.='</orderLine>';
		return $data;
	}
	
	function sub_remove() {
		$sql = "delete from orderLine where object_id=".$this->id;
		Database::delete($sql);
	}
	
	/////////////////////////// GUI /////////////////////////
	
	function getIcon() {
	    return 'Element/OrderLine';
	}
}
?>