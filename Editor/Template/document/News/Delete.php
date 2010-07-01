<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
$sql="delete from document_news where section_id=".$sectionId;
Database::delete($sql);
$sql="delete from document_news_newsgroup where section_id=".$sectionId;
Database::delete($sql);
?>