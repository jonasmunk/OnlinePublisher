<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once('Object.php');

class Stock extends Object {
	
	function Stock() {
		parent::Object('stock');
	}
		
	function load($id) {
		$obj = new Stock();
		$obj->_load($id);
		$sql = "select * from stock where object_id=".$id;
		$row = Database::selectFirst($sql);
		if ($row) {
			
		}
		return $obj;
	}
	
	function sub_create() {
		$sql = "insert into stock (object_id) values (".$this->id.")";
		Database::insert($sql);
	}
	
	function sub_update() {
		/*$sql = "update stock set ".
		"number=".Database::text($this->number).
		",image_id=".$this->imageId.
		",producttype_id=".$this->productTypeId.
		" where object_id=".$this->id;
		Database::update($sql);*/
	}
	
	function sub_publish() {
		$data = '<stock xmlns="'.parent::_buildnamespace('1.0').'">'.
		/*'<number>'.encodeXML($this->number).'</number>'.
		'<attributes>';
		$sql="select * from productattribute where product_id=".$this->id." order by `index`";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$data.='<attribute name="'.encodeXML($row['name']).'">'.
			encodeXMLBreak($row['value'],'<break/>').
			'</attribute>';
		}
		Database::free($result);
		$data.='</attributes>'.
		'<prices>';
		$sql="select * from productprice where product_id=".$this->id." order by `index`";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$data.='<price'.
				' amount="'.encodeXML($row['amount']).'"'.
				' type="'.encodeXML($row['type']).'"'.
				' price="'.encodeXML($row['price']).'"'.
				' currency="'.encodeXML($row['currency']).'"'.
				'/>';
		}
		Database::free($result);
		$data.='</prices>';
		
		$sql = "select * from image where object_id=".$this->imageId;
		$row = Database::selectFirst($sql);
		if ($row) {
			$data.='<image'.
				' id="'.encodeXML($row['id']).'"'.
				' filename="'.encodeXML($row['filename']).'"'.
				' width="'.encodeXML($row['width']).'"'.
				' height="'.encodeXML($row['height']).'"'.
				'/>';
		}*/
		$data.='</stock>';
		return $data;
	}
	
	function sub_remove() {
		$sql="delete from stock_product where stock_id=".$this->id;
		Database::delete($sql);
		$sql = "delete from stock where object_id=".$this->id;
		Database::delete($sql);
	}
}
?>