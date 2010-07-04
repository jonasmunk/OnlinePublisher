<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');

class Order extends Object {
	var $order_no;
	var $customers_id;
	var $customers_name;
	var $customers_company;
	var $customers_street_address;
	var $customers_city;
	var $customers_zipcode;
	var $customers_country;
	var $customers_telephone;
	var $customers_email;
	var $delivery_name;
	var $delivery_company;
	var $delivery_street_address;
	var $delivery_city;
	var $delivery_zipcode;
	var $delivery_country;
	var $billing_name;
	var $billing_company;
	var $billing_street_address;
	var $billing_city;
	var $billing_zipcode;
	var $billing_country;
	var $payment_method;
	var $cc_type;
	var $cc_owner;
	var $cc_number;
	var $cc_controle_number;
	var $cc_expires;
	var $orders_status;
	var $orders_date_finished;
	var $currency;
	var $currency_value;
	var $total_amount;
	var $total_tax;
	
	
	function Order() {
		parent::Object('order');
		$this->title = $id;
	}
	
	function setOrder_no($order_no) {
		$this->order_no = $order_no;
	}
	
	function getOrder_no() {
		return $this->order_no;
	}
	
	function setCustomers_id($customers_id) {
		$this->customers_id = $customers_id;
	}
	
	function getCustomers_id() {
		return $this->customers_id;
	}
	
	function setCustomers_name($customers_name) {
		$this->customers_name = $customers_name;
	}
	
	function getCustomers_name() {
		return $this->customers_name;
	}
	
	function setCustomers_company($customers_company) {
		$this->customers_company = $customers_company;
	}
	
	function getCustomers_company() {
		return $this->customers_company;
	}
	
	function setCustomers_street_address($street_address) {
		$this->customers_street_address = $street_address;
	}
	
	function getCustomers_street_address() {
		return $this->customers_street_address;
	}
	
	function setCustomers_city($customers_city) {
		$this->customers_city = $customers_city;
	}
	
	function getCustomers_city() {
		return $this->customers_city;
	}
	
	function setCustomers_zipcode($customers_zipcode) {
		$this->customers_zipcode = $customers_zipcode;
	}
	
	function getCustomers_zipcode() {
		return $this->customers_zipcode;
	}
	
	function setCustomers_country($customers_country) {
		$this->customers_country = $customers_country;
	}
	
	function getCustomers_country() {
		return $this->customers_country;
	}
	
	function setCustomers_telephone($customers_telephone) {
		$this->customers_telephone = $customers_telephone;
	}
	
	function getCustomers_telephone() {
		return $this->customers_telephone;
	}
	
	function setCustomers_email($customers_email) {
		$this->customers_email = $customers_email;
	}
	
	function getCustomers_email() {
		return $this->customers_email;
	}
	
	function setDelivery_name($delivery_name) {
		$this->delivery_name = $delivery_name;
	}
	
	function getDelivery_name() {
		return $this->delivery_name;
	}
	
	function setDelivery_company($delivery_company) {
		$this->delivery_company = $delivery_company;
	}
	
	function getDelivery_company() {
		return $this->delivery_company;
	}
	
	function setDelivery_street_address($street_address) {
		$this->delivery_street_address = $street_address;
	}
	
	function getDelivery_street_address() {
		return $this->delivery_street_address;
	}
	
	function setDelivery_city($delivery_city) {
		$this->delivery_city = $delivery_city;
	}
	
	function getDelivery_city() {
		return $this->delivery_city;
	}
	
	function setDelivery_zipcode($delivery_zipcode) {
		$this->delivery_zipcode = $delivery_zipcode;
	}
	
	function getDelivery_zipcode() {
		return $this->delivery_zipcode;
	}
	
	function setDelivery_country($delivery_country) {
		$this->delivery_country = $delivery_country;
	}
	
	function getDelivery_country() {
		return $this->delivery_country;
	}
	
	function setBilling_name($billing_name) {
		$this->billing_name = $billing_name;
	}
	
	function getBilling_name() {
		return $this->billing_name;
	}
	
	function setBilling_company($billing_company) {
		$this->billing_company = $billing_company;
	}
	
	function getBilling_company() {
		return $this->billing_company;
	}
	
	function setBilling_street_address($street_address) {
		$this->billing_street_address = $street_address;
	}
	
	function getBilling_street_address() {
		return $this->billing_street_address;
	}
	
	function setBilling_city($billing_city) {
		$this->billing_city = $billing_city;
	}
	
	function getBilling_city() {
		return $this->billing_city;
	}
	
