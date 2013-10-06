<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Central
 */
require_once '../../../Include/Private.php';

$showTools = Request::getBoolean('tools');
$showTemplates = Request::getBoolean('templates');
$showEmail = Request::getBoolean('email');
$order = Request::getString('order');
$direction = Request::getString('direction');

if (!$order) {
	$order = 'title';
}

$time = 60*60;

$objects = Query::after('remotepublisher')->orderBy($order)->withDirection($direction)->get();

$writer = new ListWriter();

$writer->startList(array('unicode'=>true))->
	sort($order,$direction)->
	startHeaders()->
	header(array('title'=>array('Title','da'=>'Titel'),'key'=>'title','sortable'=>true))->
	header(array('title'=>array('Address','da'=>'Adresse'),'key'=>'url','sortable'=>true));
$writer->header(array('title'=>'Version'));
if ($showTools) {
	$writer->header(array('title'=>array('Tools','da'=>'Værktøjer')));
}
if ($showTemplates) {
	$writer->header(array('title'=>array('Templates','da'=>'Skabeloner')));	
}
if ($showEmail) {
	$writer->header(array('title'=>'E-mail'));
}
$writer->endHeaders();

$sites = array();

foreach ($objects as $site) {
	$data = RemoteDataService::getRemoteData($site->getUrl().'services/info/json/',$time);
	$obj = null;
	if ($data->isHasData()) {
		$str = file_get_contents($data->getFile());
		$obj = Strings::fromJSON($str);
		if ($obj) {
			$version = Dates::formatLongDate($obj->date);			
		} else {
			$version = 'Not set';
		}
	} else {
		$version = 'Unknown';
	}
	$writer->startRow(array('kind'=>'remotepublisher','id'=>$site->getId()))->
		startCell(array('wrap'=>false))->text($site->getTitle())->endCell()->
		startCell()->text($site->getUrl())->endCell()->
		startCell(array('wrap'=>false))->text($version)->endCell();
	if ($showTools) {
		$writer->startCell(array('wrap'=>false));
		writeTools($writer,$obj);
		$writer->endCell();
	}
	if ($showTemplates) {
		$writer->startCell(array('wrap'=>false));
		writeTemplates($writer,$obj);
		$writer->endCell();
	}
	if ($showEmail) {
		$writer->startCell(array('wrap'=>false));
		writeEmail($writer,$obj);
		$writer->endCell();
	}
	$writer->endRow();
}
$writer->endList();

function writeTemplates($writer,$obj) {
	if ($obj && property_exists($obj,'templates')) {
		$installed = $obj->templates->installed;
		$used = $obj->templates->used;
		foreach ($installed as $template) {
			$writer->startLine()->object(array('icon'=>in_array($template,$used) ? 'common/success' : 'monochrome/round_question','text'=>$template))->endLine();
		} 
	} else {
		$writer->object(array('icon'=>'monochrome/warning','text'=>array('Not available','da'=>'Ikke tilgængelig')));		
	}
}

function writeTools($writer,$obj) {
	if ($obj && property_exists($obj,'tools')) {
		$installed = $obj->tools->installed;
		if (!$installed || !is_array($installed)) {
			$writer->text('not an array');
			return;
		}
		Log::debug($installed);
		foreach ($installed as $tool) {
			$writer->startLine()->object(array('icon'=>'common/success','text'=>$tool))->endLine();
		}
	} else {
		$writer->object(array('icon'=>'monochrome/warning','text'=>array('Not available','da'=>'Ikke tilgængelig')));		
	}
}

function writeEmail($writer,$obj) {
	if ($obj && property_exists($obj,'email')) {
		$email = $obj->email;
		if ($email->enabled) {
			$writer->startLine()->object(array('icon'=>'common/success','text'=>array('Enabled','da'=>'Slået til')))->endLine();
		} else {
			$writer->startLine()->object(array('icon'=>'common/stop','text'=>array('Disabled','da'=>'Slået fra')))->endLine();
		}
		$writer->startLine()->object(array('icon'=>$email->server ? 'common/success' : 'common/stop','text'=>'Server'))->endLine();
		$writer->startLine()->object(array('icon'=>$email->username ? 'common/success' : 'common/stop','text'=>array('Username','da'=>'Brugernavn')))->endLine();
		$writer->startLine()->object(array('icon'=>$email->password ? 'common/success' : 'common/stop','text'=>array('Password','da'=>'Kodeord')))->endLine();
		$writer->startLine()->object(array('icon'=>$email->standardEmail ? 'common/success' : 'common/stop','text'=>array('Standard e-email','da'=>'Standard e-post')))->endLine();
		$writer->startLine()->object(array('icon'=>$email->standardName ? 'common/success' : 'common/stop','text'=>array('Standard name','da'=>'Standard navn')))->endLine();
		$writer->startLine()->object(array('icon'=>$email->feedbackEmail ? 'common/success' : 'common/stop','text'=>array('Feedback e-email','da'=>'Feedback e-post')))->endLine();
		$writer->startLine()->object(array('icon'=>$email->feedbackName ? 'common/success' : 'common/stop','text'=>array('Feedback name','da'=>'Feedback navn')))->endLine();
	} else {
		$writer->object(array('icon'=>'monochrome/warning','text'=>array('Not available','da'=>'Ikke tilgængelig')));
	}
}