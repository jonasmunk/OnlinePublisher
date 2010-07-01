var N2i = {};

/**
 * @todo Maybe improve performace
 */
function $id() {
	var elements = new Array();

	for (var i = 0; i < arguments.length; i++) {
		var element = arguments[i];
		if (typeof element == 'string') {
			element = document.getElementById(element);
		}
		if (arguments.length == 1) {
			return element;			
		}
		elements.push(element);
	}

	return elements;
}

function $class(className,parentElement) {
	var children = ($id(parentElement) || document.body).getElementsByTagName('*');
	var elements = [];
	for (var i=0;i<children.length;i++) {
		if (N2i.Element.hasClassName(children[i],className)) {
			elements[elements.length] = children[i];
		}
	}
	return elements;
}

function $tag(name,parentElement) {
	parentElement = parentElement ? $id(parentElement) : document.body;
	return parentElement.getElementsByTagName(name);
}

function $ani(element,style,value,duration,delegate) {
	if (N2i.Animation) {
		N2i.Animation.get(element).animate(null,value,style,duration,delegate);
	}
}

function $get(url,delegate,options) {
	var req = new N2i.Request(delegate);
	req.request(url,options);
}

/**
 * Implement push on array if not implemented
 */
if (!Array.prototype.push) {
	Array.prototype.push = function() {
		var startLength = this.length;
		for (var i = 0; i < arguments.length; i++) {
			this[startLength + i] = arguments[i];
		}
		return this.length;
	}
}


///////////////////////////////////////// Util //////////////////////////////////////

N2i.override = function(orig,subj) {
	for (item in subj) {
		orig[item] = subj[item];
	}
}

N2i.camelize = function(str) {
    var oStringList = str.split('-');
    if (oStringList.length == 1) return oStringList[0];

    var camelizedString = str.indexOf('-') == 0
      ? oStringList[0].charAt(0).toUpperCase() + oStringList[0].substring(1)
      : oStringList[0];

    for (var i = 1, len = oStringList.length; i < len; i++) {
      var s = oStringList[i];
      camelizedString += s.charAt(0).toUpperCase() + s.substring(1);
    }

    return camelizedString;
}

N2i.log = function(obj) {
	try {
		console.log(obj);
	} catch (ignore) {};
}

N2i.objToString = function (obj,level) {
	var str = '';
	level = level | 0;
	level+=2;
	if (typeof(obj)=='object') {
		str+='{';
		for (var prop in obj) {
			var value = obj[prop];
			if (value==null) {
				value='null';
			} else if (typeof(value)=='string') {
				value = '\''+value+'\'';
			} else if (typeof(value)=='object') {
				value = N2i.objToString(value,level);
			}
			str+='\n';
			for (var i=0;i<level;i++) {
				str+=' ';
			}
			str+=prop+' : '+value;
		}
		str+='\n}';
	}
	else {
		str=obj;
	}
	return str;
}

N2i.isIE = function() {
	return navigator.userAgent.indexOf('MSIE')!=-1;
}

/////////////////////////////////////// Element /////////////////////////////////////

N2i.Element = function() {
	
}

N2i.Element.removeClassName = function(element, className) {
	element = $id(element);
	if (!element) return;		

	var newClassName = '';
	var a = element.className.split(' ');
	for (var i = 0; i < a.length; i++) {
		if (a[i] != className) {
			if (i > 0) {
				newClassName += ' ';				
			}
			newClassName += a[i];
		}
	}
	element.className = newClassName;
}

N2i.Element.hasClassName = function(element, className) {
	element = $id(element);
	if (!element) return;
	
	var a = element.className.split(' ');
	for (var i = 0; i < a.length; i++) {
		if (a[i] == className) {
			return true;
		}
	}
	return false;
}

N2i.Element.addClassName = function(element, className) {
    element = $id(element);
	if (!element) return;
	
    N2i.Element.removeClassName(element, className);
    element.className += ' ' + className;
}


N2i.Element.scrollTo = function(element) {
	element = $id(element);
	window.scrollTo(N2i.Element.getLeft(element), N2i.Element.getTop(element)-20);
}

N2i.Element.getLeft = function(element) {
    element = $id(element);
	if (element) {
		xPos = element.offsetLeft;
		tempEl = element.offsetParent;
		while (tempEl != null) {
			xPos += tempEl.offsetLeft;
			tempEl = tempEl.offsetParent;
		}
		return xPos;
	}
	else return 0;
}


N2i.Element.getTop = function(element) {
    element = $id(element);
	if (element) {
		yPos = element.offsetTop;
		tempEl = element.offsetParent;
		while (tempEl != null) {
			yPos += tempEl.offsetTop;
			tempEl = tempEl.offsetParent;
		}
		return yPos;
	}
	else return 0;
}

