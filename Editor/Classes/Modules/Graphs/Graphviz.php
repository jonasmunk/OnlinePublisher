<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class Graphviz {

	var $data;
	
	function Graphviz() {
		$this->data = array();
	}
	
	function canDisplay() {
		$hasNeato = SettingService::getSetting('system','environment','neato');
		return ($hasNeato == true);
	}
	
	function add($source,$target) {
		$this->data[] = array('source' => $source, 'target' => $target);
	}
	
	function toDot() {
		$dot = "digraph abstract {\n".
		"graph [normalize=true, outputorder=edgesfirst, overlap=false, pack=false, packmode=\"node\", sep=\"0.20\", splines=true, size=\"6,6\"];\n";
		"node [fillcolor=gray100, label=\"\\N\"];\n";
		foreach ($this->data as $item) {
			$dot.=$this->encodeDotLabel($item['source'])." -> ".$this->encodeDotLabel($item['target']).";\n";
		}
		$dot.="}";
		$dot = mb_convert_encoding($dot,'iso-8859-1','utf-8');
		return $dot;
	}
	
	function downloadDot($filename) {
		header('Content-Type: text/plain; charset: utf-8;');
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		echo $this->toDot();
	}
	
	function display($format) {
		$folder = new TemporaryFolder();
		if (!$dir = $folder->make()) {
			return false;
		}
		file_put_contents($dir.'graph.dot',$this->toDot());
		$this->execute('neato -o '.$dir.'graph.ps -Tps2 '.$dir.'graph.dot');
		if ($format=='pdf') {
			$this->execute('ps2pdf12 '.$dir.'graph.ps '.$dir.'graph.pdf');
			header('Content-Type: application/pdf;');
			readfile($dir.'graph.pdf');
		} else {
			$this->execute('convert -density 200x200 '.$dir.'graph.ps '.$dir.'graph.png');
			header('Content-Type: image/png;');
			readfile($dir.'graph.png');
		}
		$folder->remove();
	}
	
	function encodeDotLabel($str) {
		$str = str_replace("\"","\\\"",$str);
		return "\"".mb_convert_encoding($str, "UTF-8")."\"";
	}
	
	function execute($cmd) {
		$extraPath = SettingService::getSetting('system','environment','extrapath');
		if (strlen($extraPath)>0) {
			exec('export PATH="$PATH:'.$extraPath.'"; '.$cmd);
		} else {
			exec($cmd);
		}
	}
}
?>