	function setBilling_zipcode($billing_zipcode) {
		$this->billing_zipcode = $billing_zipcode;
	}
	
	function getBilling_zipcode() {
		return $this->billing_zipcode;
	}
	
	function setBilling_country($billing_country) {
		$this->billing_country = $billing_country;
	}
	
	function getBilling_country() {
		return $this->billing_country;
	}
	
	function setPayment_method($payment_method) {
		$this->payment_method = $payment_method;
	}
	
	function getPayment_method() {
		return $this->payment_method;
	}
	
	function setCc_type($cc_type) {
		$this->cc_type = $cc_type;
	}
	
	function getCc_type() {
		return $this->cc_type;
	}
	
	function setCc_owner($cc_owner) {
		$this->cc_owner = $cc_owner;
	}
	
	function getCc_owner() {
		return $this->cc_owner;
	}
	
	function setCc_number($cc_number) {
		$this->cc_number = $cc_number;
	}
	
	function getCc_number() {
		return $this->cc_number;
	}
	
	function setCc_controle_number($cc_controle_number) {
		$this->cc_controle_number = $cc_controle_number;
	}
	
	function getCc_controle_number() {
		return $this->cc_controle_number;
	}
	
	function setCc_expires($cc_expires) {
		$this->cc_expires = $cc_expires;
	}
	
	function getCc_expires() {
		return $this->cc_expires;
	}
	
	function setOrders_status($orders_status) {
		$this->orders_status = $orders_status;
	}
	
	function getOrders_status() {
		return $this->orders_status;
	}
	
	function setOrders_date_finished($orders_date_finished) {
		$this->orders_date_finished = $orders_date_finished;
	}
	
	function getOrders_date_finished() {
		return $this->orders_date_finished;
	}
	
	function setCurrency($currency) {
		$this->currency = $currency;
	}
	
	function getCurrency() {
		return $this->currency;
	}
	
	function setCurrency_value($currency_value) {
		$this->currency_value = $currency_value;
	}
	
	function getCurrency_value() {
		return $this->currency_value;
	}
	
	function setTotal_amount($total_amount) {
		$this->total_amount = $total_amount;
	}
	
	function getTotal_amount() {
		return $this->total_amount;
	}
	
	function setTotal_tax($total_tax) {
		$this->total_tax = $total_tax;
	}
	
	function getTotal_tax() {
		return $this->total_tax;
	}
	
	function load($id) {
		$obj = new Order();
		$obj->_load($id);
		$sql = "select * from orders where object_id=".$id;
		$row = Database::selectFirst($sql);
		if ($row) {
			$obj->order_no=$row['order_no'];
			$obj->customers_id=$row['customers_id'];
			$obj->customers_name=$row['customers_name'];
			$obj->customers_company=$row['customers_company'];
			$obj->customers_street_address=$row['customers_street_address'];
			$obj->customers_city=$row['customers_city'];
			$obj->customers_zipcode=$row['customers_postcode'];
			$obj->customers_country=$row['customers_country'];
			$obj->customers_telephone=$row['customers_telephone'];
			$obj->customers_email=$row['customers_email'];
			$obj->delivery_name=$row['delivery_name'];
			$obj->delivery_company=$row['delivery_company'];
			$obj->delivery_street_address=$row['delivery_street_address'];
			$obj->delivery_city=$row['delivery_city'];
			$obj->delivery_zipcode=$row['delivery_postcode'];
			$obj->delivery_country=$row['delivery_country'];
			$obj->billing_name=$row['billing_name'];
			$obj->billing_company=$row['billing_company'];
			$obj->billing_street_address=$row['billing_street_address'];
			$obj->billing_city=$row['billing_city'];
			$obj->billing_zipcode=$row['billing_postcode'];
			$obj->billing_country=$row['billing_country'];
			$obj->payment_method=$row['payment_method'];
			$obj->cc_type=$row['cc_type'];
			$obj->cc_owner=$row['cc_owner'];
			$obj->cc_number=$row['cc_number'];
			$obj->cc_controle_number=$row['cc_controle_number'];
			$obj->cc_expires=$row['cc_expires'];
			$obj->orders_status=$row['orders_status'];
			$obj->orders_date_finished=$row['orders_date_finished'];
			$obj->currency=$row['currency'];
			$obj->currency_value=$row['currency_value'];
			$obj->total_amount=$row['total_amount'];
			$obj->total_tax=$row['total_tax'];
		}
		return $obj;
	}
	
