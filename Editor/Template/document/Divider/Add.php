<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
$sql="insert into document_divider (page_id,section_id) values (".$pageId.",".$sectionId.")";
Database::insert($sql);
?>