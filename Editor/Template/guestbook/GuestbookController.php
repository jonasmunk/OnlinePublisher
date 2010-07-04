<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.GuestBook
 */
require_once($basePath.'Editor/Classes/TemplateController.php');

class GuestbookController extends TemplateController {
    
    function GuestbookController($id) {
        parent::TemplateController($id);
    }

	function create($page) {
		$sql="insert into guestbook (page_id,title) values (".$page->getId().",".Database::text($page->getTitle()).")";
		Database::insert($sql);
	}
	
	function delete() {
		$sql="delete from guestbook where page_id=".$this->id;
		Database::delete($sql);
		$sql="delete from guestbook_item where page_id=".$this->id;
		Database::delete($sql);
	}

	function build() {
		$data = '<guestbook xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/guestbook/1.0/">';
		$data.= '<lang xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/internationalization/">';
		$data.= '<text key="list-header-time">Tid</text>';
		$data.= '<text key="list-header-name">Navn</text>';
		$data.= '<text key="list-header-text">Besked</text>';
		$data.= '<text key="action-new">Ny besked</text>';
		$data.= '<text key="action-cancel">Annuller</text>';
		$data.= '<text key="action-create">Opret</text>';
		$data.= '<text key="newitem-label-name">Navn</text>';
		$data.= '<text key="newitem-label-text">Besked</text>';
		$data.= '</lang>';
		$sql="select title,text from guestbook where page_id=".$this->id;
		$row = Database::selectFirst($sql);
		$data.='<title>'.encodeXML($row['title']).'</title>';
		$data.='<text>'.escapeXMLwithLineBreak($row['text'],'<break/>').'</text>';
		$data.='<!--dynamic-->';
		$data.= '</guestbook>';
		$index = $row['title'].' '.$row['text'];
        return array('data' => $data, 'dynamic' => true, 'index' => $index);
	}

	function dynamic(&$state) {		
		if (requestGetBoolean('newitem')) {
			$xml="<newitem/>";
		}
		else {
			if (requestPost() && requestPostBoolean('userinteraction')) {
				$name = Request::getUnicodeString('name');
				$text = Request::getUnicodeString('text');
				$sql = "insert into guestbook_item (page_id,time,name,text) values (".$this->id.",now(),".Database::text($name).",".Database::text($text).")";
				Database::insert($sql);
			}
			$sql="select *,UNIX_TIMESTAMP(time) as unix from guestbook_item where page_id=".$this->id." order by time desc";
			$result = Database::select($sql);
			$num = mysql_num_rows($result);
			$xml='<list>';
			while ($row = Database::next($result)) {
				$xml.='<item id="'.$row['id'].'">';
				$xml.=buildDateTag('time',$row['unix']);
				$xml.='<name>'.encodeXML($row['name']).'</name>';
				$xml.='<text>'.escapeXMLwithLineBreak($row['text'],'<break/>').'</text>';
				$xml.='</item>';
			}
			$xml.='</list>';
			Database::free($result);
		}
		$state['data']=str_replace("<!--dynamic-->", $xml, $state['data']);
	}
}
?>