	function sub_create() {

		$sql = "insert into orders (object_id, order_no, customers_id, customers_name,customers_company,customers_street_address,customers_city,customers_postcode,customers_country,".
				"customers_telephone,customers_email,delivery_name,delivery_company,delivery_street_address,delivery_city,delivery_postcode,".
				"delivery_country,billing_name,billing_company,billing_street_address,billing_city,billing_postcode,billing_country,payment_method,".
				"cc_type,cc_owner,cc_number,cc_controle_number,cc_expires,orders_status,orders_date_finished,currency,currency_value,total_amount,total_tax) values (".
				$this->id.
				",".Database::int($this->order_no).
				",".Database::int($this->customers_id).
				",".Database::text($this->customers_name).
				",".Database::text($this->customers_company).
				",".Database::text($this->customers_street_address).
				",".Database::text($this->customers_city).
				",".Database::text($this->customers_zipcode).
				",".Database::text($this->customers_country).
				",".Database::text($this->customers_telephone).
				",".Database::text($this->customers_email).
				",".Database::text($this->delivery_name).
				",".Database::text($this->delivery_company).
				",".Database::text($this->delivery_street_address).
				",".Database::text($this->delivery_city).
				",".Database::text($this->delivery_zipcode).
				",".Database::text($this->delivery_country).
				",".Database::text($this->billing_name).
				",".Database::text($this->billing_company).
				",".Database::text($this->billing_street_address).
				",".Database::text($this->billing_city).
				",".Database::text($this->billing_zipcode).
				",".Database::text($this->billing_country).
				",".Database::text($this->payment_method).
				",".Database::text($this->cc_type).
				",".Database::text($this->cc_owner).
				",".Database::text($this->cc_number).
				",".Database::text($this->cc_controle_number).
				",".Database::text($this->cc_expires).
				",".Database::int($this->orders_status).
				",".$this->orders_date_finished.
				",".Database::text($this->currency).
				",".Database::text($this->currency_value).
				",".Database::text($this->total_amount).
				",".Database::text($this->total_tax).
				")";
		Database::insert($sql);
	}
	
	function sub_update() {
		$sql="update orders set".
			" order_no=".Database::int($this->order_no).
			",customers_id=".Database::text($this->customers_id).
			",customers_name=".Database::text($this->customers_name).
			",customers_company=".Database::text($this->customers_company).
			",customers_street_address=".Database::text($this->customers_street_address).
			",customers_city=".Database::text($this->customers_city).
			",customers_zipcode=".Database::text($this->customers_postcode).
			",customers_country=".Database::text($this->customers_country).
			",customers_telephone=".Database::text($this->customers_telephone).
			",customers_email=".Database::text($this->customers_email).
			",delivery_name=".Database::text($this->delivery_name).
			",delivery_company=".Database::text($this->delivery_company).
			",delivery_street_address=".Database::text($this->delivery_street_address).
			",delivery_city=".Database::text($this->delivery_city).
			",delivery_zipcode=".Database::text($this->delivery_postcode).
			",delivery_country=".Database::text($this->delivery_country).
			",billing_name=".Database::text($this->billing_name).
			",billing_company=".Database::text($this->billing_company).
			",billing_street_address=".Database::text($this->billing_street_address).
			" billing_city=".Database::text($this->billing_city).
			",billing_zipcode=".Database::text($this->billing_postcode).
			",billing_country=".Database::text($this->billing_country).
			",payment_method=".Database::text($this->payment_method).
			",cc_type=".Database::text($this->cc_type).
			",cc_owner=".Database::text($this->cc_owner).
			",cc_number=".Database::text($this->cc_number).
			",cc_controle_number=".Database::text($this->cc_controle_number).
			",orders_date_finished=".Database::text($this->orders_date_finished).
			",currency=".Database::text($this->currency).
			",currency_value=".Database::text($this->currency_value).
			",total_amount=".Database::text($this->total_amount).
			",total_tax=".Database::text($this->total_tax).			
			" where object_id=".$this->id;
		Database::update($sql);
	}
				
