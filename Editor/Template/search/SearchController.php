<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Search
 */
require_once($basePath.'Editor/Classes/LegacyTemplateController.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class SearchController extends LegacyTemplateController {
    
    function SearchController($id) {
        parent::LegacyTemplateController($id);
    }

	function create($page) {
		$sql="insert into search (page_id,title) values (".$page->getId().",".Database::text($page->getTitle()).")";
		Database::insert($sql);
	}
	
	function delete() {
		$sql="delete from search where page_id=".$this->id;
		Database::delete($sql);
	}
    
    function build() {
		$data='<search xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/search/1.0/">';

		$sql="select * from search where page_id=".$this->id;
		$row = Database::selectFirst($sql);
		$data.='<title>'.StringUtils::escapeXML($row['title']).'</title>';
		$data.='<text>'.StringUtils::escapeSimpleXMLwithLineBreak($row['text'],'<break/>').'</text>';
		$data.='<buttontitle>'.StringUtils::escapeXML($row['buttontitle']).'</buttontitle>';
		$data.='<types>';
		if ($row['pagesenabled']) {
			$data.='<type label="'.StringUtils::escapeXML($row['pageslabel']).'" default="'.($row['pagesdefault'] ? 'true' : 'false').'" hidden="'.($row['pageshidden'] ? 'true' : 'false').'" unique="page"/>';
		}
		if ($row['imagesenabled']) {
			$data.='<type label="'.StringUtils::escapeXML($row['imageslabel']).'" default="'.($row['imagesdefault'] ? 'true' : 'false').'" hidden="'.($row['imageshidden'] ? 'true' : 'false').'" unique="image"/>';
		}
		if ($row['filesenabled']) {
			$data.='<type label="'.StringUtils::escapeXML($row['fileslabel']).'" default="'.($row['filesdefault'] ? 'true' : 'false').'" hidden="'.($row['fileshidden'] ? 'true' : 'false').'" unique="file"/>';
		}
		if ($row['personsenabled']) {
			$data.='<type label="'.StringUtils::escapeXML($row['personslabel']).'" default="'.($row['personsdefault'] ? 'true' : 'false').'" hidden="'.($row['personshidden'] ? 'true' : 'false').'" unique="person"/>';
		}
		if ($row['newsenabled']) {
			$data.='<type label="'.StringUtils::escapeXML($row['newslabel']).'" default="'.($row['newsdefault'] ? 'true' : 'false').'" hidden="'.($row['newshidden'] ? 'true' : 'false').'" unique="news"/>';
		}
		if ($row['productsenabled']) {
			$data.='<type label="'.StringUtils::escapeXML($row['productslabel']).'" default="'.($row['productsdefault'] ? 'true' : 'false').'" hidden="'.($row['productshidden'] ? 'true' : 'false').'" unique="product"/>';
		}
		$data.='</types>';
		$data.='<!--dynamic-->';
		$data.='</search>';
        return array('data' => $data, 'dynamic' => true, 'index' => '');
    }

	function dynamic(&$state) {
		if (Request::exists('query')) {
			$searchPages = Request::getCheckbox('page');
			$searchImages = Request::getCheckbox('image');
			$searchFiles = Request::getCheckbox('file');
			$searchPersons = Request::getCheckbox('person');
			$searchNews = Request::getCheckbox('news');
			$searchProducts = Request::getCheckbox('product');
			$method = Request::getString('method');
			if ($method=='') {
				$method='all';
			}
			$query = Request::getUnicodeString('query');
			$xml = '<parameters method="'.$method.'">';
			$xml .= '<query>'.StringUtils::escapeXML($query).'</query>';
			$xml .= '<types>';
			$xml .= '<type unique="page" selected="'.($searchPages ? 'true' : 'false').'"/>';
			$xml .= '<type unique="image" selected="'.($searchImages ? 'true' : 'false').'"/>';
			$xml .= '<type unique="file" selected="'.($searchFiles ? 'true' : 'false').'"/>';
			$xml .= '<type unique="person" selected="'.($searchPersons ? 'true' : 'false').'"/>';
			$xml .= '<type unique="news" selected="'.($searchNews ? 'true' : 'false').'"/>';
			$xml .= '<type unique="product" selected="'.($searchProducts ? 'true' : 'false').'"/>';
			$xml .= '</types>';
			$xml .= '</parameters>';
			if (strlen($query)>0) {
				$xml.='<results>';
				if ($searchPages) {
					$sql="select id,title,description,`index` from page where searchable=1 and secure=0 and disabled=0";
					if ($method=='sentence') {
						$highlight = array($query);
						$sql.=" and (title like ".Database::search($query);
						$sql.=" or keywords like ".Database::search($query);
						$sql.=" or description like ".Database::search($query);
						$sql.=" or `index` like ".Database::search($query).")";
					}
					else {
						$words = explode(' ',$query);
						$highlight = $words;
						$first=true;
						$count = count($words);
						if ($count>0) {
							$sql.=' and (';
							for ($i=0;$i<$count;$i++) {
								if (strlen($words[$i])>0) {
									if (!$first) {
										if ($method=='some') {
											$sql.=' or ';
										}
										else if ($method=='all') {
											$sql.=' and ';
										}
									}
									$first=false;
									$sql.="(title like ".Database::search($words[$i]);
									$sql.=" or keywords like ".Database::search($words[$i]);
									$sql.=" or description like ".Database::search($words[$i]);
									$sql.=" or `index` like ".Database::search($words[$i]).")";
								}
							}
							$sql.=')';
						}
					}
					$sql.=' order by page.title';
					$result = Database::select($sql);
					$num = mysql_num_rows($result);
					$xml.='<group type="page" count="'.$num.'">';
					while ($row = Database::next($result)) {
						$xml.='<page id="'.$row['id'].'">';
						$xml.='<title>'.StringUtils::escapeXML($row['title']).'</title>';
						$xml.='<description>'.StringUtils::escapeSimpleXMLwithLineBreak($row['description'],'<break/>').'</description>';
						$xml.='<summary>'.StringUtils::summarizeAndHighlight($highlight,$row['index']).'</summary>';
						$xml.='</page>';
					}
					Database::free($result);
					$xml.='</group>';
				}
				if ($searchPersons) {
					$xml.=$this->searchObjects('person',$query,$method);
				}
				if ($searchImages) {
					$xml.=$this->searchObjects('image',$query,$method);
				}
				if ($searchFiles) {
					$xml.=$this->searchObjects('file',$query,$method);
				}
				if ($searchNews) {
					$xml.=$this->searchObjects('news',$query,$method);
				}
				if ($searchProducts) {
					$xml.=$this->searchObjects('product',$query,$method);
				}
				$xml.='</results>';
			}
			$state['data']=str_replace("<!--dynamic-->", $xml, $state['data']);
		}
	}
	

	function searchObjects($type,$query,$method) {
		$words = explode(' ',$query);
		$sql="select object.data,object.index from object where searchable=1 and type=".Database::text($type).$this->buildObjectSearchSql($query,$method)." order by object.title";
		$result = Database::select($sql);
		$num = mysql_num_rows($result);
		$xml='<group type="'.$type.'" count="'.$num.'">';
		while ($row = Database::next($result)) {
			$xml.=
			'<result>'.
			$row['data'].
			'<summary>'.StringUtils::summarizeAndHighlight($words,$row['index']).'</summary>'.
			'</result>';
		}
		Database::free($result);
		$xml.='</group>';
		return $xml;
	}

	function buildObjectSearchSql($query,$method) {
		$sql='';
		if ($method=='sentence') {
			//$highlight = array($query);
			$sql.=" and `index` like ".Database::search($query);
		}
		else {
			$words = explode(' ',$query);
			//$highlight = $words;
			$first=true;
			if (count($words>0)) {
				$sql.=" and (";
				for ($i=0;$i<count($words);$i++) {
					if (strlen($words[$i])>0) {
						if (!$first) {
							if ($method=='some') {
								$sql.=' or ';
							}
							else if ($method=='all') {
								$sql.=' and ';
							}
						}
						$first=false;
						$sql.="`index` like ".Database::search($words[$i]);
					}
				}
				$sql.=")";
			}
		}
		return $sql;
	}
}
?>