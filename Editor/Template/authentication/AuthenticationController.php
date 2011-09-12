<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Authentication
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/LegacyTemplateController.php');
require_once($basePath.'Editor/Classes/ExternalSession.php');
require_once($basePath.'Editor/Classes/Request.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class AuthenticationController extends LegacyTemplateController {
    
    function AuthenticationController($id) {
        parent::LegacyTemplateController($id);
    }

	function create($page) {
		$sql="insert into authentication (page_id,title) values (".$page->getId().",".Database::text($page->getTitle()).")";
		Database::insert($sql);
	}
	
	function delete() {
		$sql="delete from authentication where page_id=".$this->id;
		Database::delete($sql);
	}

	function build() {
		$data = '<authentication xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/authentication/1.0/">';
		$sql="select * from authentication where page_id=".$this->id;
		if ($row = Database::selectFirst($sql)) {
			$data.='<title>'.StringUtils::escapeXML($row['title']).'</title>';
			$index = $row['title'];
		}
		$data.='<!--dynamic-->';
		$data.= '</authentication>';
		return array('data' => $data,'index'=>'','dynamic'=>true);
	}
	
	function dynamic(&$state) {
		$xml = '';

		if (Request::exists('page')) {
			$xml .= '<target type="page" id="'.Request::getInt('page').'"/>';
		}
		if (Request::getBoolean('logout')) {
			if (Request::exists('page')) {
				$state['redirect'] = './?id='.Request::getInt('page');
			} else {
				$xml .= '<message type="loggedout"/>';
			}
		}

		if (Request::exists('username') && Request::exists('password')) {
			if (strlen(Request::getString('username'))==0) {
					$xml .= '<message type="nousername"/>';
			}
			elseif (strlen(Request::getString('password'))==0) {
					$xml .= '<message type="nopassword"/>';
			}
			else {
				if ($user = ExternalSession::logIn(Request::getString('username'),Request::getString('password'))) {
					if (Request::exists('page')) {
						$state['redirect'] = './?id='.Request::getInt('page');
					}
					else {
						$xml .= '<message type="loggedin"/>';
					}
				}
				else {
					$xml .= '<message type="usernotfound"/>';
				}
			}
		}
		$state['data'] = str_replace('<!--dynamic-->', $xml, $state['data']);
	}
	
    function import(&$node) {
		$titles =& $node->getElementsByTagName('title');
		$title = '';
		if ($titles->getLength()>0) {
			$title = $titles->item(0)->getText();
		}
		
		$sql="update authentication set title=".Database::text($title)." where page_id=".$this->id;
		Database::update($sql);
	}
}
?>