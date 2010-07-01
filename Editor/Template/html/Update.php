<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Html
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';

$id = getPageId();
$title = requestPostText('title');
$html = requestPostText('html');

$valid = validateXML($html);

$sql="update html set".
" html=".sqlText($html).
",title=".sqlText($title).
",valid=".sqlBoolean($valid).
" where page_id=".$id;
Database::update($sql);

$sql="update page set".
" changed=now()".
" where id=".$id;
Database::update($sql);

redirect('Editor.php');

function validateXML($data) {
	$code=0;
	$parser = xml_parser_create();
	xml_parser_set_option($parser,XML_OPTION_CASE_FOLDING,0);
	xml_parser_set_option($parser,XML_OPTION_SKIP_WHITE,1);
	xml_parse_into_struct($parser,'<x>'.$data.'</x>',$values,$tags);
	$code=xml_get_error_code($parser);
	xml_parser_free($parser);
	if ($code==false) {
		return true;
	}
	else {
		return false;
	}
}
?>