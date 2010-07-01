/**
* @fileoverview General purpose functions and classes
*/



/**
 * Tests if the browser is based on Apples WebKit
 * @return {bool} True if browser is WebKit based, false otherwise
 */
function isAppleWebKit() {
	return /AppleWebKit/.test(navigator.userAgent);
}

/**
 * Tests if the browser is Opera
 * @return {bool} True if the browser is Opera, false otherwise
 */
function isOpera() {
	/opera [56789]|opera\/[56789]/i.test(navigator.userAgent);
}

/**
 * Tests if the client is a Mac
 * @return {bool} True if the client is a Mac, false otherwise
 */
function isMac() {
	return /mac/i.test(navigator.userAgent.toLowerCase());
}

/**
 * Tests if the browser uses the IE box model
 * @return {bool} True if browser uses IE box model, false otherwise
 */
function isIEbox() {
	var ua = navigator.userAgent;
	var opera = /opera [56789]|opera\/[56789]/i.test(ua);
	var ie = !opera && /MSIE/.test(ua);
	return ie && (document.compatMode == null || document.compatMode != "CSS1Compat");
}

////////////////////////////////////////////////////////////////////////
////                             Events                             ////
////////////////////////////////////////////////////////////////////////

/**
 * Takes an event and inserts mouseX and mouseY for the mouse's x,y coordinates
 * @param {Event} e The event to be altered
 * @returns {Event} The same event with added parameters
 * @deprecated
 */
function fixEvent(e) {
    var posx = 0;
    var posy = 0;
    if (!e) var e = window.event;
    if (e.pageX || e.pageY)
    {
	    posx = e.pageX;
	    posy = e.pageY;
    }
    else if (e.clientX || e.clientY)
    {
	    posx = e.clientX + document.body.scrollLeft;
	    posy = e.clientY + document.body.scrollTop;
    }
    e.mouseX = posx;
    e.mouseY = posy;
    return e;
}

/**
 * Stops an event from being handled by ancestors in the call hierarchy
 * @returns {void}
 * @param {Event} e An event
 */
function stopEventPropagation(e) {
	if (!e) var e = window.event;
	e.cancelBubble = true;
	if (e.stopPropagation) e.stopPropagation();
}


///////////////////////////////////////////////////////////////////////////////
////                            In2iEvent                                  ////
///////////////////////////////////////////////////////////////////////////////

/**
 * Creates a new In2iEvent object from an event
 * @class A wrapper for an event
 * @constructor
 */
function In2iEvent(event) {
    if (!event) {
		this.event = window.event;
	} else {
		this.event=event;
	}
	if (!this.event) this.event={};
}

In2iEvent.prototype.stop = function() {
	stopEventPropagation(this.event);
}

/**
 * Get the cursors distance to the left of the document
 * @return {int} The distance of the cursor to the left of the document
 */
In2iEvent.prototype.mouseLeft = function() {
    var left = 0;
    if (this.event.pageX) {
	    left = this.event.pageX;
    }
    else if (this.event.clientX) {
	    left = this.event.clientX + document.body.scrollLeft;
    }
    return left;
}


/**
 * Get the cursors distance to the top of the document
 * @return {int} The distance of the cursor to the top of the document
 */
In2iEvent.prototype.mouseTop = function() {
    var top = 0;
    if (this.event.pageY) {
	    top = this.event.pageY;
    }
    else if (this.event.clientY) {
	    top = this.event.clientY + document.body.scrollTop;
    }
    return top;
}

/**
 * Get the source element of the event
 * @return {Element} The source element of the event
 */
In2iEvent.prototype.source = function() {
	var tg = (this.event.target) ? this.event.target : this.event.srcElement;
	return tg;
}


In2iEvent.prototype.shiftKey = function() {
	return (this.event.shiftKey ? true : false);
}

In2iEvent.prototype.ctrlKey = function() {
	return (this.event.ctrlKey ? true : false);
}

In2iEvent.prototype.selectKey = function() {
	if (isMac()) {
		return this.event.metaKey;
	} else {
		return this.ctrlKey();
	}
}

/////////////////////////////////////////////////////////////////////////
////                          Positioning                            ////
/////////////////////////////////////////////////////////////////////////

/**
 * @private
 */
