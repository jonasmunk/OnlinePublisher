<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
$sql="delete from document_header where section_id=".$sectionId;
Database::delete($sql);
?>