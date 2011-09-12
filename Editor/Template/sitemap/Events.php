<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Sitemap
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
if ($type=='hierarchy') {
	$sql = "update page,template set page.changed=now() where page.template_id=template.id and template.unique='sitemap'";
	Database::update($sql);
}
?>