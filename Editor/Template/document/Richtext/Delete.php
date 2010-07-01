<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
$sql="delete from document_richtext where section_id=".$sectionId;
Database::delete($sql);
?>