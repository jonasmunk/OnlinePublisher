<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Authentication
 */
require_once($basePath.'Editor/Classes/LegacyTemplateController.php');
require_once($basePath.'Editor/Classes/ExternalSession.php');

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
			$data.='<title>'.encodeXML($row['title']).'</title>';
			$index = $row['title'];
		}
		$data.='<!--dynamic-->';
		$data.= '</authentication>';
		return array('data' => $data,'index'=>'','dynamic'=>true);
	}
	
	function dynamic(&$state) {
		$xml = '';

		if (requestGetNumber('page')>0) {
			$xml .= '<target type="page" id="'.requestGetNumber('page').'"/>';
		}
		elseif (requestPostExists('page')) {
			$xml .= '<target type="page" id="'.requestPostNumber('page').'"/>';
		}
		if (requestGetBoolean('logout')) {
			if (requestGetExists('page')) {
				$state['redirect'] = './?id='.requestGetNumber('page');
			} else {
				$xml .= '<message type="loggedout"/>';
			}
		}

		if (requestPostExists('username') && requestPostExists('password')) {
			if (strlen(requestPostText('username'))==0) {
					$xml .= '<message type="nousername"/>';
			}
			elseif (strlen(requestPostText('password'))==0) {
					$xml .= '<message type="nopassword"/>';
			}
			else {
				if ($user = ExternalSession::logIn(requestPostText('username'),requestPostText('password'))) {
					if (requestPostExists('page')) {
						$state['redirect'] = './?id='.requestPostNumber('page');
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