function getInnerLeft(el) {
	//alert('');
	if (el == null) return 0;
	if (isIEbox() && el == document.body || !isIEbox() && el == document.documentElement) return 0;
	return getLeft(el) + getBorderLeft(el);
}

/**
 * @private
 */
function getLeft(el) {
	//alert(el);
	if (el == null) {
		return 0;
	} else {
		return el.offsetLeft + getInnerLeft(el.offsetParent);
	}
}

/**
 * @private
 */
function getInnerTop(el) {
	if (el == null) return 0;
	if (isIEbox() && el == document.body || !isIEbox() && el == document.documentElement) return 0;
	return getTop(el) + getBorderTop(el);
}

/**
 * @private
 */
function getTop(el) {
	if (el == null) {
		return 0;
	} else {
		return el.offsetTop + getInnerTop(el.offsetParent);
	}
}

/**
 * Finds an elements absolute distance to the left of the page
 * @param {Object} obj The element to analyze
 * @return {int} The distance in pixels to the left of the page
 */
function getAbsoluteElementLeft(obj) {
	return getLeft(obj);
}

/**
 * Finds an elements absolute distance to the top of the page
 * @param {Object} obj The element to analyze
 * @return {int} The distance in pixels to the top of the page
 */
function getAbsoluteElementTop(obj) {
	return getTop(obj);
}

/**
 * Finds an elements width as displayed by the browser
 * @param {Object} obj The element to analyze
 * @return {int} The width in pixels of the element
 */
function getElementDisplayWidth(obj) {
	return obj.offsetWidth;
}

/**
 * Finds an elements height as displayed by the browser
 * @param {Object} obj The element to analyze
 * @return {int} The height in pixels of the element
 */
function getElementDisplayHeight(obj) {
	return obj.offsetHeight;
}


///////////////////////////////////////////////////////////////////
////                       Get style                           ////
///////////////////////////////////////////////////////////////////

/**
 * Find the width of an elements left border
 * @param {Element} el The element to analyze
 * @return {int} The width of the elements left border
 */
function getBorderLeft(el) {
	try {
		return el.clientLeft ?
		el.clientLeft :
		getDisplayStyleInt(el,"border-left-width");
	} catch (e) {
		return 0;
	}
}

/**
 * Find the width of an elements top border
 * @param {Element} el The element to analyze
 * @return {int} The width of the elements top border
 */
function getBorderTop(el) {
	try {
		return el.clientTop ?
		el.clientTop :
		getDisplayStyleInt(el,"border-top-width");
	} catch (e) {
		return 0;
	}
}

/**
 * Finds an elements css style as displayed by the browser
 * @param {Element} element The element to analyze
 * @param {String} property The css property to find
 * @return {String} The css value of the property, undefined if the property is not set
 */
function getDisplayStyle(element,property) {
	if (element.currentStyle)
		var value = element.currentStyle[property];
	else if (window.getComputedStyle)
		var value = document.defaultView.getComputedStyle(element,null).getPropertyValue(property);
	return value;
}

/**
 * Finds an elements css style as an integer as displayed by the browser
 * @param {Element} element The element to analyze
 * @param {String} property The css property to find
 * @return {int} The css int value of the property, 0 if the property is not set
 */
function getDisplayStyleInt(element,property) {
	var val = parseInt(getDisplayStyle(element,property));
	if (isNaN(val)) {
		val = 0;
	}
	return val;
}

/**
 * Builds an object with an elements left,top,width and height
 * @param {Element} el The element to analyse
 * @return {Object} An object containing top,left,width and height of the element
 */
function getOuterRect(element) {
	return {
		left:	getLeft(element),
		top:	getTop(element),
		width:	getElementDisplayWidth(element),
		height:	getElementDisplayHeight(element)
	};
}

/**
 * Cross platform way of getting an element by name
 * @param {String} name The name/id of the element to find
 * @return {Object} A wrapper for the object
 */
function getObj(name)
{
  if (document.getElementById)
  {
  	this.obj = document.getElementById(name);
	this.style = document.getElementById(name).style;
  }
  else if (document.all)
  {
	this.obj = document.all[name];
	this.style = document.all[name].style;
  }
  else if (document.layers)
  {
	this.obj = getObjNN4(document,name);
	this.style = this.obj;
  }
}

/**
 * @private
 */
