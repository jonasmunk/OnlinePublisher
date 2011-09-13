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
require_once($basePath.'Editor/Classes/Utilities/DOMUtils.php');
require_once($basePath.'Editor/Classes/ExternalSession.php');
require_once($basePath.'Editor/Classes/Request.php');

class AuthenticationTemplateController extends TemplateController
{
	function AuthenticationTemplateController() {
		parent::TemplateController('authentication');
	}
	
	function create($page) {
		$sql="insert into authentication (page_id,title) values (".Database::int($page->getId()).",".Database::text($page->getTitle()).")";
		Database::insert($sql);
	}
	
	function delete($page) {
		$sql="delete from authentication where page_id=".Database::int($page->getId());
		Database::delete($sql);
	}

	function isClientSide() {
		return true;
	}
	
	function build($id) {
		$data = '<authentication xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/authentication/1.0/">';
		$sql="select * from authentication where page_id=".Database::int($id);
		if ($row = Database::selectFirst($sql)) {
			$data.='<title>'.StringUtils::escapeXML($row['title']).'</title>';
			$index = $row['title'];
		}
		$data.='<!--dynamic-->';
		$data.= '</authentication>';
		return array('data' => $data,'index'=>'','dynamic'=>true);
	}
	
	function dynamic($id,&$state) {
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

}