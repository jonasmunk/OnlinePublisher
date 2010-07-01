<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
$sql="insert into document_image (page_id,section_id,image_id) values (".$pageId.",".$sectionId.",0)";
Database::insert($sql);
?>