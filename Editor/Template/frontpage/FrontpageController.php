<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Frontpage
 */
require_once($basePath.'Editor/Classes/TemplateController.php');
require_once $basePath.'Editor/Classes/Part.php';
require_once $basePath.'Editor/Classes/PartContext.php';

class FrontpageController extends TemplateController {
    
    function FrontpageController($id) {
        parent::TemplateController($id);
    }

	function create($page) {

		$sql="insert into frontpage (page_id) values (".$page->getId().")";
		Database::insert($sql);

		$sql="insert into frontpage_row (page_id,position) values (".$page->getId().",1)";
		$row1 = Database::insert($sql);

		$sql="insert into frontpage_cell (page_id,position,row_id) values (".$page->getId().",1,".$row1.")";
		Database::insert($sql);

		$sql="insert into frontpage_cell (page_id,position,row_id) values (".$page->getId().",2,".$row1.")";
		Database::insert($sql);

		$sql="insert into frontpage_row (page_id,position) values (".$page->getId().",2)";
		$row2 = Database::insert($sql);

		$sql="insert into frontpage_cell (page_id,position,row_id) values (".$page->getId().",1,".$row2.")";
		Database::insert($sql);

		$sql="insert into frontpage_cell (page_id,position,row_id) values (".$page->getId().",2,".$row2.")";
		Database::insert($sql);
	}
	
	function delete() {
		// Find and delete alle sections in the cells of the page
		$sql="select frontpage_section.*,part.type from frontpage_section,part,frontpage_cell where frontpage_section.cell_id=frontpage_cell.id and frontpage_section.part_id=part.id and frontpage_cell.page_id=".$this->id;
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$sql="delete from frontpage_section where id =".$row['id'];
			Database::delete($sql);
			$part = Part::load($row['type'],$row['part_id']);
			$part->delete();
		}
		Database::free($result);

		// Delete all cells
		$sql="delete from frontpage_cell where page_id=".$this->id;
		Database::delete($sql);

		// Delete all rows
		$sql="delete from frontpage_row where page_id=".$this->id;
		Database::delete($sql);

		// Delete the content
		$sql="delete from frontpage where page_id=".$this->id;
		Database::delete($sql);
	}

	function build() {
		$partContext = new PartContext();

		$data = '<content xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/frontpage/1.0/">';
		$index = '';
		$dynamic = false;
		$sql="select * from frontpage_row where page_id=".$this->id." order by position";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$cells = $this->publishCells($row['id'],$partContext);
			$data.='<row>';
			$data.=$cells['data'];
			$data.='</row>';
			$index.=' '.$cells['index'];
			$dynamic = $dynamic || $cells['dynamic'];
		}
		Database::free($result);
		$data.= '</content>';
        return array('data' => $data, 'dynamic' => $dynamic, 'index' => $index);
	}

	function publishCells($rowId,$partContext) {
		$index='';
		$data='';
		$dynamic=false;
		$sql="select * from frontpage_cell where row_id=".$rowId." order by position";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$sections = $this->publishSections($row['id'],$partContext);
			$data.='<cell columns="'.$row['columns'].'" rows="'.$row['rows'].'"'.
			($row['title']!='' ? ' title="'.encodeXML($row['title']).'"' : '').
			($row['type']!='' ? ' type="'.encodeXML($row['type']).'"' : '').
			($row['width']!='' ? ' width="'.encodeXML($row['width']).'"' : '').
			($row['height']!='' ? ' height="'.encodeXML($row['height']).'"' : '').
			'>'.
			$sections['data'].
			'</cell>';
			$index.=' '.$sections['index'];
			$dynamic = $dynamic || $sections['dynamic'];
		}
		Database::free($result);
		return array('data' => $data, 'index' => $index, 'dynamic' => $dynamic);
	}

	function publishSections($cellId,$partContext) {
		$index='';
		$data='';
		$dynamic = false;
		$sql="select frontpage_section.*,part.type from frontpage_section,part where frontpage_section.part_id=part.id and frontpage_section.cell_id=".$cellId." order by position";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$data.='<section>';
			$part = Part::load($row['type'],$row['part_id']);
			if ($part->isDynamic()) {
				$dynamic = true;
				$data .= "<!-- dynamic:part#".$row['part_id']." -->";
			} else {
				$data .= $part->build($partContext);
			}
			$data.='</section>';
			$index.=' '.$part->index();
		}
		Database::free($result);
		return array('data' =>$data, 'index' => $index, 'dynamic' => $dynamic);
	}
	
	function dynamic(&$state) {
		$context = new PartContext();
		$sql = "select page_id,part_id,part.type from frontpage_section,part where page_id=".$this->id." and frontpage_section.part_id=part.id and part.dynamic=1";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$part = Part::load($row['type'],$row['part_id']);
			$partData = $part->build($context);
			$state['data']=str_replace('<!-- dynamic:part#'.$row['part_id'].' -->', $partData, $state['data']);
		}
		Database::free($result);
	}
}
?>