/**
 * Finds an elements width as displayed by the browser
 * @param {Object} obj The element to analyze
 * @return {int} The width in pixels of the element
 */
N2i.Element.getWidth = function(element) {
	element = $id(element);
	return element.offsetWidth;
}

/**
 * Finds an elements height as displayed by the browser
 * @param {Object} obj The element to analyze
 * @return {int} The height in pixels of the element
 */
N2i.Element.getHeight = function(element) {
	element = $id(element);
	return element.offsetHeight;
}

N2i.Element.getRect = function(element) {
	return {
		left:	N2i.Element.getLeft(element),
		top:	N2i.Element.getTop(element),
		width:	N2i.Element.getWidth(element),
		height:	N2i.Element.getHeight(element)
	};
}


N2i.Element.getStyle = function(element, style) {
	element = $id(element);
	var cameled = N2i.camelize(style);
	var value = element.style[cameled];
	if (!value) {
		if (document.defaultView && document.defaultView.getComputedStyle) {
			var css = document.defaultView.getComputedStyle(element, null);
			value = css ? css.getPropertyValue(style) : null;
		} else if (element.currentStyle) {
			value = element.currentStyle[cameled];
		}
	}
	if (window.opera && ['left', 'top', 'right', 'bottom'].include(style)) {
		if (N2i.Element.getStyle(element, 'position') == 'static') value = 'auto';
	}
	return value == 'auto' ? null : value;
}

////////////////////////////////////// Window ////////////////////////////////


N2i.Window = function() {}

/**
 * Finds how far the window has scrolled from the top
 * @return {int} The number of pixels the window is scrolled from the top
 */
