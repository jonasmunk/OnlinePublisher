<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Templates
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Templates/TemplateController.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class DocumentTemplateController extends TemplateController
{
	function DocumentTemplateController() {
		parent::TemplateController('document');
	}
	
	function import($id,$doc) {
		// First clear the document
		$this->removeAll($id);
				
		$rows = DOMUtils::getChildElements($doc->documentElement,'row');
		$rowPosition = 0;
		foreach ($rows as $row) {
			$rowPosition++;
						
			$sql = "insert into document_row (`index`,page_id) values (".Database::int($rowPosition).",".Database::int($id).")";
			$rowId = Database::insert($sql);
			
			$columns = DOMUtils::getChildElements($row,'column');
			$columnPosition = 0;
			// Lopp through all columns
			foreach ($columns as $column) {
				$columnPosition++;
				$width = $column->getAttribute('width');
				$sql = "insert into document_column (row_id,width,`index`,page_id) values (".Database::int($rowId).",".Database::text($width).",".Database::int($columnPosition).",".Database::int($id).")";
				$columnId = Database::insert($sql);
				
				$sectionPosition = 0;
				$sections = DOMUtils::getChildElements($column,'section');
				// Lopp through all sections
				foreach ($sections as $section) {
					if ($part = DOMUtils::getFirstChildElement($section,'part')) {
						$sectionPosition++;
						$type = $part->getAttribute('type');
						// Get a new Part objects
						
						if ($controller = PartService::getController($type)) {
							$obj = $controller->importFromNode($part);
							$obj->save();
							$sql = "insert into document_section (column_id,type,part_id,`index`,page_id) values (".$columnId.",'part',".$obj->getId().",".$sectionPosition.",".$id.")";
							Database::insert($sql);
						}
					}
				}
			}
		}
    }
    
	function removeAll($id) {
		$sql="delete from document_row where page_id=".$id;
		Database::delete($sql);
		$sql="delete from document_column where page_id=".$id;
		Database::delete($sql);

		$sql = "select part.id,part.type from document_section,part where part.id=document_section.part_id and document_section.page_id=".$id;
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$part = PartService::load($row['type'],$row['id']);
			$part->remove();
		}
		Database::free($result);

		$sql="delete from document_section where page_id=".$id;
		Database::delete($sql);
	}
}