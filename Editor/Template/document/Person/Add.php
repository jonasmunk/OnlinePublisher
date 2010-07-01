<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
$sql="insert into document_person (page_id,section_id) values (".$pageId.",".$sectionId.")";
Database::insert($sql);
?>