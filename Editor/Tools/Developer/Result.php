<?php
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once('../../Libraries/nusoap/nusoap.php');

$proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
$proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
$proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
$proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
$client = new nusoapclient("http://api.google.com/search/beta2", false,
						$proxyhost, $proxyport, $proxyusername, $proxypassword);
$err = $client->getError();
if ($err) {
	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
}
$client->soap_defencoding = 'UTF-8';

//echo 'You must set your own Google key in the source code to run this client!'; exit();
$params = array(
	'Googlekey'=>'s1GD5fhQFHLUIw1ZexgZRAzxRfUDTC76',
	'queryStr'=>requestPostText('query'),
	'startFrom'=>0,
	'maxResults'=>10,
	'filter'=>true,
	'restrict'=>'',
	'adultContent'=>true,
	'language'=>'',
	'iencoding'=>'',
	'oendcoding'=>''
);
$result = $client->call("doGoogleSearch", $params, "urn:GoogleSearch", "urn:GoogleSearch");
if ($client->fault) {
	echo '<h2>Fault</h2><pre>'; print_r($result); echo '</pre>';
} else {
	$err = $client->getError();
	if ($err) {
		echo '<h2>Error</h2><pre>' . $err . '</pre>';
	} else {
		//echo '<h2>Result</h2><pre>'; print_r($result); echo '</pre>';
		viewResults($result);
	}
}
//echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
//echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
//echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';

function viewResults($result) {
	global $xwg_skin;
	$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
	'<interface>'.
	'<list xmlns="uri:List" width="100%" margin="3" sort="true">'.
	'<content>'.
	'<headergroup>'.
	'<header title="Titel" width="30%"/>'.
	'<header title="Summary"/>'.
	'</headergroup>';
	$elements = $result['resultElements'];
	foreach($elements as $element) {
		$gui.='<row>'.
		'<cell>'.cleanGoogleResult($element['title']).'</cell>'.
		'<cell>'.cleanGoogleResult($element['summary']).'</cell>'.
		'</row>';
	}
	$gui.=
	'</content>'.
	'</list>'.
	'</interface>'.
	'</xmlwebgui>';

	$elements = array("List");
	writeGui($xwg_skin,$elements,$gui);
}

function cleanGoogleResult($text) {
	$text=str_replace('&#39;','\'',$text);
	$text=str_replace('&quot;','"',$text);
	$text=str_replace('<b>', '[b]', $text);
	$text=str_replace('</b>', '[/b]', $text);
	$text=str_replace('<br>', '', $text);
	$text=encodeXML($text);
	$text=str_replace('[b]', '<strong>', $text);
	$text=str_replace('[/b]', '</strong>', $text);
	return $text;//encodeXML($text);
}
?>
