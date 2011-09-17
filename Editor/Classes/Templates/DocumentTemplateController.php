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
require_once($basePath.'Editor/Classes/PartContext.php');
require_once($basePath.'Editor/Classes/Services/PartService.php');

class DocumentTemplateController extends TemplateController
{
	function DocumentTemplateController() {
		parent::TemplateController('document');
	}
	
	function create($page) {
		$sql="insert into document (page_id) values (".Database::int($page->getId()).")";
		Database::insert($sql);
		
		$sql="insert into document_row (page_id,`index`) values (".Database::int($page->getId()).",1)";
		$rowId = Database::insert($sql);
		
		$sql="insert into document_column (page_id,`index`,row_id) values (".Database::int($page->getId()).",1,".Database::int($rowId).")";
		$columnId = Database::insert($sql);
	}
	
	function delete($page) {
		$this->removeAll($page->getId());
		$sql="delete from document where page_id=".Database::int($page->getId());
		Database::delete($sql);
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
		$sql="delete from document_row where page_id=".Database::int($id);
		Database::delete($sql);
		
		$sql="delete from document_column where page_id=".Database::int($id);
		Database::delete($sql);

		$sql = "select part.id,part.type from document_section,part where part.id=document_section.part_id and document_section.page_id=".Database::int($id);
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			if ($part = PartService::load($row['type'],$row['id'])) {
				$part->remove();
			}
		}
		Database::free($result);

		$sql="delete from document_section where page_id=".Database::int($id);
		Database::delete($sql);
	}
	
	function build($id) {
		$out = $this->getData($id);
        return array('data' => $out['xml'], 'dynamic' => $out['dynamic'], 'index' => $out['index']);
    }

	function getData($id) {
		$context = $this->buildPartContext($id);
		$dynamic=false;
		$index = '';
		$output = '<content xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/document/1.0/">';
		$sql="select * from document_row where page_id=".$id." order by `index`";
		$result_row = Database::select($sql);
		while ($row = Database::next($result_row)) {
			$output.= '<row>';
			$sql="select * from document_column where row_id=".$row['id']." order by `index`";
			$result_col = Database::select($sql);
			while ($col = Database::next($result_col)) {
				$output.= '<column'.($col['width']!='' ? ' width="'.$col['width'].'"' : '').'>';
				$sql="select document_section.*,part.type as part_type from document_section left join part on part.id = document_section.part_id where document_section.column_id=".$col['id']." order by document_section.`index`";
				$result_sec = Database::select($sql);
				while ($sec = Database::next($result_sec)) {
					$attrs = '';
					if ($sec['left']!='') $attrs.=' left="'.$sec['left'].'"';
					if ($sec['right']!='') $attrs.=' right="'.$sec['right'].'"';
					if ($sec['top']!='') $attrs.=' top="'.$sec['top'].'"';
					if ($sec['bottom']!='') $attrs.=' bottom="'.$sec['bottom'].'"';
					if ($sec['float']!='') $attrs.=' float="'.$sec['float'].'"';
					if ($sec['width']!='') $attrs.=' width="'.$sec['width'].'"';


					$output.= '<section'.$attrs.'>';
					$partArr = $this->partPublish($sec['type'],$sec['id'],$id,$sec['part_id'],$sec['part_type'],$context);
					$output.= $partArr['output'];
					$index.= ' '.$partArr['index'];
					if ($partArr['dynamic']) {
						$dynamic=true;
					}
					$output.= '</section>';
				}
				Database::free($result_sec);
				$output.= '</column>';
			}
			Database::free($result_col);
			$output.= '</row>';
		}
		Database::free($result_row);
		$output.= '</content>';
		return array('xml'=>$output,'index'=>$index,'dynamic'=>$dynamic);
	}

	function buildPartContext($pageId) {
		$context = new PartContext();
		
		//////////////////// Find links ///////////////////
		$sql = "select link.*,page.path from link left join page on page.id=link.target_id and link.target_type='page' where page_id=".Database::int($pageId)." and source_type='text'";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$context -> addBuildLink(
				StringUtils::escapeSimpleXML($row['source_text']),
				$row['target_type'],
				$row['target_id'],
				$row['target_value'],
				$row['target'],
				$row['alternative'],
				$row['path'],
				$row['id'],
				intval($row['part_id'])
			);
		}
		Database::free($result);
	
		/////////////////// Return ///////////////////////
		return $context;
	}

	function partPublish($type,$id,$pageId,$partId,$partType,$context) {
		global $basePath;
		$output='';
		$index='';
		$dynamic=false;
		
		$ctrl = PartService::getController($partType);
		if ($ctrl) {
			$part = PartService::load($partType,$partId);
			$dynamic = $ctrl->isDynamic($part);
			if ($dynamic) {
				$output = "<!-- dynamic:part#".$partId." -->";
			} else {
				$output = $ctrl->build($part,$context);
			}
			$index = $ctrl->getIndex($part);
		}
		return array('output' => $output,'index' => $index,'dynamic' => $dynamic);
	}
	
	function dynamic($id,&$state) {
		$context = new PartContext();
		$sql = "select page_id,part_id,part.type,part.dynamic from document_section,part where page_id=".Database::int($id)." and document_section.part_id=part.id and part.dynamic=1";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$ctrl = PartService::getController($row['type']);
			if ($ctrl) {
				$part = PartService::load($row['type'],$row['part_id']);
				$partData = $ctrl->build($part,$context);
			}
			$state['data']=str_replace('<!-- dynamic:part#'.$row['part_id'].' -->', $partData, $state['data']);
		}
		Database::free($result);
	}

}