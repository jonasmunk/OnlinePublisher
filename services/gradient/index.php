<?php
/**
 * @package OnlinePublisher
 * @subpackage Public
 */
require_once '../../Editor/Include/Public.php';

//http://62.107.96.100/~jbm/Publisher/src/util/gradient/?vertical=true&width=10&height=50&gradient=0,50,50,230;100,100,100,255

buildGradient();

function buildGradient() {
	$g = Request::getString('gradient');
	$cols = split(';',$g);
	$gradient = new Gradient;
	foreach($cols as $color) {
		$parts = split(",",$color);
		//error_log(print_r($parts,true));
		$gradient->addcolor($parts[1],$parts[2],$parts[3],$parts[0]);
	}
	//$gradient->addcolor(230,220,10,0);
	//$gradient->addcolor(240,0,20,50);
	//$gradient->addcolor(220,120,30,100);
	$gradient->vertical = Request::getBoolean('vertical');
	$display = $gradient->buildgradient(Request::getInt('height'),Request::getInt('width'));
	header('content-type: image/png');
	imagepng($display);
}
?>