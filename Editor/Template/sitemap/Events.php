<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Sitemap
 */
if ($type=='hierarchy') {
	$sql = "update page,template set page.changed=now() where page.template_id=template.id and template.unique='sitemap'";
	Database::update($sql);
}
?>