	function sub_publish() {
	
		$data = '<order xmlns="'.parent::_buildnamespace('1.0').'">';
		if ($this->order_no!='') {
			$data.='<order_no>'.encodeXML($this->order_no).'</order_no>';
		}		
		if ($this->customers_id!='') {
			$data.='<customers_id>'.encodeXML($this->customers_id).'</customers_id>';
		}
		if ($this->customers_name!='') {
			$data.='<customers_name>'.encodeXML($this->customers_name).'</customers_name>';
		}
		if ($this->customers_company!='') {
			$data.='<customers_company>'.encodeXML($this->customers_company).'</customers_company>';
		}
		if ($this->customers_street_address!='') {
			$data.='<customers_street_address>'.encodeXML($this->customers_street_address).'</customers_street_address>';
		}
		if ($this->customers_zipcode!='') {
			$data.='<customers_zipcode>'.encodeXML($this->customers_zipcode).'</customers_zipcode>';
		}
		if ($this->customers_country!='') {
			$data.='<customers_country>'.encodeXML($this->customers_country).'</customers_country>';
		}
		if (isset($this->customers_telephone)) {
			$data.='<customers_telephone>'.($this->customers_telephone).'</customers_telephone>';
		}
		if ($this->customers_email!='') {
			$data.='<customers_email>'.encodeXML($this->customers_email).'</customers_email>';
		}
		if ($this->delivery_name!='') {
			$data.='<delivery_name>'.encodeXML($this->delivery_name).'</delivery_name>';
		}
		if ($this->delivery_company!='') {
			$data.='<delivery_company>'.encodeXML($this->delivery_company).'</delivery_company>';
		}
		if ($this->delivery_street_address!='') {
			$data.='<delivery_street_address>'.encodeXML($this->delivery_street_address).'</delivery_street_address>';
		}
		if ($this->delivery_city!='') {
			$data.='<delivery_city>'.encodeXML($this->delivery_city).'</delivery_city>';
		}
		if ($this->delivery_zipcode!='') {
			$data.='<delivery_zipcode>'.encodeXML($this->delivery_zipcode).'</delivery_zipcode>';
		}				
		if ($this->delivery_country!='') {
			$data.='<delivery_country>'.encodeXML($this->delivery_country).'</delivery_country>';
		}
		if ($this->billing_name!='') {
			$data.='<billing_name>'.encodeXML($this->billing_name).'</billing_name>';
		}
		if ($this->billing_company!='') {
			$data.='<billing_company>'.encodeXML($this->billing_company).'</billing_company>';
		}
		if ($this->billing_street_address!='') {
			$data.='<billing_street_address>'.encodeXML($this->billing_street_address).'</billing_street_address>';
		}
		if ($this->billing_city!='') {
			$data.='<billing_city>'.encodeXML($this->billing_city).'</billing_city>';
		}
		if ($this->billing_zipcode!='') {
			$data.='<billing_zipcode>'.encodeXML($this->billing_zipcode).'</billing_zipcode>';
		}
		if ($this->billing_country!='') {
			$data.='<billing_country>'.encodeXML($this->billing_country).'</billing_country>';
		}
		if ($this->payment_method!='') {
			$data.='<payment_method>'.encodeXML($this->payment_method).'</payment_method>';
		}
		if ($this->cc_type!='') {
			$data.='<cc_type>'.encodeXML($this->cc_type).'</cc_type>';
		}
		if ($this->cc_owner!='') {
			$data.='<cc_owner>'.encodeXML($this->cc_owner).'</cc_owner>';
		}
		if ($this->cc_number!='') {
			$data.='<cc_number>'.encodeXML($this->cc_number).'</cc_number>';
		}
		if ($this->cc_controle_number!='') {
			$data.='<cc_controle_number>'.encodeXML($this->cc_controle_number).'</cc_controle_number>';
		}
		if ($this->cc_expires!='') {
			$data.='<cc_expires>'.encodeXML($this->cc_expires).'</cc_expires>';
		}
		if ($this->orders_status!='') {
			$data.='<orders_status>'.encodeXML($this->orders_status).'</orders_status>';
		}
		if ($this->orders_date_finished!='') {
			$data.='<orders_date_finished>'.encodeXML($this->orders_date_finished).'</orders_date_finished>';
		}
		if ($this->currency!='') {
			$data.='<currency>'.encodeXML($this->currency).'</currency>';
		}
		if ($this->currency_value!='') {
			$data.='<currency_value>'.encodeXML($this->currency_value).'</currency_value>';
		}
		if ($this->total_amount!='') {
			$data.='<total_amount>'.encodeXML($this->total_amount).'</total_amount>';
		}
		if ($this->total_tax!='') {
			$data.='<total_tax>'.encodeXML($this->total_tax).'</total_tax>';
		}
		$data.='</order>';
		return $data;		
		
	}
	
	function sub_remove() {
		$sql = "delete from orders where object_id=".$this->id;
		Database::delete($sql);
	}
	
	/////////////////////////// GUI /////////////////////////
	
}
?>