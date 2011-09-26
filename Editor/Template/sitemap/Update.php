<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Sitemap
 */

require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Database.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Model/Page.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Core/InternalSession.php';

$id = InternalSession::getPageId();
$title = Request::getString('title');
$text = Request::getString('text');
$file = Request::getInt('file',0);
$width = Request::getInt('width',0);
$height = Request::getInt('height',0);


$sql="update sitemap set".
" title=".Database::text($title).
",text=".Database::text($text).
" where page_id=".$id;
Database::update($sql);


PageService::markChanged($id);

Response::redirect('Editor.php');
?>