<?php
/**
 * @package OnlinePublisher
 * @subpackage Include
 */

/*************** Tools ***************/

function getToolSessionVar($tool,$key,$default=NULL) {
	if (isset($_SESSION['tools.'.$tool.'.'.$key])) {
		return $_SESSION['tools.'.$tool.'.'.$key];
	}
	else {
		setToolSessionVar($tool,$key,$default);
		return $default;
	}
}

function setToolSessionVar($tool,$key,$value) {
	$_SESSION['tools.'.$tool.'.'.$key]=$value;
}

function switchToolSessionVar($tool,$key) {
	setToolSessionVar($tool,$key,!getToolSessionVar($tool,$key));
}

function getRequestToolSessionVar($tool,$key,$query,$default=NULL) {
	if (requestGetExists($query)) {
		setToolSessionVar($tool,$key,requestGetText($query));
	}
	return getToolSessionVar($tool,$key,$default);
}

/**************** services ****************/


function getRequestServiceSessionVar($service,$key,$query,$default=NULL) {
	if (requestGetExists($query)) {
		setServiceSessionVar($service,$key,requestGetText($query));
	}
	return getServiceSessionVar($service,$key,$default);
}

function getServiceSessionVar($service,$key,$default=NULL) {
	if (isset($_SESSION['services.'.$service.'.'.$key])) {
		return $_SESSION['services.'.$service.'.'.$key];
	}
	else {
		setServiceSessionVar($service,$key,$default);
		return $default;
	}
}

function setServiceSessionVar($service,$key,$value) {
	$_SESSION['services.'.$service.'.'.$key]=$value;
}

/************** templates **************/


function getTemplateSessionVar($template,$key,$default=NULL) {
	if (isset($_SESSION['templates.'.$template.'.'.$key])) {
		return $_SESSION['templates.'.$template.'.'.$key];
	}
	else {
		setTemplateSessionVar($template,$key,$default);
		return $default;
	}
}

function setTemplateSessionVar($template,$key,$value) {
	$_SESSION['templates.'.$template.'.'.$key]=$value;
}

function getRequestTemplateSessionVar($template,$key,$query,$default=NULL) {
	if (requestGetExists($query)) {
		setTemplateSessionVar($template,$key,requestGetText($query));
	}
	return getTemplateSessionVar($template,$key,$default);
}

function getRequestTemplateSessionBool($template,$key,$query,$default=false) {
	if (requestGetExists($query)) {
		setTemplateSessionVar($template,$key,requestGetBoolean($query));
	}
	return getTemplateSessionVar($template,$key,$default);
}

/**************** parts ****************/


function getRequestPartSessionVar($part,$key,$query,$default=NULL) {
	if (requestGetExists($query)) {
		setPartSessionVar($part,$key,requestGetText($query));
	}
	return getPartSessionVar($part,$key,$default);
}

function getPartSessionVar($part,$key,$default=NULL) {
	if (isset($_SESSION['parts.'.$part.'.'.$key])) {
		return $_SESSION['parts.'.$part.'.'.$key];
	}
	else {
		setPartSessionVar($part,$key,$default);
		return $default;
	}
}

function setPartSessionVar($part,$key,$value) {
	$_SESSION['parts.'.$part.'.'.$key]=$value;
}


/************** part context ***********/


function getPartContextSessionVar($key,$default=NULL) {
	if (isset($_SESSION['partcontext.'.$key])) {
		return $_SESSION['partcontext.'.$key];
	}
	else {
		setPartContextSessionVar($key,$default);
		return $default;
	}
}

function setPartContextSessionVar($key,$value) {
	$_SESSION['partcontext.'.$key]=$value;
}

/*************** caching ***************/

function getSessionCacheVar($key) {
	if (isset($_SESSION['cache.'.$key])) {
		return $_SESSION['cache.'.$key];
	}
	else {
		return null;
	}
}

function setSessionCacheVar($key,$value) {
	$_SESSION['cache.'.$key]=$value;
}
?>