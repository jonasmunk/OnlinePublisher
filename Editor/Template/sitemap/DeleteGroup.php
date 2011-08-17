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

$id = Request::getInt('id');


// Load info about item
$sql="select * from sitemap_group where id=".$id;
$row = Database::selectFirst($sql);
$page=$row['page_id'];

// Delete item
$sql="delete from sitemap_group where id=".$id;
Database::delete($sql);

// Fix indexes
$sql="select id from sitemap_group where page_id=".$page." order by position";
$result = Database::select($sql);
$position=1;
while ($row = Database::next($result)) {
	$sql="update sitemap_group set position=".$position." where id=".$row['id'];
	Database::update($sql);
	$position++;
}
Database::free($result);
	

PageService::markChanged($page);

Response::redirect('Groups.php');
?>