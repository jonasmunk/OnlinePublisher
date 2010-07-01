<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
$sql="insert into document_news (page_id,section_id,mode,sortdir,sortby,timetype) values (".$pageId.",".$sectionId.",'single','ascending','startdate','always')";
Database::insert($sql);
?>