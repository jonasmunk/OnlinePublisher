<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Newseditor
 */
require_once($basePath.'Editor/Classes/TemplateController.php');
require_once $basePath.'Editor/Classes/Part.php';
require_once $basePath.'Editor/Classes/News.php';
require_once($basePath.'Editor/Classes/Request.php');
require_once($basePath.'Editor/Classes/ExternalSession.php');

class NewseditorController extends TemplateController {
    
    function NewseditorController($id) {
        parent::TemplateController($id);
    }

	function create($page) {
		$sql="insert into newseditor (page_id) values (".$page->getId().")";
		Database::insert($sql);
	}
	
	function delete() {
		$sql="delete from newseditor where page_id=".$this->id;
		Database::delete($sql);
	}

	function dynamic(&$state) {
		$response = array('message' => null);
		if (Request::isPost()) {
			$response = $this->createNews();
		}
		if (isset($response['redirect'])) {
			$state['redirect']=$response['redirect'];
			return;
		}
		$xml = '';
		if (Request::getString('action')=='new') {
			$xml.=$this->newNews($response);
		} else {
			$xml.=$this->listNews($response);
		}
		$state['data'] = str_replace("<!--dynamic-->", $xml, $state['data']);
	}
	
	function generateProperties($props,$response) {
		$xml = '';
		foreach ($props as $prop) {
			if (isset($response[$prop])) {
				$xml .= '<property name="'.$prop.'">';
				if (is_array($response[$prop])) {
					foreach ($response[$prop] as $value) {
						$xml.='<value>'.encodeXML($value).'</value>';
					}
				} else {
					$xml.=encodeXML($response[$prop]);
				}
				$xml.='</property>';
			}
		}
		return $xml;
	}
	
	function parseDate($date) {
		if ($date=='') {
			return null;
		} elseif (preg_match ("/([0-9]+)[\/-]([0-9]+)[\/-]([0-9]{4})/i",$date,$result)) {
			$day = $result[1];
			$month = $result[2];
			$year = $result[3];
			//				h	m	s
			return mktime ( 0 , 0 , 0 , $month , $day , $year );
		} else {
			return false;
		}
	}

	function listNews(&$response) {
		$xml = '<list>';
		$props = array('title','note','startdate','enddate','groups');
		$xml .= $this->generateProperties($props,$response);
		
		$sql="select data from object where type='news' and owner_id=".intval(ExternalSession::getUser()).' order by title';
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$xml.=$row['data'];
		}
		Database::free($result);
		
		$xml .= '</list>';
		return $xml;
	}

	function newNews(&$response) {
		$xml = '<new>';
		if ($response['message']!=null) {
			$xml.='<message type="'.$response['message'].'"/>';
		}
		$props = array('title','note','startdate','enddate','groups');
		$xml .= $this->generateProperties($props,$response);
		
		$sql="select data from object where type='newsgroup'";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$xml.=$row['data'];
		}
		Database::free($result);
		
		$xml .= '</new>';
		return $xml;
	}

	function createNews() {
		$groups = Request::getPostArray('group');
		$title = Request::getUnicodeString('title');
		$note = Request::getUnicodeString('note');
		$startdate = Request::getUnicodeString('startdate');
		$enddate = Request::getUnicodeString('enddate');
		
		$parsedStartdate = $this->parseDate($startdate);
		$parsedEnddate = $this->parseDate($enddate);
		
		$response = array('message' => null,'groups' => $groups, 'title' => $title, 'note' => $note, 'startdate' => $startdate, 'enddate' => $enddate);
		if ($parsedStartdate===false){
			$response['message'] = 'bad-startdate';
		} elseif ($parsedEnddate===false){
			$response['message'] = 'bad-enddate';
		} elseif ($parsedEnddate!=null && $parsedStartdate!=null && $parsedEnddate<$parsedStartdate) {
			$response['message'] = 'enddate-smaller-than-startdate';
		} else if ($title=='') {
			$response['message'] = 'no-title';
		} elseif (!true) {
			$response['message'] = 'x';
		} else {
			$news = new News();
			$news->setTitle($title);
			$news->setNote($note);
			$news->setStartdate($parsedStartdate);
			$news->setEnddate($parsedEnddate);
			$news->setOwnerId(ExternalSession::getUser());
			$news->create();
			$news->updateGroupIds($groups);
			$news->publish();
			$response = array('message' => null,'redirect' => '?id='.$this->id);
		}
		return $response;
	}

    
    function build() {
		$sql="select * from newseditor where page_id=".$this->id;
		$row = Database::selectFirst($sql);
		$data = '<newseditor xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/newseditor/1.0/">';
		$data.= '<!--dynamic--></newseditor>';
        return array('data' => $data, 'dynamic' => true, 'index' => '');
    }

    function import(&$node) {
    }
    
}
?>