N2i.Window.getScrollTop = function() {
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
N2i.Window.getScrollLeft = function() {
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
 * Finds how much the window is scrolled
 * @return {Object} An object with left,top
 */
N2i.Window.getScrollPosition = function() {
	return {
		left:	N2i.Window.getScrollLeft(),
		top:	N2i.Window.getScrollTop()
	};
}

/**
 * Finds the height of the windows visible view of the document
 * @return {int} The height of the windows view of the document in pixels
 */
N2i.Window.getInnerHeight = function() {
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
N2i.Window.getInnerWidth = function() {
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
N2i.Window.getDocumentRect = function() {
	return {
		left:	0,
		top:	0,
		width:	N2i.Window.getInnerWidth(),
		height:	N2i.Window.getInnerHeight()
	};
}

////////////////////////////////////// Event /////////////////////////////////

/**
 * Creates a new In2iEvent object from an event
 * @class A wrapper for an event
 * @constructor
 */
N2i.Event = function(event) {
    if (!event) {
		this.event = window.event;
	} else {
		this.event=event;
	}
}

/**
 * Get the cursors distance to the left of the document
 * @return {int} The distance of the cursor to the left of the document
 */
N2i.Event.prototype.mouseLeft = function() {
    var left = 0;
	if (this.event) {
	    if (this.event.pageX) {
		    left = this.event.pageX;
	    }
	    else if (this.event.clientX) {
		    left = this.event.clientX + document.body.scrollLeft;
	    }
	}
    return left;
}


/**
 * Get the cursors distance to the top of the document
 * @return {int} The distance of the cursor to the top of the document
 */
N2i.Event.prototype.mouseTop = function() {
    var top = 0;
	if (this.event) {
	    if (this.event.pageY) {
		    top = this.event.pageY;
	    }
	    else if (this.event.clientY) {
		    top = this.event.clientY + document.body.scrollTop;
	    }
	}
    return top;
}


N2i.Event.addListener = function(el,type,listener,useCapture) {
	el = $id(el);
	if(document.addEventListener) {
		// W3C DOM Level 2 Events - used by Mozilla, Opera and Safari
		if(!useCapture) {useCapture = false;} else {useCapture = true;} {
			el.addEventListener(type,listener,useCapture);
		}
	} else {
		// MS implementation - used by Internet Explorer
		el.attachEvent('on'+type, listener);
	}
}

N2i.Event.removeListener = function(el,type,listener,useCapture) {
	el = $id(el);
	if(document.removeEventListener) {
		// W3C DOM Level 2 Events - used by Mozilla, Opera and Safari
		if(!useCapture) {useCapture = false;} else {useCapture = true;} {
			el.removeEventListener(type,listener,useCapture);
		}
	} else {
		// MS implementation - used by Internet Explorer
		el.detachEvent('on'+type, listener);
	}
}

N2i.Event.stop = function(e) {
	if (!e) var e = window.event;
	e.cancelBubble = true;
	if (e.stopPropagation) e.stopPropagation();
}

N2i.Event.addLoadListener = function(delegate) {
	if(typeof window.addEventListener != 'undefined')
	{
		//.. gecko, safari, konqueror and standard
		window.addEventListener('load', delegate, false);
	}
	else if(typeof document.addEventListener != 'undefined')
	{
		//.. opera 7
		document.addEventListener('load', delegate, false);
	}
	else if(typeof window.attachEvent != 'undefined')
	{
		//.. win/ie
		window.attachEvent('onload', delegate);
	}

	//** remove this condition to degrade older browsers
	else
	{
		//.. mac/ie5 and anything else that gets this far
	
		//if there's an existing onload function
		if(typeof window.onload == 'function')
		{
			//store it
			var existing = window.onload;
		
			//add new onload handler
			window.onload = function()
			{
				//call existing onload function
				existing();
			
				//call delegate onload function
				delegate();
			};
		}
		else
		{
			//setup onload function
			window.onload = delegate;
		}
	}
}


/************************************* Request ******************************/

N2i.Request = function(delegate) {
	this.delegate = delegate;
	this.options = {method:'GET',async:true};
}

N2i.Request.prototype.request = function(url,options) {
	N2i.override(this.options,options);
	this.initTransport();
	var req = this.transport;
	var self = this;
	req.onreadystatechange = function() {
		try {
			if (req.readyState == 4) {
				if (req.status == 200) {
					self.callDelegate('onSuccess');
				} else {
					self.callDelegate('onFailure');
				}
			}
		} catch (e) {
			N2i.log(e);
		}
	};
	var method = this.options.method.toUpperCase();
	req.open(method, url, this.options.async);
	var parameters = null;
    if (method=='POST' && this.options.parameters) {
		parameters = this.buildPostBody(this.options.parameters);
		req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		req.setRequestHeader("Content-length", parameters.length);
		req.setRequestHeader("Connection", "close");
	}
	req.send(this.buildPostBody(this.options.parameters));
}

N2i.Request.prototype.buildPostBody = function(parameters) {
	if (!parameters) return null;
	var output = '';
	for (param in parameters) {
		if (output.length>0) output+='&';
		output+=encodeURIComponent(param)+'='+encodeURIComponent(parameters[param]);
	}
	return output;
}

N2i.Request.prototype.callDelegate = function(method) {
	if (this.delegate && this.delegate[method]) {
		this.delegate[method](this.transport);
	}
}

N2i.Request.prototype.initTransport = function() {
	this.transport = N2i.Request.createTransport();
}

N2i.Request.createTransport = function() {
	try {
		if (window.XMLHttpRequest) {
			var req = new XMLHttpRequest();
			if (req.readyState == null) {
				req.readyState = 1;
				req.addEventListener("load", function () {
					req.readyState = 4;
					if (typeof req.onreadystatechange == "function")
						req.onreadystatechange();
				}, false);
			}
			return req;
		}
		else if (window.ActiveXObject) {
			return N2i.Request.getActiveX();
		} else {
			// Could not create transport
			this.delegate.onError(this);
		}
	}
	catch (ex) {
		if (this.delegate.onError) {
			this.delegate.onError(this,ex);
		}
	}
}

N2i.Request.getActiveX = function() {
	if (getXmlHttpPrefix.prefix)
		return getXmlHttpPrefix.prefix;
	
	var prefixes = ["MSXML2", "Microsoft", "MSXML", "MSXML3"];
	var o;
	for (var i = 0; i < prefixes.length; i++) {
		try {
			// try to create the objects
			o = new ActiveXObject(prefixes[i] + ".XmlHttp");
			return o;
		}
		catch (ex) {};
	}
	
	throw new Error("Could not find an installed XML parser");
}




N2i.Log = function() {
	
}

N2i.Log.ensure = function() {
	if (!N2i.Log.log) {
		N2i.Log.log = new N2i.Log.Logger();
	}
}

N2i.Log.debug = function(msg) {
	N2i.Log.ensure();
	N2i.Log.log.appendLine(msg);
}

N2i.Log.Logger = function() {
	this.data = document.createElement('textarea');
	this.data.style.height='200px';
	this.data.style.width='300px';
	this.data.style.fontSize='9px';
	document.body.appendChild(this.data);
}

N2i.Log.Logger.prototype.appendLine = function(msg) {
	this.data.value=msg+"\n"+this.data.value;
}



N2i.Browser = function() {
	
}

N2i.Browser.isIE = function() {
	var ua = navigator.userAgent;
	var opera = /opera [56789]|opera\/[56789]/i.test(ua);
	var ie = !opera && /MSIE/.test(ua);
	return ie;
}

/**
 * Tests if the browser is Opera
 * @return {bool} True if the browser is Opera, false otherwise
 */
N2i.Browser.isOpera = function() {
	return /opera [56789]|opera\/[56789]/i.test(navigator.userAgent);
}