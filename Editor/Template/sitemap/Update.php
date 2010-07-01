<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Sitemap
 */

require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Page.php';

$id = getPageId();
$title = requestPostText('title');
$text = requestPostText('text');
$file = requestPostNumber('file',0);
$width = requestPostNumber('width',0);
$height = requestPostNumber('height',0);


$sql="update sitemap set".
" title=".sqlText($title).
",text=".sqlText($text).
" where page_id=".$id;
Database::update($sql);


Page::markChanged($id);

redirect('Editor.php');
?>