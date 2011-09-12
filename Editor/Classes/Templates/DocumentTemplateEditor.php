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
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/Parts/Part.php');
require_once($basePath.'Editor/Classes/Services/PartService.php');

class DocumentTemplateEditor
{
	function deleteRow($rowId) {
		$sql="select * from document_row where id=".Database::int($rowId);
		$row = Database::selectFirst($sql);
		if (!$row) {
			Log::debug('Row not found');
			return;
		}
		$index=$row['index'];
		$pageId=$row['page_id'];

		$sql="select count(id) as num from document_row where page_id=".Database::int($pageId);
		$row = Database::selectFirst($sql);
		if ($row['num']<2) {
			// Cannot delete the last row
			return;
		}

		$latestRow=0;

		$sql="select * from document_row where page_id=".Database::int($pageId)." and `index`>".Database::int($index);
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$sql="update document_row set `index`=".Database::int($row['index']-1)." where id=".Database::int($row['id']);
			Database::update($sql);
			$latestRow=$row['id'];
		}
		Database::free($result);

		$sql="select document_section.*,part.type as part_type from document_section left join part on part.id=document_section.part_id left join document_column on document_section.column_id=document_column.id where document_column.row_id=".Database::int($rowId);
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$type=$row['type'];
			$sectionId=$row['id'];
			$partId=$row['part_id'];
			$partType=$row['part_type'];
			if ($type=='part') {
				if ($part = Part::load($partType,$partId)) {
					$part->remove();
				}
			}
			$sql="delete from document_section where id=".Database::int($sectionId);
			Database::delete($sql);
		}
		Database::free($result);

		$sql="delete from document_column where row_id=".Database::int($rowId);
		Database::delete($sql);

		$sql="delete from document_row where id=".Database::int($rowId);
		Database::delete($sql);

		$sql="update page set changed=now() where id=".Database::int($pageId);
		Database::update($sql);
	}
	
	function addPartAtEnd($pageId,$part) {
		if (!$part->isPersistent()) {
			Log::debug('The part is not persistent!');
			return;
		}
		$sql="select id from document_row where page_id=".Database::int($pageId)." order by `index` desc";
		if ($row = Database::selectFirst($sql)) {
			$rowId = $row['id'];
			$sql="select id from document_column where row_id=".Database::int($rowId)." order by `index` desc";
			if ($row = Database::selectFirst($sql)) {
				$columnId = $row['id'];
				$sql = "select max(`index`) as `index` from document_section where column_id=".Database::int($columnId);
				$index = 1;
				if ($row = Database::selectFirst($sql)) {
					$index = $row['index']+1;
				}
				$sql="insert into document_section (`page_id`,`column_id`,`index`,`type`,`part_id`) values (".Database::int($pageId).",".Database::int($columnId).",".Database::int($index).",'part',".Database::int($part->getId()).")";
				$sectionId=Database::insert($sql);
				
				$sql="update page set changed=now() where id=".Database::int($pageId);
				Database::update($sql);
			} else {
				Log::debug('No column found for first row of page='.$pageId);
			}
		} else {
			Log::debug('No rows found for page='.$pageId);
		}
	}
	
	/**
	 * @return The id of the section
	 */
	function addSection($columnId,$index,$part) {
		$ctrl = PartService::getController($part);
		if (!$ctrl) {
			Log::debug('Controller not found');
			return null;
		}
		
		$sql="select page_id from document_column where id=".Database::int($columnId);
		if ($row = Database::selectFirst($sql)) {
			$pageId = $row['page_id'];
		} else {
			Log::debug('Column not found');
			return null;
		}
		
		$sql="select * from document_section where column_id=".Database::int($columnId)." and `index`>=".Database::int($index);
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$sql="update document_section set `index`=".Database::int($row['index']+1)." where id=".Database::int($row['id']);
			Database::update($sql);
		}
		Database::free($result);
		$sectionId = null;
		if ($part = $ctrl->createPart()) {
			$sql="insert into document_section (`page_id`,`column_id`,`index`,`type`,`part_id`) values (".Database::int($pageId).",".Database::int($columnId).",".Database::int($index).",'part',".Database::int($part->getId()).")";
			$sectionId=Database::insert($sql);
		}

		$sql="update page set changed=now() where id=".Database::int($pageId);
		Database::update($sql);
		
		return $sectionId;
	}
	
	function deleteColumn($columnId) {
		$sql="select * from document_column where id=".Database::int($columnId);
		$row = Database::selectFirst($sql);
		if (!$row) {
			Log::debug('Column with id='.$columnId.' not found!');
		}
		$index = $row['index'];
		$rowId = $row['row_id'];
		$pageId = $row['page_id'];
		

		$sql="select * from document_column where row_id=".Database::int($rowId)." and `index`>".Database::int($index);
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$sql="update document_column set `index`=".Database::int($row['index']-1)." where id=".Database::int($row['id']);
			Database::update($sql);
		}
		Database::free($result);


		$sql="select document_section.*,part.type as part_type from document_section left join part on part.id=document_section.part_id where column_id=".Database::int($columnId);
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$type = $row['type'];
			$sectionId = $row['id'];
			$partType = $row['part_type'];
			$partId = $row['part_id'];
			if ($type=='part') {
				if ($part = Part::load($partType,$partId)) {
					$part->remove();
				}
			}
		}
		Database::free($result);


		$sql="delete from document_section where column_id=".Database::int($columnId);
		Database::delete($sql);

		$sql="delete from document_column where id=".Database::int($columnId);
		Database::delete($sql);

		$sql="update page set changed=now() where id=".Database::int($pageId);
		Database::update($sql);
	}
	
	function deleteSection($sectionId) {

		$sql="select document_section.*,part.type as part_type from document_section left join part on part.id = document_section.part_id where document_section.id=".Database::int($sectionId);
		$row = Database::selectFirst($sql);
		if (!$row) {
			Log::debug('Unable to find section with id='.$sectionId);
			return;
		}
		$index = $row['index'];
		$type = $row['type'];
		$columnId = $row['column_id'];
		$pageId = $row['page_id'];
		$partType = $row['part_type'];
		$partId = $row['part_id'];

		$sql="select * from document_section where column_id=".Database::int($columnId)." and `index`>".Database::int($index);
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$sql="update document_section set `index`=".Database::int($row['index']-1)." where id=".Database::int($row['id']);
			Database::update($sql);
		}
		Database::free($result);

		$sql="delete from document_section where id=".Database::int($sectionId);
		Database::delete($sql);

		$sql="update page set changed=now() where id=".Database::int($pageId);
		Database::update($sql);

		if ($type=='part') {
			if ($part = Part::load($partType,$partId)) {
				$part->remove();
			}
		}
	}
	
	function addRow($pageId,$index) {
		if (!PageService::exists($pageId)) {
			Log::debug('The page with id='.$pageId.' does not exist');
			return;
		}
		
		$sql="select * from document_row where page_id=".Database::int($pageId)." and `index`>=".Database::int($index);
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$sql="update document_row set `index`=".Database::int($row['index']+1)." where id=".Database::int($row['id']);
			Database::update($sql);
		}
		Database::free($result);

		$sql = "insert into document_row (page_id,`index`) values (".Database::int($pageId).",".Database::int($index).")";
		$rowId = Database::insert($sql);
		$sql = "insert into document_column (page_id,row_id,`index`) values (".Database::int($pageId).",".Database::int($rowId).",1)";
		$columnId = Database::insert($sql);
		$sql = "update page set changed=now() where id=".Database::int($pageId);
		Database::update($sql);
		return $rowId;
	}
	
	function addColumn($rowId,$index) {
		$sql="select * from document_row where id=".Database::int($rowId);
		$row = Database::selectFirst($sql);
		if (!$row) {
			Log::debug('Row not found');
			return;
		}
		$pageId=$row['page_id'];

		
		$sql="select * from document_column where row_id=".Database::int($rowId)." and `index`>=".Database::int($index);
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$sql="update document_column set `index`=".Database::int($row['index']+1)." where id=".Database::int($row['id']);
			Database::update($sql);
		}
		Database::free($result);

		$sql="insert into document_column (page_id,row_id,`index`) values (".Database::int($pageId).",".Database::int($rowId).",".Database::int($index).")";
		$columnId=Database::insert($sql);

		$sql="update page set changed=now() where id=".Database::int($pageId);
		Database::update($sql);
		
		return $columnId;
	}
}