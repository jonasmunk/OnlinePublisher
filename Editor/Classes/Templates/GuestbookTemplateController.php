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

class GuestbookTemplateController extends TemplateController
{
	function GuestbookTemplateController() {
		parent::TemplateController('guestbook');
	}

	function create($page) {
		$sql="insert into guestbook (page_id,title) values (".Database::int($page->getId()).",".Database::text($page->getTitle()).")";
		Database::insert($sql);
	}

	function delete($page) {
		$sql="delete from guestbook where page_id=".Database::int($page->getId());
		Database::delete($sql);
		$sql="delete from guestbook_item where page_id=".Database::int($page->getId());
		Database::delete($sql);
	}

    function build($id) {
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
		$sql="select title,text from guestbook where page_id=".Database::int($id);
		$row = Database::selectFirst($sql);
		$data.='<title>'.StringUtils::escapeXML($row['title']).'</title>';
		$data.='<text>'.StringUtils::escapeSimpleXMLwithLineBreak($row['text'],'<break/>').'</text>';
		$data.='<!--dynamic-->';
		$data.= '</guestbook>';
		$index = $row['title'].' '.$row['text'];
        return array('data' => $data, 'dynamic' => true, 'index' => $index);
    }

	function dynamic($id,&$state) {
		if (Request::getBoolean('newitem')) {
			$xml="<newitem/>";
		}
		else {
			if (Request::isPost() && Request::getBoolean('userinteraction')) {
				$name = Request::getString('name');
				$text = Request::getString('text');
				$sql = "insert into guestbook_item (page_id,time,name,text) values (".Database::int($id).",now(),".Database::text($name).",".Database::text($text).")";
				Database::insert($sql);
			}
			$sql="select *,UNIX_TIMESTAMP(time) as unix from guestbook_item where page_id=".Database::int($id)." order by time desc";
			$result = Database::select($sql);
			$num = mysql_num_rows($result);
			$xml='<list>';
			while ($row = Database::next($result)) {
				$xml.='<item id="'.$row['id'].'">';
				$xml.=RenderingService::buildDateTag('time',$row['unix']);
				$xml.='<name>'.StringUtils::escapeXML($row['name']).'</name>';
				$xml.='<text>'.StringUtils::escapeSimpleXMLwithLineBreak($row['text'],'<break/>').'</text>';
				$xml.='</item>';
			}
			$xml.='</list>';
			Database::free($result);
		}
		$state['data']=str_replace("<!--dynamic-->", $xml, $state['data']);
	}

}