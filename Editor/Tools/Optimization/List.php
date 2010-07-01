<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/InternalProblem.php';

$kind = Request::getString('kind');

if ($kind=='index') {
	listIndex();
} else if ($kind=='words') {
	listWords();
} else {
	listWarning();
}

function listIndex() {
	$writer = new ListWriter();

	$writer->startList();
	$writer->startHeaders();
	$writer->header(array('title'=>'Side','width'=>'30'));
	$writer->header(array('title'=>'Indeks'));
	$writer->endHeaders();

	$sql = "select `index`,title from page";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$writer->startRow();
		$writer->startCell(array('icon'=>'common/page'))->text($row['title'])->endCell();
		$writer->startCell()->text($row['index'])->endCell();
		$writer->endRow();
	}
	Database::free($result);

	$writer->endList();
}

function listWords() {
	$writer = new ListWriter();
	$allText = '';
	$sql = "select `index` from page";
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$allText.=' '.$row['index'];
	}
	Database::free($result);
	
	$enStopWords = array("a","able","about","across","after","all","almost","also","am","among","an","and","any","are","as","at","be","because","been","but","by","can","cannot","could","dear","did","do","does","either","else","ever","every","for","from","get","got","had","has","have","he","her","hers","him","his","how","however","i","if","in","into","is","it","its","just","least","let","like","likely","may","me","might","most","must","my","neither","no","nor","not","of","off","often","on","only","or","other","our","own","rather","said","say","says","she","should","since","so","some","than","that","the","their","them","then","there","these","they","this","tis","to","too","twas","us","wants","was","we","were","what","when","where","which","while","who","whom","why","will","with","would","yet","you","your");
	$daStopWords = array("og","i","jeg","det","at","en","den","til","er","som","på","de","med","han","af","for","ikke","der","var","mig","sig","men","et","har","om","vi","min","havde","ham","hun","nu","over","da","fra","du","ud","sin","dem","os","op","man","hans","hvor","eller","hvad","skal","selv","her","alle","vil","blev","kunne","ind","når","være","dog","noget","ville","jo","deres","efter","ned","skulle","denne","end","dette","mit","også","under","have","dig","anden","hende","mine","alt","meget","sit","sine","vor","mod","disse","hvis","din","nogle","hos","blive","mange","ad","bliver","hendes","været","thi","jer","sådan");
	$newStopWords = array("nye","one","now","new","ved","use","such","kan","more","used","mere","så","se","samt","hver","");
	$words = preg_split("/[\s,]+/",strtolower($allText));
	array_walk($words, 'wordTrimmer');
	$words = array_diff($words,array_merge($enStopWords,$daStopWords,$newStopWords));
	$counts = array_count_values($words);
	asort($counts);
	$total = count($counts);
	$page = Request::getInt('windowPage');
	$size = 300;
	$counts = array_slice($counts,$page*$size,$size);
	
	$writer->startList();
	$writer->window(array( 'total' => $total, 'size' => $size, 'page' => $page ));
	$writer->startHeaders();
	$writer->header(array('title'=>'Ord','width'=>'50'));
	$writer->header(array('title'=>'Frekvens'));
	$writer->endHeaders();

	foreach ($counts as $word => $freq) {
		$writer->startRow();
		$writer->startCell()->text($word)->endCell();
		$writer->startCell()->text($freq)->endCell();
		$writer->endRow();
	}

	$writer->endList();
}

function wordTrimmer(&$word,$key) {
	$word = trim($word,".\"'():;-*+=");
}

function listWarning() {
	$writer = new ListWriter();

	$writer->startList();
	$writer->startHeaders();
	$writer->header(array('title'=>'Enhed'));
	$writer->header(array('title'=>'Problem'));
	$writer->header();
	$writer->endHeaders();


	$problems = InternalProblem::findProblems();
	foreach ($problems as $problem) {
		$title = $problem->getTitle();
		$entity = $problem->getEntity();
		$writer->startRow();
		if ($entity) {
			$writer->startCell(array('icon'=>'common/page'))->text($entity['title'])->endCell();
		} else {
			$writer->startCell()->endCell();
		}
		$writer->startCell(array('icon'=>'common/warning'))->text($title)->endCell();
		$writer->startCell()->endCell();
		$writer->endRow();
	}
	$writer->endList();
}
?>