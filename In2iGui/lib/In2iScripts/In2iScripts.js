/**
 * Base
 */
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
		if (N2i.hasClass(children[i],className)) {
			elements[elements.length] = children[i];
		}
	}
	return elements;
}

function $firstClass(className,parentElement) {
	var children = ($id(parentElement) || document.body).getElementsByTagName('*');
	for (var i=0;i<children.length;i++) {
		if (N2i.hasClass(children[i],className)) {
			return children[i];
		}
	}
	return null;
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

N2i.override = function(original,subject) {
	if (subject) {
		for (prop in subject) {
			original[prop] = subject[prop];
		}
	}
	return original;
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

N2i.escapeHTML = function(str) {
    var div = document.createElement('div');
    var text = document.createTextNode(str);
    div.appendChild(text);
    return div.innerHTML;
}

/////////////////////////////////////// Element /////////////////////////////////////

N2i.create = function(name,attributes,styles,properties) {
	var element = document.createElement(name);
	if (attributes) {
		for (attribute in attributes) {
			if (attribute=='class') {
				element.className = attributes[attribute];
			} else {
				element.setAttribute(attribute,attributes[attribute]);
			}
		}
	}
	if (styles) {
		for (style in styles) {
			element.style[style] = styles[style];
		}
	}
	if (properties) {
		for (property in properties) {
			element[property] = properties[property];
		}
	}
	return element;
}

N2i.removeChildren = function(node) {
	var children = node.childNodes;
	for (var i = children.length - 1; i >= 0; i--){
		node.removeChild(children[i]);
	};
}

N2i.ELEMENT_NODE=1;
N2i.ATTRIBUTE_NODE=2;
N2i.TEXT_NODE=3;

N2i.Element = {}

N2i.Element.removeClassName = N2i.removeClass = function(element, className) {
	element = $id(element);
	if (!element) return;		

	var newClassName = '';
	var a = element.className.split(/\s+/);
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

N2i.Element.hasClassName = N2i.hasClass = function(element, className) {
	element = $id(element);
	if (!element) return;
	var a = element.className.split(/\s+/);
	for (var i = 0; i < a.length; i++) {
		if (a[i] == className) {
			return true;
		}
	}
	return false;
}

N2i.Element.addClassName = N2i.addClass = function(element, className) {
    element = $id(element);
	if (!element) return;
	
    N2i.Element.removeClassName(element, className);
    element.className += ' ' + className;
}

N2i.toggleClass = function(element,className) {
	if (N2i.hasClass(element,className)) {
		N2i.removeClass(element,className);
	} else {
		N2i.addClass(element,className);
	}
}

N2i.setClass = function(element,className,add) {
	if (add) {
		N2i.addClass(element,className);
	} else {
		N2i.removeClass(element,className);
	}
}


N2i.Element.scrollTo = function(element) {
	element = $id(element);
	window.scrollTo(N2i.Element.getLeft(element), N2i.Element.getTop(element)-20);
}

N2i.Element.getLeft = N2i.getLeft = function(element) {
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


N2i.Element.getTop = N2i.getTop = function(element) {
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
N2i.Element.getWidth = N2i.getWidth = function(element) {
	element = $id(element);
	return element.offsetWidth;
}

/**
 * Finds an elements height as displayed by the browser
 * @param {Object} obj The element to analyze
 * @return {int} The height in pixels of the element
 */
N2i.Element.getHeight = N2i.getHeight = function(element) {
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


N2i.Element.getStyle = N2i.getStyle = function(element, style) {
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

N2i.setOpacity = function(element,opacity) {
	if (N2i.isIE()) {
		if (opacity==1) {
			element.style['filter']=null;
		} else {
			element.style['filter']='alpha(opacity='+(opacity*100)+')';
		}
	} else {
		element.style['opacity']=opacity;
	}
}

N2i.getDocumentHeight = function() {
	if (window.scrollMaxY && window.innerHeight) {
		return window.scrollMaxY+window.innerHeight;
	} else {
		return Math.max(document.body.clientHeight,document.documentElement.clientHeight,document.documentElement.scrollHeight);
	}
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

N2i.Event.prototype = {
	mouseLeft : function() {
	    var left = 0;
		if (this.event) {
		    if (this.event.pageX) {
			    left = this.event.pageX;
		    } else if (this.event.clientX) {
			    left = this.event.clientX + document.body.scrollLeft;
		    }
		}
	    return left;
	},
	mouseTop : function() {
	    var top = 0;
		if (this.event) {
		    if (this.event.pageY) {
			    top = this.event.pageY;
		    } else if (this.event.clientY) {
			    top = this.event.clientY + document.body.scrollTop;
		    }
		}
	    return top;
	},
	getTarget : function() {
		return this.event.target != null ? this.event.target : this.event.srcElement;
	},
	isReturnKey : function() {
		return this.event.keyCode==13;
	},
	isRightKey : function() {
		return this.event.keyCode==39;
	},
	isLeftKey : function() {
		return this.event.keyCode==37;
	},
	isEscapeKey : function() {
		return this.event.keyCode==27;
	},
	isSpaceKey : function() {
		N2i.log(this.event.keyCode);
		return this.event.keyCode==32;
	},
	stop : function() {
//		this.event.returnValue = false;
//		N2i.Event.stop(this.event);
	}
}


N2i.Event.addListener = N2i.addListener = function(el,type,listener,useCapture) {
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

N2i.Event.removeListener = N2i.removeListener = function(el,type,listener,useCapture) {
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

N2i.Event.addLoadListener = N2i.addLoadListener = function(delegate) {
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

N2i.Location = {};

N2i.Location.getParameter = function(name) {
	var parms = N2i.Location.getParameters();
	for (var i=0; i < parms.length; i++) {
		if (parms[i].name==name) {
			return parms[i].value;
		}
	};
	return null;
}

N2i.Location.setParameter = function(name,value) {
	var parms = N2i.Location.getParameters();
	var found = false;
	for (var i=0; i < parms.length; i++) {
		if (parms[i].name==name) {
			parms[i].value=value;
			found=true;
			break;
		}
	};
	if (!found) {
		parms.push({name:name,value:value});
	}
	N2i.Location.setParameters(parms);
}

N2i.Location.setParameters = function(parms) {
	var query = '';
	for (var i=0; i < parms.length; i++) {
		query+= i==0 ? '?' : '&';
		query+=parms[i].name+'='+parms[i].value;
	};
	document.location.search=query;
}

N2i.Location.getBoolean = function(name) {
	var value = N2i.Location.getParameter(name);
	return (value=='true' || value=='1');
}

N2i.Location.getParameters = function() {
	var items = document.location.search.substring(1).split('&');
	var parsed = [];
	for( var i = 0; i < items.length; i++) {
		var item = items[i].split('=');
		var name = unescape(item[0]).replace(/^\s*|\s*$/g,"");
		var value = unescape(item[1]).replace(/^\s*|\s*$/g,"");
		if (name) {
			parsed.push({name:name,value:value});
		}
	};
	return parsed;
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
					if (req.responseXML && req.responseXML.documentElement) {
						if (self.callDelegate('onXML',req.responseXML)) {
							return;
						}
					} else {
						if (self.callDelegate('onText',req.responseXML)) {
							return;
						}
					}
					self.callDelegate('onSuccess',req);
				} else {
					self.callDelegate('onFailure',req);
				}
			}
		} catch (e) {
			N2i.log(e);
		}
	};
	var method = this.options.method.toUpperCase();
	req.open(method, url, this.options.async);
	var parameters = null;
	var body = '';
    if (method=='POST' && this.options.parameters) {
		body = this.buildPostBody(this.options.parameters);
		req.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
		req.setRequestHeader("Content-length", body.length);
		req.setRequestHeader("Connection", "close");
	}
	req.send(body);
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

N2i.Request.prototype.callDelegate = function(method,variable) {
	if (this.delegate && this.delegate[method]) {
		this.delegate[method](variable);
		return true;
	}
	return false;
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

N2i.Preloader = function(options) {
	this.options = options || {};
	this.delegate = {};
	this.images = [];
	this.loaded = 0;
}

N2i.Preloader.prototype = {
	addImages : function(imageOrImages) {
		if (typeof(imageOrImages)=='object') {
			for (var i=0; i < imageOrImages.length; i++) {
				this.images.push(imageOrImages[i]);
			};
		} else {
			this.images.push(imageOrImages);
		}
	},
	setDelegate : function(d) {
		this.delegate = d;
	},
	load : function() {
		var self = this;
		this.obs = [];
		for (var i=0; i < this.images.length; i++) {
			var img = new Image();
			img.n2iPreloaderIndex = i;
			img.onload = function() {self.imageChanged(this.n2iPreloaderIndex,'imageDidLoad')};
			img.onerror = function() {self.imageChanged(this.n2iPreloaderIndex,'imageDidGiveError')};
			img.onabort = function() {self.imageChanged(this.n2iPreloaderIndex,'imageDidAbort')};
			img.src = (this.options.context ? this.options.context : '')+this.images[i];
			this.obs.push(img);
		};
	},
	imageChanged : function(index,method) {
		this.loaded++;
		if (this.delegate[method]) {
			this.delegate[method](this.loaded,this.images.length,index);
		}
		if (this.loaded==this.images.length && this.delegate.allImagesDidLoad) {
			this.delegate.allImagesDidLoad();
		}
	}
}

///////////////////////////////////// Strings /////////////////////////////////////

N2i.trim = function(str) {
	if (!str) return str;
	return str.replace(/^[\s\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000]+|[\s\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000]+$/g, '');
}

N2i.isEmpty = function(str) {
	if (str==null || typeof(str)=='undefined') return true;
	return N2i.trim(str).length==0;
}

///////////////////////////////////// Arrays /////////////////////////////////////

N2i.inArray = function(arr,value) {
	for (var i=0; i < arr.length; i++) {
		if (arr[i]==value) return true;
	};
}


N2i.flipInArray = function(arr,value) {
	if (N2i.inArray(arr,value)) {
		N2i.removeFromArray(arr,value);
	} else {
		arr.push(value);
	}
}

N2i.removeFromArray = function(arr,value) {
	for (var i = arr.length - 1; i >= 0; i--){
		if (arr[i]==value) {
			arr.splice(i,1);
		}
	};
}

N2i.addToArray = function(arr,value) {
	if (value.constructor==Array) {
		for (var i=0; i < value.length; i++) {
			if (!N2i.inArray(arr,value[i])) {
				arr.push(value);
			}
		};
	} else {
		if (!N2i.inArray(arr,value)) {
			arr.push(value);
		}
	}
}

/////////////////////////////////////////////// Browsers //////////////////////////////////////

N2i.isIE = function() {
	var ua = navigator.userAgent;
	var opera = /opera [56789]|opera\/[56789]/i.test(ua);
	var ie = !opera && /MSIE/.test(ua);
	return ie;
}

////////////////////////////////////////////// Cookie ///////////////////////////////////////////

N2i.Cookie = {
	set : function(name,value,days) {
		if (days) {
			var date = new Date();
			date.setTime(date.getTime()+(days*24*60*60*1000));
			var expires = "; expires="+date.toGMTString();
		}
		else var expires = "";
		document.cookie = name+"="+value+expires+"; path=/";
	},
	get : function(name) {
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1,c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
		}
		return null;
	},
	clear : function(name) {
		this.set(name,"",-1);
	}
}

N2i.getFrameDocument = function(frame) {
    if (frame.contentDocument) {
        return frame.contentDocument;
    } else if (frame.contentWindow) {
        return frame.contentWindow.document;
    } else if (frame.document) {
        return frame.document;
    } else {
		alert(frame.contentDocument);
	}
}

/* EOF */