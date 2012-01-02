<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Core/Database.php');

class SelectBuilder {

	private $columns = array();
	private $tables = array();
	private $limits = array();
	private $orderings = array();
	private $from = null;
	private $to = null;
	
	function addColumn($column) {
		$this->columns[] = $column;
		return $this;
	}
	
	function addColumns($columns) {
		foreach ($columns as $column) {
			$this->columns[] = $column;
		}
		return $this;
	}
	
	function addTable($table) {
		$this->tables[] = $table;
		return $this;
	}
	
	function addLimit($limit) {
		$this->limits[] = $limit;
		return $this;
	}
	
	function addOrdering($column,$descending=false) {
		$this->orderings[] = array('column'=>$column,'descending'=>$descending);
		return $this;
	}
	
	function setFrom($from) {
		$this->from = $from;
		return $this;
	}
	
	function setTo($to) {
		$this->to = $to;
		return $this;
	}
	
	function clearColumns() {
		$this->columns = array();
		return $this;
	}
	
	function clearFromAndTo() {
		$this->from = null;
		$this->to = null;
		return $this;
	}
	
	function toSQL() {
		$sql = 'select ';
		for ($i=0; $i < count($this->columns); $i++) { 
			if ($i>0) {
				$sql.= ',';
			}
			$sql.=$this->columns[$i];
		}
		
		$sql.=' from ';
		for ($i=0; $i < count($this->tables); $i++) { 
			if ($i>0) {
				$sql.= ',';
			}
			$sql.=$this->tables[$i];
		}
		
		if (count($this->limits)>0) {
			$sql.=' where ';
			for ($i=0; $i < count($this->limits); $i++) { 
				if ($i>0) {
					$sql.=' and ';
				}
				$sql.= $this->limits[$i];
			}
		}
		if (count($this->orderings)>0) {
			$sql.=' order by ';
			for ($i=0; $i < count($this->orderings); $i++) { 
				if ($i>0) {
					$sql.=',';
				}
				$sql.= $this->orderings[$i]['column'];
				$sql.= $this->orderings[$i]['descending'] ? ' desc' : ' asc';
			}
		}
		if ($this->from!==null && $this->to!==null) {
			$sql.=' limit '.$this->from.','.$this->to;
		}
		return $sql;
	}
}
?>