function getObjNN4(obj,name)
{
	var x = obj.layers;
	var foundLayer;
	for (var i=0;i<x.length;i++)
	{
		if (x[i].id == name)
		 	foundLayer = x[i];
		else if (x[i].layers.length)
			var tmp = getObjNN4(x[i],name);
		if (tmp) foundLayer = tmp;
	}
	return foundLayer;
}

/**
 * Finds how far the window has scrolled from the top
 * @return {int} The number of pixels the window is scrolled from the top
 */
function getWindowScrollTop() {
	var x,y;
	if (self.pageYOffset) // all except Explorer
	{
		y = self.pageYOffset;
	}
	else if (document.documentElement && document.documentElement.scrollTop)
		// Explorer 6 Strict
	{
		y = document.documentElement.scrollTop;
	}
	else if (document.body) // all other Explorers
	{
		y = document.body.scrollTop;
	}
	return y;
}

/**
 * Finds how far the window has scrolled from the left
 * @return {int} The number of pixels the window is scrolled from the left
 */
function getWindowScrollLeft() {
	var x;
	if (self.pageYOffset) // all except Explorer
	{
		x = self.pageXOffset;
	}
	else if (document.documentElement && document.documentElement.scrollTop)
		// Explorer 6 Strict
	{
		x = document.documentElement.scrollLeft;
	}
	else if (document.body) // all other Explorers
	{
		x = document.body.scrollLeft;
	}
	return x;
}

/**
 * Finds the height of the windows visible view of the document
 * @return {int} The height of the windows view of the document in pixels
 */
function getWindowInnerHeight() {
	var y;
	if (self.innerHeight) // all except Explorer
	{
		y = self.innerHeight;
	}
	else if (document.documentElement && document.documentElement.clientHeight)
		// Explorer 6 Strict Mode
	{
		y = document.documentElement.clientHeight;
	}
	else if (document.body) // other Explorers
	{
		y = document.body.clientHeight;
	}
	return y;
}

/**
 * Finds the width of the windows visible view of the document
 * @return {int} The width of the windows view of the document in pixels
 */
function getWindowInnerWidth() {
	var x;
	if (self.innerHeight) // all except Explorer
	{
		x = self.innerWidth;
	}
	else if (document.documentElement && document.documentElement.clientHeight)
		// Explorer 6 Strict Mode
	{
		x = document.documentElement.clientWidth;
	}
	else if (document.body) // other Explorers
	{
		x = document.body.clientWidth;
	}
	return x;
}

/**
 * Finds the dimensions of the visible area of the document
 * @return {Object} An object with left,top,width,height
 */
function getDocumentRect() {
	return {
		left:	0,
		top:	0,
		width:	getWindowInnerWidth(),
		height:	getWindowInnerHeight()
	};
}

/**
 * Finds how much the window is scrolled
 * @return {Object} An object with left,top
 */
function getScrollPos() {
	return {
		left:	getWindowScrollLeft(),
		top:	getWindowScrollTop()
	};
}

////////////////////////////////////////////////////////////////////////////////
//                                Cookies                                     //
////////////////////////////////////////////////////////////////////////////////

/**
 * Creates a new In2iCookie object
 * @class Class used to set an get cookies, note that cookies are stored as strings!
 * @constructor
 */
function In2iCookie() {
	if (document.cookie.length) { this.cookies = ' ' + document.cookie; }
}

In2iCookie.prototype.fix = function (value) {
	return value.replace(/=/g,'x');
}

/**
 * Sets a cookie
 * @param {String} key The key of the cookie
 * @param {String} value The value of the cookie
 * @return {void}
 */
In2iCookie.prototype.setCookie = function (key, value) {
	key = this.fix(key);
	document.cookie = key + "=" + escape(value);
}

/**
 * Gets a cookie
 * @param {String} key The key of the cookie to retrieve
 * @return {String} The value of the cookie, null if the cookie doesn't exist
 */
In2iCookie.prototype.getCookie = function (key) {
	key = this.fix(key);
	if (this.cookies) {
		var start = this.cookies.indexOf(' ' + key + '=');
		if (start == -1) { return null; }
		var end = this.cookies.indexOf(";", start);
		if (end == -1) { end = this.cookies.length; }
		end -= start;
		var cookie = this.cookies.substr(start,end);
		return unescape(cookie.substr(cookie.indexOf('=') + 1, cookie.length - cookie.indexOf('=') + 1));
	}
	else { return null; }
}
