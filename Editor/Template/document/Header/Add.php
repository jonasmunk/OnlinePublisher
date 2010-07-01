<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
$sql="insert into document_header (page_id,section_id,level) values (".$pageId.",".$sectionId.",1)";
Database::insert($sql);
?>