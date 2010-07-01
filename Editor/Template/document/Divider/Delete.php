<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
$sql="delete from document_divider where section_id=".$sectionId;
Database::delete($sql);
?>