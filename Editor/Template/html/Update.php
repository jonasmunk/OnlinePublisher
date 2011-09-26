<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Html
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Database.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Core/InternalSession.php';
require_once '../../Classes/Core/Request.php';

$id = InternalSession::getPageId();
$title = Request::getString('title');
$html = Request::getString('html');

$valid = validateXML($html);

$sql="update html set".
" html=".Database::text($html).
",title=".Database::text($title).
",valid=".Database::boolean($valid).
" where page_id=".$id;
Database::update($sql);

$sql="update page set".
" changed=now()".
" where id=".$id;
Database::update($sql);

Response::redirect('Editor.php');

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