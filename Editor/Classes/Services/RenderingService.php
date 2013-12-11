<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class RenderingService {
	
	static function sendNotFound() {
		$uri = $_SERVER['REQUEST_URI'];
		if ($uri!='/favicon.ico' && $uri!='/robots.txt' && $uri!='/apple-touch-icon.png' && $uri!='/apple-touch-icon-precomposed.png') {
			Log::logPublic('pagenotfound','uri='.$_SERVER['REQUEST_URI']);
		}
		$error = '<title>Page not found</title>'.
		'<note>The requested page was not found on this website</note>';
		RenderingService::displayError($error);	
	}
	
	static function displayError($message,$path="") {
		$xml = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml.= '<message xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/error/1.0/">';
		$xml.= $message;
		$xml.= '</message>';
		header("Content-Type: text/html; charset=UTF-8");
		header("HTTP/1.0 404 Not Found");
		echo RenderingService::applyStylesheet($xml,"basic","error",$path,$path,$path,'',false,'en');
	}
	
	static function buildPageContext($id,$nextPage,$previousPage) {
		$output='<context>';
		// Front pages
		$sql="select specialpage.*,page.path from specialpage,page where page.disabled=0 and specialpage.page_id = page.id";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			if ($row['type']=='home') {
				$output.='<home page="'.$row['page_id'].'"'.($row['path']!='' ? ' path="'.Strings::escapeEncodedXML($row['path']).'"' : '').($row['language']!='' ? ' language="'.Strings::escapeEncodedXML(strtolower($row['language'])).'"' : '').'/>';
			}
		}
		Database::free($result);
		// Translations
		$sql="select page.id,page.language,page.path from page_translation,page".
		" where page.id=page_translation.translation_id and page.disabled=0".
		" and page_translation.page_id=".$id." order by language";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$output.='<translation page="'.$row['id'].'"'.($row['path']!='' ? ' path="'.Strings::escapeEncodedXML($row['path']).'"' : '').($row['language']!='' ? ' language="'.Strings::escapeEncodedXML(strtolower($row['language'])).'"' : '').'/>';
		}
		Database::free($result);
		if ($nextPage>0) {
			$output.='<next id="'.$nextPage.'"/>';
		}
		if ($previousPage>0) {
			$output.='<previous id="'.$previousPage.'"/>';
		}
		$output.='</context>';
		return $output;
	}
	
	static function applyStylesheet(&$xmlData,$design,$template,$path,$urlPath,$navigationPath,$pagePath,$preview,$language) {
		global $basePath;

		$agent='xslt';
		if (function_exists('xslt_create')) {
			$incPath = '../../';
		}
		else {
			$incPath = $path;
		}
		if (file_exists($basePath.'style/'.$design.'/xslt/'.$template.'.xsl')) {
			$contentDesign = $design;
			$agent='xslt';
		} else if (file_exists($basePath.'style/basic/xslt/'.$template.'.xsl')) {
			$contentDesign = 'basic';
			$agent = 'xslt';
		} else {
			$contentDesign='basic';
		}
		if (Request::getBoolean('print')) {
			$mainFile='main_print';
			$mainDesign='basic';
		} else if (Request::getBoolean('mini')) {
			$mainFile='main_mini';
			$mainDesign='basic';
		} else if (Request::getBoolean('content')) {
			$mainFile='main_content';
			$mainDesign='basic';
		} else {
			$mainFile='main';
			$mainDesign=$design;
		}
		$userId=0;
		$userName='';
		$userTitle='';
		if ($user = ExternalSession::getUser()) {
			$userId=$user['id'];
			$userName=$user['username'];
			$userTitle=$user['title'];
		}
		$mainPath = $incPath.'style/'.$mainDesign.'/xslt/'.$mainFile.'.xsl';
		$templatePath = $incPath.'style/'.$contentDesign.'/xslt/'.$template.'.xsl';
        $encoding = ConfigurationService::isUnicode() ? 'UTF-8' : 'ISO-8859-1';
		$xslData='<?xml version="1.0" encoding="'.$encoding.'"?>'.
		'<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">'.
		'<xsl:output method="html" indent="no" encoding="'.$encoding.'"/>'.
		'<xsl:include href="'.$templatePath.'"/>'.
		'<xsl:include href="'.$mainPath.'"/>'.
		'<xsl:variable name="design">'.$design.'</xsl:variable>'.
		'<xsl:variable name="path">'.$urlPath.'</xsl:variable>'.
		'<xsl:variable name="navigation-path">'.$navigationPath.'</xsl:variable>'.
		'<xsl:variable name="page-path">'.$pagePath.'</xsl:variable>'.
		'<xsl:variable name="template">'.$template.'</xsl:variable>'.
		'<xsl:variable name="agent">'.Strings::escapeEncodedXML(RenderingService::_getAgent()).'</xsl:variable>'.
		'<xsl:variable name="userid">'.Strings::escapeEncodedXML($userId).'</xsl:variable>'.
		'<xsl:variable name="username">'.Strings::escapeEncodedXML($userName).'</xsl:variable>'.
		'<xsl:variable name="usertitle">'.Strings::escapeEncodedXML($userTitle).'</xsl:variable>'.
		'<xsl:variable name="internal-logged-in">'.(InternalSession::isLoggedIn() ? 'true' : 'false').'</xsl:variable>'.
		'<xsl:variable name="preview">'.($preview ? 'true' : 'false').'</xsl:variable>'.
		'<xsl:variable name="editor">false</xsl:variable>'.
		'<xsl:variable name="mini">'.(Request::getBoolean('mini') ? 'true' : 'false').'</xsl:variable>'.
		'<xsl:variable name="development">'.(Request::getBoolean('dev') ? 'true' : 'false').'</xsl:variable>'.
		'<xsl:variable name="highquality">'.(Request::getBoolean('print') ? 'true' : 'false').'</xsl:variable>'.
		'<xsl:variable name="urlrewrite">'.(ConfigurationService::isUrlRewrite() ? 'true' : 'false').'</xsl:variable>'.
		'<xsl:variable name="timestamp">'.SystemInfo::getDate().'</xsl:variable>'.
		'<xsl:variable name="language">'.$language.'</xsl:variable>'.
		'<xsl:template match="/"><xsl:apply-templates/></xsl:template>'.
		'</xsl:stylesheet>';
	
		return XslService::transform($xmlData,$xslData);
	}
	
	static function applyFrameDynamism($id,&$data) {
		$sql = "select id,maxitems,sortdir,sortby,timetype,timecount,UNIX_TIMESTAMP(startdate) as startdate,UNIX_TIMESTAMP(enddate) as enddate from frame_newsblock where frame_id=".$id;
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$blockId = $row['id'];
			$maxitems = $row['maxitems'];
			$sortBy = 'news.'.$row['sortby'];
			// Find sort direction
			if ($row['sortdir']=='descending') {
				$sortDir = 'DESC';
			}
			else {
				$sortDir = 'ASC';
			}
			$timetype = $row['timetype'];
			if ($timetype=='always') {
				$timeSql=''; // no time managing for always
			}
			else if ($timetype=='now') {
				// Create sql for active news
				$timeSql=" and ((news.startdate is null and news.enddate is null) or (news.startdate<=now() and news.enddate>=now()) or (news.startdate<=now() and news.enddate is null) or (news.startdate is null and news.enddate>=now()))";
			}
			else {
				$count=$row['timecount'];
				if ($timetype=='interval') {
					$start = intval($row['startdate']);
					$end = intval($row['enddate']);
				}
				else if ($timetype=='hours') {
					$start = mktime(date("H")-$count,date("i"),date("s"),date("m"),date("d"),date("Y"));
					$end = time();
				}
				else if ($timetype=='days') {
					$start = mktime(date("H"),date("i"),date("s"),date("m"),date("d")-$count,date("Y"));
					$end = time();
				}
				else if ($timetype=='weeks') {
					$start = mktime(date("H"),date("i"),date("s"),date("m"),date("d")-($count*7),date("Y"));
					$end = time();
				}
				else if ($timetype=='months') {
					$start = mktime(date("H"),date("i"),date("s"),date("m")-$count,date("d"),date("Y"));
					$end = time();
				}
				else if ($timetype=='years') {
					$start = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")-$count);
					$end = time();
				}
				$timeSql=" and ((news.startdate is null and news.enddate is null) or (news.startdate>=".Database::datetime($start)." and news.startdate<=".Database::datetime($end).") or (news.enddate>=".Database::datetime($start)." and news.enddate<=".Database::datetime($end).") or (news.enddate>=".Database::datetime($start)." and news.startdate is null) or (news.startdate<=".Database::datetime($end)." and news.enddate is null))";
			}
			$newsData = '';
			$sql = "select distinct object.data from object, news, newsgroup_news, frame_newsblock_newsgroup, frame_newsblock where object.id = news.object_id and news.object_id=newsgroup_news.news_id and newsgroup_news.newsgroup_id=frame_newsblock_newsgroup.newsgroup_id and frame_newsblock_newsgroup.frame_newsblock_id=".$blockId.$timeSql." order by ".$sortBy." ".$sortDir;
			$resultNews = Database::select($sql);
			while ($rowNews = Database::next($resultNews)) {
				$newsData.=$rowNews['data'];
				$maxitems--;
				if ($maxitems==0) break;
			}
			Database::free($resultNews);
			$data = str_replace("<!--newsblock#".$blockId."-->", $newsData, $data);
		}
		Database::free($result);
		return $data;
	}
	
	static function applyContentDynamism($id,$template,&$data) {
		$state = array('data' => $data,'redirect' => false,'override' => false);
		if ($controller = TemplateService::getController($template)) {
			if (method_exists($controller,'dynamic')) {
				$controller->dynamic($id,$state);
				return $state;
			}
		}
		return $state;
	}
	
	static function handleMissingPage($path) {
		// See if there is a page redirect
		$sql = "select page.id,page.path from path left join page on page.id=path.page_id where path.path=".Database::text($path);
		if ($row = Database::selectFirst($sql)) {
			if ($row['path']!='') {
				Response::redirectMoved(Strings::concatUrl(ConfigurationService::getBaseUrl(),$row['path']));
			} else if ($row['id']>0) {
				Response::redirectMoved(ConfigurationService::getBaseUrl().'?id='.$row['id']);
			} else {
				RenderingService::sendNotFound();
			}
		} else {
			RenderingService::sendNotFound();
		}
	}
	
	static function buildPage($id,$path=null,$parameters=array()) {
		//Log::debug('buildPage: id:('.$id.') path:('.$path.')');
		$sql="select page.id,page.path,page.secure,UNIX_TIMESTAMP(page.published) as published,".
		" page.title,page.description,page.language,page.keywords,page.data,page.dynamic,page.next_page,page.previous_page,".
		" template.unique as template,frame.id as frameid,frame.title as frametitle,".
		" frame.data as framedata,frame.dynamic as framedynamic,design.`unique` as design,".
		" design.parameters,".
		" hierarchy.data as hierarchy, ".
		" setting.value as analytics_key".
		" from page,template,frame,design,hierarchy".
		" left join setting on setting.subdomain='googleanalytics' and setting.`key`='webprofile'".
		" where page.frame_id=frame.id and page.template_id=template.id".
		" and page.disabled=0".
		" and page.design_id=design.object_id and frame.hierarchy_id=hierarchy.id";
		if ($id > 0) {
			$sql.=" and page.id=".Database::int($id);
		} else {
			//Log::debug('Paths: ('.$path.') ('.$path.'/) (/'.$path.')');
			$sql.=" and (page.path=".Database::text($path);
			if (Strings::isNotBlank($path)) {
				$sql.=" or page.path=".Database::text($path.'/')." or page.path=".Database::text('/'.$path);
			}
			$sql.=") order by page.path desc";
		}
		if ($row = Database::selectFirst($sql)) {
			if (Request::getBoolean('ajax')) {
				if ($controller = TemplateService::getController($row['template'])) {
					if (method_exists($controller,'ajax')) {
						$controller->ajax($id);
						exit;
					}
				}
			}

			$pageNS = 'http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/';
			$frameNS = 'http://uri.in2isoft.com/onlinepublisher/publishing/frame/1.0/';

			$data = $row['data'];
			$template = $row['template'];
			$redirect = false;
		
			if (Strings::isNotBlank($row['path']) && Strings::isBlank($path) && $id>0) {
				//Log::debug('Redirect: requested:('.$path.') page:('.$row['path'].') id:('.$id.')');
				if ($row['path']) {
					$redirect = Strings::concatUrl(ConfigurationService::getBaseUrl(),$row['path']);
					$query = array();
					foreach ($parameters as $parameter) {
						if ($parameter['name']!='id' && Strings::isNotBlank($parameter['name'])) {
							$query[] = $parameter['name'].'='.$parameter['value'];
						}
					}
					if (count($query)>0) {
						$redirect.='?'.join($query,'&');
					}
					//Log::debug('Redirect::: '.$redirect);
					//Log::debug($row);
				}
			}
			else if ($row['dynamic']) {
				$content = RenderingService::applyContentDynamism($row['id'],$template,$data);
				$data = $content['data'];
				$redirect = $content['redirect'];
			}
			$framedata = $row['framedata'];
			if ($row['framedynamic']) {
				$framedata = RenderingService::applyFrameDynamism($row['frameid'],$framedata);
			}
            $encoding = ConfigurationService::isUnicode() ? 'UTF-8' : 'ISO-8859-1';
			$xml = '<?xml version="1.0" encoding="'.$encoding.'"?>'.
			'<page xmlns="'.$pageNS.'" id="'.$row['id'].'" title="'.Strings::escapeEncodedXML($row['title']).'">'.
			'<meta>'.
			($row['description'] ? '<description>'.Strings::escapeEncodedXML(preg_replace('/\s+/', ' ', $row['description'])).'</description>' : '').
			'<keywords>'.Strings::escapeEncodedXML($row['keywords']).'</keywords>'.
			RenderingService::buildDateTag('published',$row['published']).
			'<language>'.Strings::escapeEncodedXML(strtolower($row['language'])).'</language>'.
			($row['analytics_key'] ? '<analytics key="'.Strings::escapeEncodedXML($row['analytics_key']).'"/>' : '').
			'</meta>'.
			RenderingService::buildPageContext($row['id'],$row['next_page'],$row['previous_page']).
			'<design>'.
			$row['parameters'].
			'</design>'.
			'<frame xmlns="'.$frameNS.'" title="'.Strings::escapeEncodedXML($row['frametitle']).'">'.
			$row['hierarchy'].
			$framedata.
			'</frame>'.
			'<content>'.
			$data.
			'</content>'.
			'</page>';
			return array(
				'id' => $row['id'],
			    'xml' => $xml,
			    'design' => $row['design'],
			    'template' => $template,
			    'published' => $row['published'],
			    'secure' => $row['secure'],
				'language' => strtolower($row['language']),
			    'redirect' => $redirect,
				'dynamic' => $row['dynamic'],
				'framedynamic' => $row['framedynamic']
			);
		}
		else {
			return false;
		}
	}

	static function _getAgent() {
		if (isset($_SERVER['HTTP_USER_AGENT'])) {
			$str = $_SERVER['HTTP_USER_AGENT'];
			$analyzer = new UserAgentAnalyzer($str);
			return $analyzer->getShortID();
		}
		return '';
	}
	
	static function findPage($type) {
		$sql="select page_id from specialpage where type=".Database::text($type)." order by language asc";
		$row = Database::selectFirst($sql);
		if ($row) {
			return intval($row['page_id']);
		}
		return null;
	}
	

	static function buildDateTag($tag,$stamp) {
		return '<'.$tag.' unix="'.$stamp.'" day="'.date('d',$stamp).'" weekday="'.date('d',$stamp).'" yearday="'.date('z',$stamp).'" month="'.date('m',$stamp).'" year="'.date('Y',$stamp).'" hour="'.date('H',$stamp).'" minute="'.date('i',$stamp).'" second="'.date('s',$stamp).'" offset="'.date('Z',$stamp).'" timezone="'.date('T',$stamp).'"/>';
	}
	


	// Returns true if the user has access to the page
	static function userHasAccessToPage($user,$page) {
		$sql = "select * from securityzone_page,securityzone_user where securityzone_page.securityzone_id=securityzone_user.securityzone_id and page_id=".$page." and user_id=".$user;
		if ($row = Database::selectFirst($sql)) {
			return true;
		}
		else {
			return false;
		}
	}
	
	// finds and redirects to the appropriate authentication page for the provided page
	// displays error otherwise
	static function goToAuthenticationPage($id) {
		if ($authId = RenderingService::findAuthenticationPageForPage($id)) {
			Response::redirect('./?id='.$authId.'&page='.$id);
		}
		else {
			echo 'could not find auth page!';
		}
	}

	// Finds the appropriate authentication page for a given page
	// returns the id of the authentication page
	// returns false otherwise
	static function findAuthenticationPageForPage($id) {
		$sql = "select authentication_page_id,page.id from securityzone, securityzone_page,page,page as authpage where securityzone.object_id=securityzone_page.securityzone_id and page.id= securityzone_page.page_id and authpage.id = authentication_page_id and page.id=".$id;
		if ($row = Database::selectFirst($sql)) {
			return $row['authentication_page_id'];
		}
		else {
			return false;
		}
	}
	
	static function showFile($id) {
		$sql = "select * from file where object_id = ".$id;
		if ($row = Database::selectFirst($sql)) {
			Response::redirect('files/'.$row['filename']);
		} else {
			$error = '<title>Filen findes ikke!</title>'.
			'<note>Den forespurgte fil findes ikke på dette website.</note>';
			Log::logPublic('filenotfound','File-id:'.$id);
			RenderingService::displayError($error);
		}
	}
	
	static function getDesign($design) {
		if (Request::exists('designsession')) {
			$_SESSION['debug.design']=Request::getString('designsession');
		}
		if (Request::getBoolean('resetdesign')) {
			unset($_SESSION['debug.design']);
		}
		if (Request::exists('design')) {
			$design = Request::getString('design');
		}
		else if (isset($_SESSION['debug.design'])) {
			$design = $_SESSION['debug.design'];
		}
		return $design;
	}

	static function writePage($id,$path,&$page,$relative,$samePageBaseUrl) {
		if (Request::getBoolean('viewsource')) {
			header('Content-type: text/xml');
			echo $page['xml'];
		} else {
			$html = RenderingService::applyStylesheet($page['xml'],RenderingService::getDesign($page['design']),$page['template'],'',$relative,$relative,$samePageBaseUrl,false,$page['language']);
			header("Last-Modified: " . gmdate("D, d M Y H:i:s",$page['published']) . " GMT");
			header("Cache-Control: public");
			header("Expires: " . gmdate("D, d M Y H:i:s",time()+604800) . " GMT");
			header("Content-Type: text/html; charset=UTF-8");   
            header('X-UA-Compatible: IE=edge');
			echo $html;
			if (!$page['secure'] && !$page['dynamic'] && !$page['framedynamic']) {
				CacheService::createPageCache($page['id'],$path,$html);
			}
		}
	}
	
	static function previewPage($options) {
		$pageId = $options['pageId'];
		$historyId = @$options['historyId'];
		
		$sql="select page.id,UNIX_TIMESTAMP(page.published) as published, page.description,page.language,page.keywords,".
		"page.title,page.dynamic,page.next_page,page.previous_page,".
		"template.unique,frame.id as frameid,frame.title as frametitle,frame.data as framedata,frame.dynamic as framedynamic,".
		" design.parameters,".
		"design.`unique` as design, hierarchy.id as hierarchy".
		" from page,template,frame,design,hierarchy".
		" where page.frame_id=frame.id and page.template_id=template.id and page.design_id=design.object_id".
		" and frame.hierarchy_id=hierarchy.id and page.id=".Database::int($pageId);
		if ($row = Database::selectFirst($sql)) {
			$template = $row['unique'];
			$id = $row['id'];
			if ($historyId>0) {
				$sql = "select data from page_history where id=".Database::int($historyId);
				if ($hist = Database::selectFirst($sql)) {
					$data = $hist['data'];
				} else {
					$data = PageService::getPagePreview($id,$template);
				}
			} else {
				$data = PageService::getPagePreview($id,$template);
			}
		
			$stuff = RenderingService::applyContentDynamism($id,$template,$data);
			$data = $stuff['data'];
	
			$framedata = $row['framedata'];
			if ($row['framedynamic']) {
				$framedata = RenderingService::applyFrameDynamism($row['frameid'],$framedata);
			}
			$design = $row['design'];
			if (Request::exists('design')) {
				$design = Request::getString('design');
			}
			else if (isset($_SESSION['debug.design'])) {
				$design = $_SESSION['debug.design'];
			}
			$xml = '<?xml version="1.0" encoding="UTF-8"?>'.
			'<page xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/" id="'.$id.'" title="'.Strings::escapeEncodedXML($row['title']).'">'.
			'<meta>'.
			'<description>'.Strings::escapeEncodedXML($row['description']).'</description>'.
			'<keywords>'.Strings::escapeEncodedXML($row['keywords']).'</keywords>'.
			RenderingService::buildDateTag('published',$row['published']).
			'<language>'.Strings::escapeEncodedXML(strtolower($row['language'])).'</language>'.
			'</meta>'.
				'<design>'.
				$row['parameters'].
				'</design>'.
			RenderingService::buildPageContext($id,$row['next_page'],$row['previous_page']).
			'<frame xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/frame/1.0/" title="'.Strings::escapeEncodedXML($row['frametitle']).'">'.
			Hierarchy::build($row['hierarchy']).
			$framedata.
			'</frame>'.
			'<content>'.
			$data.
			'</content>'.
			'</page>';
			$relativeUrl = isset($options['relativeUrl']) ? $options['relativeUrl'] : $options['relativePath'];
			return RenderingService::applyStylesheet($xml,$design,$template,$options['relativePath'],$relativeUrl,'','?id='.$id.'&amp;',true,strtolower($row['language']));
		}
		Log::debug('Unable to query: '.$pageId);
		Log::debug($sql);
		return null;
	}
}