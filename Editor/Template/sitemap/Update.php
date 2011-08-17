<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Sitemap
 */

require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Page.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/InternalSession.php';

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