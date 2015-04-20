<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Templates
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class DocumentTemplateEditor
{
  static function deleteRow($rowId) {
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
        if ($part = Part::get($partType,$partId)) {
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
  
  static function addPartAtEnd($pageId,$part) {
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
  static function addSection($columnId,$index,$partType) {
    $ctrl = PartService::getController($partType);
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
  
  /**
   * @return The id of the section
   */
  static function addSectionFromPart($columnId,$index,$part) {    
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
    
    $sql = "insert into document_section (`page_id`,`column_id`,`index`,`type`,`part_id`) values (".Database::int($pageId).",".Database::int($columnId).",".Database::int($index).",'part',".Database::int($part->getId()).")";
    $sectionId = Database::insert($sql);

    $sql="update page set changed=now() where id=".Database::int($pageId);
    Database::update($sql);
    
    return $sectionId;
  }
  
  
  
  static function deleteColumn($columnId) {
    $sql="select * from document_column where id=".Database::int($columnId);
    $row = Database::selectFirst($sql);
    if (!$row) {
      Log::debug('Column with id='.$columnId.' not found!');
    }
    $index = $row['index'];
    $rowId = $row['row_id'];
    $pageId = $row['page_id'];
    
    $sql = "select count(id) as num from document_column where row_id=".Database::int($rowId);
    $c = Database::selectFirst($sql);
    if (!$c) {
      Log::debug('No columns found in row');
      return;
    }
    if ($c['num']<2) {
      Log::debug('Aborting delete of last column');
      return;
    }
    

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
        if ($part = Part::get($partType,$partId)) {
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
  
  static function getSection($sectionId) {
    $sql="select document_section.*,part.type as part_type from document_section left join part on part.id = document_section.part_id where document_section.id=".Database::int($sectionId);
    return Database::selectFirst($sql);
  }
  
  static function deleteSection($sectionId) {

    $row = DocumentTemplateEditor::getSection($sectionId);
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
      if ($part = Part::get($partType,$partId)) {
        $part->remove();
      }
    }
  }
  
  static function addRow($pageId,$index) {
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
  
  static function addColumn($rowId,$index) {
    $sql="select * from document_row where id=".Database::int($rowId);
    $row = Database::selectFirst($sql);
    if (!$row) {
      Log::debug('Row not found');
      return;
    }
    $pageId = $row['page_id'];

    
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
  
  static function moveSection($sectionId,$direction) {
    $sql="select * from document_section where id=".Database::int($sectionId);
    $row = Database::selectFirst($sql);
    if (!$row || ($direction!==1 && $direction!==-1)) {
      return;
    }

    $index = $row['index'];
    $column = $row['column_id'];
    $newIndex = $row['index']+$direction;
  
    $sql="select * from document_section where `index`=".Database::int($newIndex)." and column_id=".Database::int($column);
    $row_next = Database::selectFirst($sql);
  
    if ($row_next) {
      $next_id = $row_next['id'];
      Database::update("update document_section set `index`=".Database::int($newIndex)." where id=".Database::int($sectionId));
      Database::update("update document_section set `index`=".Database::int($index)." where id=".Database::int($next_id));
      $sql="update page set changed=now() where id=".Database::int($row['page_id']);
      Database::update($sql);
    }
  }


  /**
   * The indices are Zero-based!
   * @param $params Example: array('sectionId' => «id»,'rowIndex' => «int»,'columnIndex' => «int»,'sectionIndex' => «int»)
   */
  static function moveSectionFar($params) {
    Log::debug($params);
    $sql="select * from document_section where id=".Database::int($params['sectionId']);
    $section = Database::selectFirst($sql);
    if (!$section) {
      Log::debug('The sesion with id='.$params['sectionId'].' could not be found');
      return;
    }
    $pageId = $section['page_id'];
    $sql="select * from document_row where page_id=".Database::int($pageId)." order by `index`";
    $rows = Database::selectAll($sql);
    $row = @$rows[$params['rowIndex']];
    if (!$row) {
      Log::debug('Row not found: pageId='.$pageId.', rowIndex='.$params['rowIndex'].'. Row count is '.count($rows));
      return;
    }
    $sql="select * from document_column where row_id=".Database::int($row['id'])." order by `index`";
    $columns = Database::selectAll($sql);
    
    $column = @$columns[$params['columnIndex']];

    if (!$column) {
      Log::debug('Column not found: pageId='.$pageId.', rowIndex='.$params['rowIndex'].', columnIndex='.$params['columnIndex'].'. Column count is '.count($columns));
      return;
    }
    
    $sql="select * from document_section where column_id=".Database::int($column['id'])." and `index`>".Database::int($params['sectionIndex'])." order by `index`";
    $sections = Database::selectAll($sql);
    for ($i=0; $i < count($sections); $i++) {
      $sec = $sections[$i];
      $sql = "update document_section set `index`=".Database::int($sec['index']+1)." where id = ".Database::int($sec['id']);
      Database::update($sql);
    }

    $sql = "update document_section set `index`=".Database::int($params['sectionIndex']+1).",column_id=".Database::int($column['id'])." where id = ".Database::int($section['id']);
    Database::update($sql);
    
    DocumentTemplateEditor::_rebuildColumn($column['id']);
    DocumentTemplateEditor::_rebuildColumn($section['column_id']);
    
    $sql="update page set changed=now() where id=".Database::int($pageId);
    Database::update($sql);
  }
  
  static function _rebuildColumn($id) {
    $sql="select * from document_section where column_id=".Database::int($id)." order by `index`";
    $sections = Database::selectAll($sql);
    for ($i=0; $i < count($sections); $i++) { 
      $sql = "update document_section set `index`=".Database::int($i+1)." where id = ".Database::int($sections[$i]['id']);
      Database::update($sql);
    }
  }

  
  static function moveRow($rowId,$direction) {
    $sql="select * from document_row where id=".Database::int($rowId);
    $row = Database::selectFirst($sql);
    if (!$row || ($direction!==1 && $direction!==-1)) {
      Log::debug('Unable to move row');
      return;
    }

    $index = $row['index'];
    $newIndex = $index+$direction;
    $sql="select * from document_row where `index`=".Database::int($newIndex)." and page_id=".Database::int($row['page_id']);

    $row_next = Database::selectFirst($sql);
    if ($row_next) {
      $next_id = $row_next['id'];
      Database::update("update document_row set `index`=".Database::int($newIndex)." where id=".Database::int($rowId));
      Database::update("update document_row set `index`=".Database::int($index)." where id=".Database::int($next_id));
      $sql="update page set changed=now() where id=".Database::int($row['page_id']);
      Database::update($sql);
    } else {
      Log::debug('The other row was not found!');
    }
  }
  
  static function moveColumn($columnId,$direction) {
    $sql = "select * from document_column where id=".Database::int($columnId);
    $row = Database::selectFirst($sql);
    if (!$row || ($direction!==1 && $direction!==-1)) {
      return;
    }

    $index = $row['index'];
    $newIndex = $index+$direction;
    $rowId = $row['row_id'];

    $sql="select * from document_column where `index`=".Database::int($newIndex)." and row_id=".Database::int($rowId);
    $row_next = Database::selectFirst($sql);
    if ($row_next) {
      $next_id = $row_next['id'];
      Database::update("update document_column set `index`=".Database::int($newIndex)." where id=".Database::int($columnId));
      Database::update("update document_column set `index`=".Database::int($index)." where id=".Database::int($next_id));

      $sql="update page set changed=now() where id=".Database::int($row['page_id']);
      Database::update($sql);
    }
  }
  
  static function loadColumn($id) {
    $sql = "select * from document_column where id=".Database::int($id);
    if ($row = Database::selectFirst($sql)) {
      return array(
        'id' => intval($row['id']),
        'width' => $row['width'],
        'top' => $row['top'],
        'bottom' => $row['bottom'],
        'left' => $row['left'],
        'right' => $row['right']
      );
    }
    return null;
  }
  
  static function loadRow($id) {
    $sql = "select * from document_row where id=".Database::int($id);
    return Database::selectFirst($sql);
  }
  
  static function updateColumn($column) {
    $sql = "select * from document_column where id=".Database::int($column['id']);
    if ($row = Database::selectFirst($sql)) {
      $sql="update document_column set width=".Database::text($column['width']).
        ",`top`=".Database::text($column['top']).
        ",`bottom`=".Database::text($column['bottom']).
        ",`left`=".Database::text($column['left']).
        ",`right`=".Database::text($column['right']).
        " where id=".Database::int($column['id']);
      Database::update($sql);
      $sql="update page set changed=now() where id=".Database::int($row['page_id']);
      Database::update($sql);
    } else {
      Log::debug('Column not found...');
    }
  }
  
  static function updateRow($info) {
    
    $sql = "select * from document_row where id=@int(id)";
    if ($row = Database::selectFirst($sql,['id' => $info['id']])) {
      $sql="update document_row set ".
        "`top`=".Database::text($info['top']).
        ",`bottom`=".Database::text($info['bottom']).
        ",`spacing`=".Database::text($info['spacing']).
        " where id=".Database::int($info['id']);
      Database::update($sql);
      $sql="update page set changed=now() where id=".Database::int($row['page_id']);
      Database::update($sql);
    } else {
      Log::debug('Row not found...');
    }
  }
  
  static function getStructure($id) {
    $sql = "select 
      document_row.id as row_id, 
      document_row.index as row_index, 
      document_row.top as row_top,
      document_row.bottom as row_bottom,
      document_row.spacing as row_spacing,

      document_column.id as column_id, 
      document_column.index as column_index, 
      document_column.top as column_top,
      document_column.bottom as column_bottom, 
      document_column.left as column_left,
      document_column.right as column_right,
      document_column.width as column_width,

      document_section.id as section_id,
      document_section.index as section_index,
      document_section.type as section_type,
      document_section.top as section_top, 
      document_section.bottom as section_bottom, 
      document_section.left as section_left,
      document_section.right as section_right,
      document_section.width as section_width,
      document_section.float as section_float,
      document_section.part_id as part_id,
      part.type as part_type

      from document_row 

      left join document_column on document_row.id = row_id
      left join document_section on document_column.id = column_id 
      left join part on document_section.part_id = part.id

      where document_row.page_id=".Database::int($id)." 

      order by document_row.index, document_column.index, document_section.index";
    
    $structure = array(); 
    
    $result = Database::select($sql);
    while ($line = Database::next($result)) {
      $row = null;
      $column = null;
      $rowIndex = intval($line['row_index']);
      $columnIndex = intval($line['column_index']);
      $sectionIndex = intval($line['section_index']);
      
      if (!isset($structure[$rowIndex])) {
        $structure[$rowIndex] = array(
          'id' => intval($line['row_id']),
          'index' => $rowIndex,
          'top' => $line['row_top'],
          'bottom' => $line['row_bottom'],
          'spacing' => $line['row_spacing'],
          'columns' => array()
        );
      }     
      
      if (!isset($structure[$rowIndex]['columns'][$columnIndex])) {
        $structure[$rowIndex]['columns'][$columnIndex] = array(
          'id' => $line['column_id'],
          'index' => $columnIndex,
          'width' => $line['column_width'],
          'top' => $line['column_top'],
          'bottom' => $line['column_bottom'],
          'left' => $line['column_left'],
          'right' => $line['column_right'],
          'sections' => array()
        );
      }
      
      if (!isset($structure[$rowIndex]['columns'][$columnIndex]['sections'][$sectionIndex])) {
        $structure[$rowIndex]['columns'][$columnIndex]['sections'][$sectionIndex] = array(
          'id' => $line['section_id'],
          'index' => $sectionIndex,
          'width' => $line['section_width'],
          'float' => $line['section_float'],
          'top' => $line['section_top'],
          'bottom' => $line['section_bottom'],
          'left' => $line['section_left'],
          'right' => $line['section_right'],
          'partType' => $line['part_type'],
          'partId' => $line['part_id']
        );
      }
    }
    Database::free($result);
    
    return $structure;
  }
}