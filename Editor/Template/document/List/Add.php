<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
$sql="insert into document_list (page_id,section_id,type) values (".$pageId.",".$sectionId.",'disc')";
Database::insert($sql);
?>