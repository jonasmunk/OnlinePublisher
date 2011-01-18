/** @namespace */
var n2i = {
	/** @namespace */
	browser : {},
	ELEMENT_NODE : 1,
	ATTRIBUTE_NODE : 2,
	TEXT_NODE : 3,
	KEY_BACKSPACE : 8,
    KEY_TAB : 9,
    KEY_RETURN : 13,
    KEY_ESC : 27,
    KEY_LEFT : 37,
    KEY_UP : 38,
    KEY_RIGHT : 39,
    KEY_DOWN : 40,
    KEY_DELETE : 46,
    KEY_HOME : 36,
    KEY_END : 35,
    KEY_PAGEUP : 33,
    KEY_PAGEDOWN : 34,
    KEY_INSERT : 45,
}

if (!window.hui) {
	var hui = n2i;
}

n2i.browser.opera = /opera/i.test(navigator.userAgent);
n2i.browser.msie = !n2i.browser.opera && /MSIE/.test(navigator.userAgent);
n2i.browser.msie6 = navigator.userAgent.indexOf('MSIE 6')!==-1;
n2i.browser.msie7 = navigator.userAgent.indexOf('MSIE 7')!==-1;
n2i.browser.msie8 = navigator.userAgent.indexOf('MSIE 8')!==-1;
n2i.browser.webkit = navigator.userAgent.indexOf('WebKit')!==-1;
n2i.browser.safari = navigator.userAgent.indexOf('Safari')!==-1;
n2i.browser.webkitVersion = null;
n2i.browser.gecko = !n2i.browser.webkit && navigator.userAgent.indexOf('Gecko')!=-1;

(function() {
	var result = /Safari\/([\d.]+)/.exec(navigator.userAgent);
	if (result) {
		n2i.browser.webkitVersion=parseFloat(result[1]);
	}
})()

/** Log something */
n2i.log = function(obj) {
	try {
		console.log(obj);
	} catch (ignore) {};
}

/** Make a string camelized */
n2i.camelize = function(str) {
	if (str.indexOf('-')==-1) {return str}
    var oStringList = str.split('-');

    var camelizedString = str.indexOf('-') == 0
      ? oStringList[0].charAt(0).toUpperCase() + oStringList[0].substring(1)
      : oStringList[0];

    for (var i = 1, len = oStringList.length; i < len; i++) {
      var s = oStringList[i];
      camelizedString += s.charAt(0).toUpperCase() + s.substring(1);
    }

    return camelizedString;
}

/** Override the properties on the first argument with properties from the last object */
n2i.override = function(original,subject) {
	if (subject) {
		for (prop in subject) {
			original[prop] = subject[prop];
		}
	}
	return original;
}

n2i.wrap = function(str) {
	if (str===null || str===undefined) {
		return '';
	}
	return str.split('').join("\u200B");
}

/** Trim whitespace including unicode chars */
n2i.trim = function(str) {
	if (!str) return str;
	return str.replace(/^[\s\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000]+|[\s\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000]+$/g, '');
}

/** Escape the html in a string */
n2i.escapeHTML = function(str) {
   	return document.createElement('div').appendChild(document.createTextNode(str)).innerHTML;
}

n2i.escape = function(str) {
	if (!n2i.isDefined(str)) {return ''};
	return str.replace(/&/g,'&amp;').
		replace(/>/g,'&gt;').
		replace(/</g,'&lt;').
		replace(/"/g,'&quot;')
}

/** Checks if a string has characters */
n2i.isEmpty = n2i.isBlank = function(str) {
	if (str===null || typeof(str)==='undefined' || str==='') return true;
	return typeof(str)=='string' && n2i.trim(str).length==0;
}

/** Checks that an object is not null or undefined */
n2i.isDefined = function(obj) {
	return obj!==null && typeof(obj)!=='undefined';
}

n2i.isArray = function(obj) {
	if (obj.constructor == Array) {
		return true;
	} else {
		return Object.prototype.toString.call(obj) === '[object Array]';
	}
}

n2i.inArray = function(arr,value) {
	for (var i=0; i < arr.length; i++) {
		if (arr[i]==value) {
			return true;
		}
	};
	return false;
}

n2i.indexInArray = function(arr,value) {
	for (var i=0; i < arr.length; i++) {
		if (arr[i]==value) {
			return i;
		}
	};
	return -1;
}

n2i.each = function(items,func) {
	if (n2i.isArray(items)) {		
		for (var i=0; i < items.length; i++) {
			func(items[i],i);
		};
	} else {
		for (key in items) {
			func(key,items[key]);
		}
	}
}

/**
 * Converts a string to an int if it is only digits, otherwise remains a string
 */
n2i.intOrString = function(str) {
	if (n2i.isDefined(str)) {
		var result = /[0-9]+/.exec(str);
		if (result!==null && result[0]==str) {
			if (parseInt(str,10)==str) {
				return parseInt(str,10);
			}
		}
	}
	return str;
}

n2i.flipInArray = function(arr,value) {
	if (n2i.inArray(arr,value)) {
		n2i.removeFromArray(arr,value);
	} else {
		arr.push(value);
	}
}

n2i.removeFromArray = function(arr,value) {
	for (var i = arr.length - 1; i >= 0; i--){
		if (arr[i]==value) {
			arr.splice(i,1);
		}
	};
}

n2i.addToArray = function(arr,value) {
	if (value.constructor==Array) {
		for (var i=0; i < value.length; i++) {
			if (!n2i.inArray(arr,value[i])) {
				arr.push(value);
			}
		};
	} else {
		if (!n2i.inArray(arr,value)) {
			arr.push(value);
		}
	}
}

n2i.toIntArray = function(str) {
	var array = str.split(',');
	for (var i = array.length - 1; i >= 0; i--){
		array[i] = parseInt(array[i]);
	};
	return array;
}

n2i.scrollTo = function(element) {
	element = $(element);
	if (element) {
		var pos = element.cumulativeOffset();
		window.scrollTo(pos.left, pos.top-50);
	}
}

////////////////////// DOM ////////////////////

/** @namespace */
n2i.dom = {
	isElement : function(n,name) {
		return n.nodeType==n2i.ELEMENT_NODE && (name===undefined ? true : n.nodeName.toLowerCase()==name);
	},
	isDefinedText : function(node) {
		return node.nodeType==n2i.TEXT_NODE && node.nodeValue.length>0;
	},
	addText : function(node,text) {
		node.appendChild(document.createTextNode(text));
	},
	clear : function(node) {
		var children = node.childNodes;
		for (var i = children.length - 1; i >= 0; i--) {
			children[i].parentNode.removeChild(children[i]);
		};
	},
	remove : function(node) {
		if (node.parentNode) {
			node.parentNode.removeChild(node);
		}
	},
	replaceHTML : function(node,html) {
		node = n2i.get(node);
		node.innerHTML=html;
	},
	runScripts : function(node) {
		var scripts = node.getElementsByTagName('script');
		for (var i=0; i < scripts.length; i++) {
			eval(scripts[i].innerHTML);
		}
	},
	setText : function(node,text) {
		var c = node.childNodes;
		var updated = false;
		for (var i=0; i < c.length; i++) {
			if (!updated && node.nodeType==n2i.TEXT_NODE) {
				node.nodeValue=text;
				updated = true;
			} else {
				node.removeChild(c[i]);
			}
		}
		if (!updated) {
			n2i.dom.addText(node,text);
		}
	},
	getText : function(node) {
		var txt = '';
		var c = node.childNodes;
		for (var i=0; i < c.length; i++) {
			if (c[i].nodeType==n2i.TEXT_NODE && c[i].nodeValue!=null) {
				txt+=c[i].nodeValue;
			} else if (c[i].nodeType==n2i.ELEMENT_NODE) {
				txt+=n2i.dom.getNodeText(c[i]);
			}
		};
		return txt;
	},
	isVisible : function(node) {
		while (node) {
			if (node.style && (n2i.getStyle(node,'display')==='none' || n2i.getStyle(node,'visibility')==='hidden')) {
				return false;
			}
			node = node.parentNode;
		}
		return true;
	}
}

n2i.form = {
	getValues : function(node) {
		var params = {};
		var inputs = node.getElementsByTagName('input');
		for (var i=0; i < inputs.length; i++) {
			if (n2i.isDefined(inputs[i].name)) {
				params[inputs[i].name] = inputs[i].value;
			}
		};
		return params;
	}
}

n2i.get = function(str) {
	if (typeof(str)=='string') {
		return document.getElementById(str);
	}
	return str;
}

n2i.getChildren = function(node) {
	var children = [];
	var x = node.childNodes;
	for (var i=0; i < x.length; i++) {
		if (n2i.dom.isElement(x[i])) {
			children.push(x[i]);
		}
	};
	return children;
}

n2i.firstByClass = function(parentElement,className,tag) {
	var children = (n2i.get(parentElement) || document.body).getElementsByTagName(tag || '*');
	for (var i=0;i<children.length;i++) {
		if (n2i.hasClass(children[i],className)) {
			return children[i];
		}
	}
	return null;
}

n2i.byClass = function(parentElement,className,tag) {
	var children = (n2i.get(parentElement) || document.body).getElementsByTagName(tag || '*');
	var out = [];
	for (var i=0;i<children.length;i++) {
		if (n2i.hasClass(children[i],className)) {
			out[out.length]=children[i];
		}
	}
	return out;
}

n2i.firstByTag = function(parentElement,tag) {
	var children = (n2i.get(parentElement) || document.body).getElementsByTagName(tag);
	return children[0];
}

n2i.build = function(tag,options) {
	var e = document.createElement(tag);
	if (options) {
		for (prop in options) {
			if (prop=='text') {
				e.appendChild(document.createTextNode(options.text));
			} else if (prop=='html') {
				e.innerHTML=options.html;
			} else if (prop=='parent') {
				options.parent.appendChild(e);
			} else if (prop=='className') {
				e.className=options.className;
			} else if (prop=='class') {
				e.className=options['class'];
			} else if (n2i.isDefined(options[prop])) {
				e.setAttribute(prop,options[prop]);
			}
		}
	}
	return e;
}

n2i.getAncestors = function(element) {
	var ancestors = [];
	var parent = element.parentNode;
	while (parent) {
		ancestors[ancestors.length] = parent;
		parent = parent.parentNode;
	}
	return ancestors;
}

n2i.getNext = function(element) {
	if (!element) {
		return null;
	}
	if (!element.nextSibling) {
		return null;
	}
	var next = element.nextSibling;
	while (next && next.nodeType!=1) {
		next = next.nextSibling;
	}
    return next;
}

n2i.getAllNext = function(element) {
	var elements = [];
	var next = n2i.getNext(element);
	while (next) {
		elements.push(next);
		next = n2i.getNext(next);
	}
	return elements;
}

n2i.getTop = function(element) {
    element = n2i.get(element);
	if (element) {
		var yPos = element.offsetTop;
		var tempEl = element.offsetParent;
		while (tempEl != null) {
			yPos += tempEl.offsetTop;
			tempEl = tempEl.offsetParent;
		}
		return yPos;
	}
	else return 0;
}

n2i.getLeft = function(element) {
    element = n2i.get(element);
	if (element) {
		var xPos = element.offsetLeft;
		var tempEl = element.offsetParent;
		while (tempEl != null) {
			xPos += tempEl.offsetLeft;
			tempEl = tempEl.offsetParent;
		}
		return xPos;
	}
	else return 0;
}

n2i.getPosition = function(element) {
	return {
		left : n2i.getLeft(element),
		top : n2i.getTop(element)
	}
}

/////////////////////////// Class handling //////////////////////

n2i.hasClass = function(element, className) {
	element = n2i.get(element);
	if (!element || !element.className) {
		return false
	}
	var a = element.className.split(/\s+/);
	for (var i = 0; i < a.length; i++) {
		if (a[i] == className) {
			return true;
		}
	}
	return false;
}

n2i.addClass = function(element, className) {
    element = n2i.get(element);
	if (!element) {return};
	
    n2i.removeClass(element, className);
    element.className += ' ' + className;
}

n2i.removeClass = function(element, className) {
	element = n2i.get(element);
	if (!element) {return};

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

n2i.toggleClass = function(element,className) {
	if (n2i.hasClass(element,className)) {
		n2i.removeClass(element,className);
	} else {
		n2i.addClass(element,className);
	}
}

n2i.setClass = function(element,className,add) {
	if (add) {
		n2i.addClass(element,className);
	} else {
		n2i.removeClass(element,className);
	}
}

n2i.fromJSON = function(json) {
	return JSON.parse(json);
	//return eval('(' + json + ')');
}

n2i.toJSON = function(obj) {
	return JSON.stringify(obj);
}

///////////////////// Events //////////////////

n2i.listen = function(el,type,listener,useCapture) {
	el = n2i.get(el);
	if(document.addEventListener) {
		el.addEventListener(type,listener,useCapture ? true : false);
	} else {
		el.attachEvent('on'+type, listener);
	}
}

n2i.unListen = function(el,type,listener,useCapture) {
	el = n2i.get(el);
	if(document.removeEventListener) {
		el.removeEventListener(type,listener,useCapture ? true : false);
	} else {
		el.detachEvent('on'+type, listener);
	}
}

n2i.event = function(event) {
	return new n2i.Event();
}

n2i.Event = function(event) {
	this.event = event = event || window.event;
	this.element = event.target ? event.target : event.srcElement;
	this.shiftKey = event.shiftKey;
	this.returnKey = event.keyCode==13;
	this.escapeKey = event.keyCode==27;
	this.spaceKey = event.keyCode==32;
	this.upKey = event.keyCode==38;
	this.downKey = event.keyCode==40;
	this.leftKey = event.keyCode==37;
	this.rightKey = event.keyCode==39;
	this.keyCode = event.keyCode;
}

n2i.Event.prototype = {
	left : function() {
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
	getLeft : function() {
		return this.left();
	},
	top : function() {
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
	getTop : function() {
		return this.top();
	},
	getElement : function() {
		return this.element;
	},
	findByClass : function(cls) {
		var parent = this.element;
		while (parent) {
			if (n2i.hasClass(parent,cls)) {
				return parent;
			}
			parent = parent.parentNode;
		}
		return null;
	},
	findByTag : function(tag) {
		var parent = this.element;
		while (parent) {
			if (parent.tagName.toLowerCase()==tag) {
				return parent;
			}
			parent = parent.parentNode;
		}
		return null;
	},

	stop : function() {
		n2i.stop(this.event);
	}
}

n2i.stop = function(e) {
	if (!e) {e = window.event};
	if (e.stopPropagation) {e.stopPropagation()};
	if (e.preventDefault) {e.preventDefault()};
	e.cancelBubble = true;
    e.stopped = true;
}

n2i.onReady = function(delegate) {
	if(window.addEventListener) {
		window.addEventListener('DOMContentLoaded',delegate);
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

// Ajax //

n2i.request = function(options) {
	options = n2i.override({method:'POST',async:true},options);
	var transport = n2i.request.createTransport();
	var self = this;
	transport.onreadystatechange = function() {
		try {
			if (transport.readyState == 4) {
				if (transport.status == 200 && options.onSuccess) {
					options.onSuccess(transport);
				} else if (options.onFailure) {
					options.onFailure(transport);
				}
			}
		} catch (e) {
			if (options.onException) {
				options.onException(e,transport)
			} else {
				throw e;
			}
		}
	};
	var method = options.method.toUpperCase();
	transport.open(method, options.url, options.async);
	var parameters = null;
	var body = '';
    if (method=='POST' && options.parameters) {
		body = n2i.request.buildPostBody(options.parameters);
		transport.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
		//transport.setRequestHeader("Content-length", body.length);
		//transport.setRequestHeader("Connection", "close");
	}
	transport.send(body);
}

n2i.request.buildPostBody = function(parameters) {
	if (!parameters) return null;
	var output = '';
	for (param in parameters) {
		if (output.length>0) output+='&';
		output+=encodeURIComponent(param)+'=';
		if (parameters[param]!==undefined && parameters[param]!==null) {
			output+=encodeURIComponent(parameters[param]);
		}
	}
	return output;
}

n2i.request.createTransport = function() {
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
			return n2i.request.getActiveX();
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

n2i.request.getActiveX = function() {
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

///////////////////// Style ///////////////////

n2i.getStyle = function(element, style) {
	element = n2i.get(element);
	var cameled = n2i.camelize(style);
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
		if (n2i.getStyle(element, 'position') == 'static') value = 'auto';
	}
	return value == 'auto' ? null : value;
}

n2i.setOpacity = function(element,opacity) {
	if (n2i.browser.msie) {
		if (opacity==1) {
			element.style['filter']=null;
		} else {
			element.style['filter']='alpha(opacity='+(opacity*100)+')';
		}
	} else {
		element.style['opacity']=opacity;
	}
}

n2i.setStyle = function(element,styles) {
	for (style in styles) {
		element.style[style] = styles[style];
	}
}

n2i.copyStyle = function(source,target,styles) {
	for (var i=0; i < styles.length; i++) {
		var s = styles[i];
		var r = n2i.getStyle(source,s);
		if (r) {
			target.style[s] = r;
		}
	};
}

//////////////////// Frames ////////////////////

n2i.getFrameDocument = function(frame) {
    if (frame.contentDocument) {
        return frame.contentDocument;
    } else if (frame.contentWindow) {
        return frame.contentWindow.document;
    } else if (frame.document) {
        return frame.document;
    }
}

n2i.getFrameWindow = function(frame) {
    if (frame.defaultView) {
        return frame.defaultView;
    } else if (frame.contentWindow) {
        return frame.contentWindow;
    }
}

/////////////////// Selection /////////////////////

n2i.getSelectedText = function(doc) {
	doc = doc || document;
	if (doc.getSelection) {
		return doc.getSelection()+'';
	} else if (doc.selection) {
		return doc.selection.createRange().text;
	}
	return '';
}

n2i.selection = {
	clear : function() { 
		var sel ; 
		if(document.selection && document.selection.empty	){ 
			document.selection.empty() ; 
		} else if(window.getSelection) { 
			sel=window.getSelection();
			if(sel && sel.removeAllRanges) {
				sel.removeAllRanges() ; 
			}
		}
	},
	getText : function(doc) {
		doc = doc || document;
		if (doc.getSelection) {
			return doc.getSelection()+'';
		} else if (doc.selection) {
			return doc.selection.createRange().text;
		}
		return '';
	}
}

/////////////////// Effects //////////////////////

n2i.effect = {
	makeFlippable : function(options) {
		n2i.addClass(options.container,'in2igui_flip_container');
		n2i.addClass(options.front,'in2igui_flip_front');
		n2i.addClass(options.back,'in2igui_flip_back');
	},
	flip : function(options) {
		var element = n2i.get(options.element);
		var duration = options.duration || '1s';
		var front = n2i.firstByClass(element,'in2igui_flip_front');
		var back = n2i.firstByClass(element,'in2igui_flip_back');
		front.style.webkitTransitionDuration=duration;
		back.style.webkitTransitionDuration=duration;
		n2i.toggleClass(options.element,'in2igui_flip_flipped');
	}
}

/////////////////// Position /////////////////////

n2i.getScrollTop = function() {
	if (self.pageYOffset) {
		return self.pageYOffset;
	} else if (document.documentElement && document.documentElement.scrollTop) {
		return document.documentElement.scrollTop;
	} else if (document.body) {
		return document.body.scrollTop;
	}
}

n2i.getScrollLeft = function() {
	if (self.pageYOffset) {
		return self.pageXOffset;
	} else if (document.documentElement && document.documentElement.scrollTop) {
		return document.documentElement.scrollLeft;
	} else if (document.body) {
		return document.body.scrollLeft;
	}
}

/**
 * Get the height of the viewport
 */
n2i.getViewPortHeight = function() {
	var y;
	if (window.innerHeight) {
		return window.innerHeight;
	} else if (document.documentElement && document.documentElement.clientHeight) {
		return document.documentElement.clientHeight;
	} else if (document.body) {
		return document.body.clientHeight;
	}
}

n2i.getInnerHeight = function() {
	var y;
	if (window.innerHeight) {
		return window.innerHeight;
	} else if (document.documentElement && document.documentElement.clientHeight) {
		return document.documentElement.clientHeight;
	} else if (document.body) {
		return document.body.clientHeight;
	}
}

n2i.getDocumentWidth = n2i.getInnerWidth = function() {
	if (window.innerHeight) {
		return window.innerWidth;
	} else if (document.documentElement && document.documentElement.clientHeight) {
		return document.documentElement.clientWidth;
	} else if (document.body) {
		return document.body.clientWidth;
	}
}

n2i.getDocumentHeight = function() {
	if (n2i.browser.msie6) {
		// In IE6 check the children too
		var max = Math.max(document.body.clientHeight,document.documentElement.clientHeight,document.documentElement.scrollHeight);
		$(document.body).childElements().each(function(node) {
			max = Math.max(max,node.getHeight());
		});
		return max;
	}
	if (window.scrollMaxY && window.innerHeight) {
		return window.scrollMaxY+window.innerHeight;
	} else {
		return Math.max(document.body.clientHeight,document.documentElement.clientHeight,document.documentElement.scrollHeight);
	}
}

//////////////////////////// Placement /////////////////////////

n2i.place = function(options) {
	var left=0,top=0;
	var trgt = options.target.element;
	var trgtPos = {left:n2i.getLeft(trgt),top:n2i.getTop(trgt)};
	left = trgtPos.left+trgt.clientWidth*options.target.horizontal;
	top = trgtPos.top+trgt.clientHeight*options.target.vertical;
	
	var src = options.source.element;
	left-=src.clientWidth*options.source.horizontal;
	top-=src.clientHeight*options.source.vertical;
	
	var src = options.source.element;
	src.style.top=top+'px';
	src.style.left=left+'px';
}

//////////////////////////// Preloader /////////////////////////

/** @constructor */
n2i.Preloader = function(options) {
	this.options = options || {};
	this.delegate = {};
	this.images = [];
	this.loaded = 0;
}

n2i.Preloader.prototype = {
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
	load : function(startIndex) {
		startIndex = startIndex || 0;
		var self = this;
		this.obs = [];
		for (var i=startIndex; i < this.images.length+startIndex; i++) {
			var index=i;
			if (index>=this.images.length) {
				index = index-this.images.length;
			}
			var img = new Image();
			img.n2iPreloaderIndex = index;
			img.onload = function() {self.imageChanged(this.n2iPreloaderIndex,'imageDidLoad')};
			img.onerror = function() {self.imageChanged(this.n2iPreloaderIndex,'imageDidGiveError')};
			img.onabort = function() {self.imageChanged(this.n2iPreloaderIndex,'imageDidAbort')};
			img.src = (this.options.context ? this.options.context : '')+this.images[index];
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

/* @namespace */
n2i.cookie = {
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

///////////////////////// URL/Location /////////////////////

n2i.URL = function(url) {
	this.url = url || '';
}

n2i.URL.prototype = {
	addParameter : function(key,value) {
		this.url+=this.url.indexOf('?')!=-1 ? '&' : '?';
		this.url+=key+'='+(n2i.isDefined(value) ? value : '');
	},
	toString : function() {
		return this.url;
	}
}

/* @namespace */
n2i.location = {
	getParameter : function(name) {
		var parms = n2i.location.getParameters();
		for (var i=0; i < parms.length; i++) {
			if (parms[i].name==name) {
				return parms[i].value;
			}
		};
		return null;
	},
	setParameter : function(name,value) {
		var parms = n2i.location.getParameters();
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
		n2i.location.setParameters(parms);
	},
	hasHash : function(name) {
		var h = document.location.hash;
		if (h!=='') {
			return h=='#'+name;
		}
		return false;
	},
	getHashParameter : function(name) {
		var h = document.location.hash;
		if (h!=='') {
			var i = h.indexOf(name+'=');
			if (i!==-1) {
				var remaining = h.substring(i+name.length+1);
				if (remaining.indexOf('&')!==-1) {
					return remaining.substring(0,remaining.indexOf('&'));
				}
				return remaining;
			}
		}
		return null;
	},
	clearHash : function() {
		document.location.hash='#';
	},
	setParameters : function(parms) {
		var query = '';
		for (var i=0; i < parms.length; i++) {
			query+= i==0 ? '?' : '&';
			query+=parms[i].name+'='+parms[i].value;
		};
		document.location.search=query;
	},
	getBoolean : function(name) {
		var value = n2i.location.getParameter(name);
		return (value=='true' || value=='1');
	},
	getParameters : function() {
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
};

/////////////////////////// Animation ///////////////////////////


n2i.ani = n2i.animate = function(element,style,value,duration,delegate) {
	n2i.animation.get(element).animate(null,value,style,duration,delegate);
}

/** @namespace */
n2i.animation = {
	objects : {},
	running : false,
	latestId : 0,
	get : function(element) {
		element = n2i.get(element);
		if (!element.n2iAnimationId) {
			element.n2iAnimationId = this.latestId++;
		}
		if (!this.objects[element.n2iAnimationId]) {
			this.objects[element.n2iAnimationId] = new n2i.animation.Item(element);
		}
		return this.objects[element.n2iAnimationId];
	},
	start : function() {
		if (!this.running) {
			n2i.animation.render();
		}
	}
};

n2i.animation.render = function(element) {
	this.running = true;
	var next = false;
	var stamp = new Date().getTime();
	for (id in this.objects) {
		var obj = this.objects[id];
		if (obj.work) {
			for (var i=0; i < obj.work.length; i++) {
				var work = obj.work[i];
				if (work.finished) continue;
				var place = (stamp-work.start)/(work.end-work.start);
				if (place<0) {
					next=true;
					continue;
				}
				else if (isNaN(place)) place = 1;
				else if (place>1) place=1;
				else if (place<1) next=true;
				var v = place;
				if (work.delegate && work.delegate.ease) {
					v = work.delegate.ease(v);
				}
				var value = null;
				if (!work.css) {
					obj.element[work.property] = Math.round(work.from+(work.to-work.from)*v);
				} else if (work.property=='transform' && !n2i.browser.msie) {
					var t = work.transform;
					var str = '';
					if (t.rotate) {
						str+=' rotate('+(t.rotate.from+(t.rotate.to-t.rotate.from)*v)+t.rotate.unit+')';
					}
					if (t.scale) {
						str+=' scale('+(t.scale.from+(t.scale.to-t.scale.from)*v)+')';
					}
					obj.element.style[n2i.animation.TRANSFORM]=str;
				} else if (work.to.red!=null) {
					var red = Math.round(work.from.red+(work.to.red-work.from.red)*v);
					var green = Math.round(work.from.green+(work.to.green-work.from.green)*v);
					var blue = Math.round(work.from.blue+(work.to.blue-work.from.blue)*v);
					value = 'rgb('+red+','+green+','+blue+')';
					obj.element.style[work.property]=value;
				} else if (n2i.browser.msie && work.property=='opacity') {
					var opacity = (work.from+(work.to-work.from)*v);
					if (opacity==1) {
						obj.element.style.removeAttribute('filter');
					} else {
						obj.element.style['filter']='alpha(opacity='+(opacity*100)+')';
					}
				} else {
					value = new String(work.from+(work.to-work.from)*v)+(work.unit!=null ? work.unit : '');
					obj.element.style[work.property]=value;
				}
				if (place==1) {
					work.finished = true;
					if (work.delegate && work.delegate.onComplete) {
						window.setTimeout(work.delegate.onComplete);
					} else if (work.delegate && work.delegate.hideOnComplete) {
						obj.element.style.display='none';
					}
				}
			};
		}
	}
	if (next) {
		window.setTimeout(function() {
			n2i.animation.render();
		},0);
	} else {
		this.running = false;
	}
}

n2i.animation.parseStyle = function(value) {
	var parsed = {type:null,value:null,unit:null};
	var match;
	if (!n2i.isDefined(value)) {
		return parsed;
	} else if (!isNaN(value)) {
		parsed.value=parseFloat(value);
	} else if (match=value.match(/([\-]?[0-9\.]+)(px|pt|%)/)) {
		parsed.type = 'length';
		parsed.value = parseFloat(match[1]);
		parsed.unit = match[2];
	} else if (match=value.match(/rgb\(([0-9]+),[ ]?([0-9]+),[ ]?([0-9]+)\)/)) {
		parsed.type = 'color';
		parsed.value = {
			red:parseInt(match[1]),
			green:parseInt(match[2]),
			blue:parseInt(match[3])
		};
	} else {
		var color = new n2i.Color(value);
		if (color.ok) {
			parsed.value = {
				red:color.r,
				green:color.g,
				blue:color.b
			};
		}
	}
	return parsed;
}

///////////////////////////// Item ///////////////////////////////

n2i.animation.Item = function(element) {
	this.element = element;
	this.work = [];
}

n2i.animation.Item.prototype.animate = function(from,to,property,duration,delegate) {
	var css = true;
	if (property=='scrollLeft' || property=='scrollTop') {
		css = false;
	}
	
	var work = this.getWork(css ? n2i.camelize(property) : property);
	work.delegate = delegate;
	work.finished = false;
	work.css = css;
	if (from!=null) {
		work.from = from;
	} else if (property=='transform') {
		work.transform = n2i.animation.Item.parseTransform(to,this.element);
	} else if (work.css && n2i.browser.msie && property=='opacity') {
		work.from = this.getIEOpacity(this.element);
	} else if (work.css) {
		var style = n2i.getStyle(this.element,property);
		var parsedStyle = n2i.animation.parseStyle(style);
		work.from = parsedStyle.value;
	} else {
		work.from = this.element[property];
	}
	if (work.css) {
		var parsed = n2i.animation.parseStyle(to);
		work.to = parsed.value;
		work.unit = parsed.unit;
	} else {
		work.to = to;
		work.unit = null;
	}
	work.start = new Date().getTime();
	if (delegate && delegate.delay) work.start+=delegate.delay;
	work.end = work.start+duration;
	n2i.animation.start();
}

n2i.animation.TRANSFORM = n2i.browser.gecko ? 'MozTransform' : 'WebkitTransform';

n2i.animation.Item.parseTransform = function(value,element) {
	var result = {};
	var rotateReg = /rotate\(([0-9\.]+)([a-z]+)\)/i;
	var rotate = value.match(rotateReg);
	if (rotate) {
		var from = 0;
		if (element.style[n2i.animation.TRANSFORM]) {
			var fromMatch = element.style[n2i.animation.TRANSFORM].match(rotateReg);
			if (fromMatch) {
				from = parseFloat(fromMatch[1]);
			}
		}
		result.rotate = {from:from,to:parseFloat(rotate[1]),unit:rotate[2]};
	}
	var scaleReg = /scale\(([0-9\.]+)\)/i;
	var scale = value.match(scaleReg);
	if (scale) {
		var from = 1;
		if (element.style[n2i.animation.TRANSFORM]) {
			var fromMatch = element.style[n2i.animation.TRANSFORM].match(scaleReg);
			if (fromMatch) {
				from = parseFloat(fromMatch[1]);
			}
		}
		result.scale = {from:from,to:parseFloat(scale[1])};
	}
	
	return result;
}

n2i.animation.Item.prototype.getIEOpacity = function(element) {
	var filter = n2i.getStyle(element,'filter').toLowerCase();
	var match;
	if (match = filter.match(/opacity=([0-9]+)/)) {
		return parseFloat(match[1])/100;
	} else {
		return 1;
	}
}

n2i.animation.Item.prototype.getWork = function(property) {
	for (var i=0; i < this.work.length; i++) {
		if (this.work[i].property==property) {
			return this.work[i];
		}
	};
	var work = {property:property};
	this.work[this.work.length] = work;
	return work;
}

/////////////////////////////// Loop ///////////////////////////////////

n2i.animation.Loop = function(recipe) {
	this.recipe = recipe;
	this.position = -1;
	this.running = false;
}

n2i.animation.Loop.prototype.next = function() {
	this.position++;
	if (this.position>=this.recipe.length) {
		this.position = 0;
	}
	var item = this.recipe[this.position];
	if (typeof(item)=='function') {
		item();
	} else if (item.element) {
		n2i.ani(item.element,item.property,item.value,item.duration,{ease:item.ease});
	}
	var self = this;
	var time = item.duration || 0;
	window.setTimeout(function() {self.next()},time);
}

n2i.animation.Loop.prototype.start = function() {
	this.running=true;
	this.next();
}

/**
	@constructor
*/
n2i.Color = function(color_string) {
    this.ok = false;

    // strip any leading #
    if (color_string.charAt(0) == '#') { // remove # if any
        color_string = color_string.substr(1,6);
    }

    color_string = color_string.replace(/ /g,'');
    color_string = color_string.toLowerCase();
	
	var simple_colors = {
		white : 'ffffff',
		black : '000000',
		red : 'ff0000',
		green : '00ff00',
		blue : '0000ff'
	};
	
    // before getting into regexps, try simple matches
    // and overwrite the input
	/*
    var simple_colors = {
        aliceblue: 'f0f8ff',
        antiquewhite: 'faebd7',
        aqua: '00ffff',
        aquamarine: '7fffd4',
        azure: 'f0ffff',
        beige: 'f5f5dc',
        bisque: 'ffe4c4',
        black: '000000',
        blanchedalmond: 'ffebcd',
        blue: '0000ff',
        blueviolet: '8a2be2',
        brown: 'a52a2a',
        burlywood: 'deb887',
        cadetblue: '5f9ea0',
        chartreuse: '7fff00',
        chocolate: 'd2691e',
        coral: 'ff7f50',
        cornflowerblue: '6495ed',
        cornsilk: 'fff8dc',
        crimson: 'dc143c',
        cyan: '00ffff',
        darkblue: '00008b',
        darkcyan: '008b8b',
        darkgoldenrod: 'b8860b',
        darkgray: 'a9a9a9',
        darkgreen: '006400',
        darkkhaki: 'bdb76b',
        darkmagenta: '8b008b',
        darkolivegreen: '556b2f',
        darkorange: 'ff8c00',
        darkorchid: '9932cc',
        darkred: '8b0000',
        darksalmon: 'e9967a',
        darkseagreen: '8fbc8f',
        darkslateblue: '483d8b',
        darkslategray: '2f4f4f',
        darkturquoise: '00ced1',
        darkviolet: '9400d3',
        deeppink: 'ff1493',
        deepskyblue: '00bfff',
        dimgray: '696969',
        dodgerblue: '1e90ff',
        feldspar: 'd19275',
        firebrick: 'b22222',
        floralwhite: 'fffaf0',
        forestgreen: '228b22',
        fuchsia: 'ff00ff',
        gainsboro: 'dcdcdc',
        ghostwhite: 'f8f8ff',
        gold: 'ffd700',
        goldenrod: 'daa520',
        gray: '808080',
        green: '008000',
        greenyellow: 'adff2f',
        honeydew: 'f0fff0',
        hotpink: 'ff69b4',
        indianred : 'cd5c5c',
        indigo : '4b0082',
        ivory: 'fffff0',
        khaki: 'f0e68c',
        lavender: 'e6e6fa',
        lavenderblush: 'fff0f5',
        lawngreen: '7cfc00',
        lemonchiffon: 'fffacd',
        lightblue: 'add8e6',
        lightcoral: 'f08080',
        lightcyan: 'e0ffff',
        lightgoldenrodyellow: 'fafad2',
        lightgrey: 'd3d3d3',
        lightgreen: '90ee90',
        lightpink: 'ffb6c1',
        lightsalmon: 'ffa07a',
        lightseagreen: '20b2aa',
        lightskyblue: '87cefa',
        lightslateblue: '8470ff',
        lightslategray: '778899',
        lightsteelblue: 'b0c4de',
        lightyellow: 'ffffe0',
        lime: '00ff00',
        limegreen: '32cd32',
        linen: 'faf0e6',
        magenta: 'ff00ff',
        maroon: '800000',
        mediumaquamarine: '66cdaa',
        mediumblue: '0000cd',
        mediumorchid: 'ba55d3',
        mediumpurple: '9370d8',
        mediumseagreen: '3cb371',
        mediumslateblue: '7b68ee',
        mediumspringgreen: '00fa9a',
        mediumturquoise: '48d1cc',
        mediumvioletred: 'c71585',
        midnightblue: '191970',
        mintcream: 'f5fffa',
        mistyrose: 'ffe4e1',
        moccasin: 'ffe4b5',
        navajowhite: 'ffdead',
        navy: '000080',
        oldlace: 'fdf5e6',
        olive: '808000',
        olivedrab: '6b8e23',
        orange: 'ffa500',
        orangered: 'ff4500',
        orchid: 'da70d6',
        palegoldenrod: 'eee8aa',
        palegreen: '98fb98',
        paleturquoise: 'afeeee',
        palevioletred: 'd87093',
        papayawhip: 'ffefd5',
        peachpuff: 'ffdab9',
        peru: 'cd853f',
        pink: 'ffc0cb',
        plum: 'dda0dd',
        powderblue: 'b0e0e6',
        purple: '800080',
        red: 'ff0000',
        rosybrown: 'bc8f8f',
        royalblue: '4169e1',
        saddlebrown: '8b4513',
        salmon: 'fa8072',
        sandybrown: 'f4a460',
        seagreen: '2e8b57',
        seashell: 'fff5ee',
        sienna: 'a0522d',
        silver: 'c0c0c0',
        skyblue: '87ceeb',
        slateblue: '6a5acd',
        slategray: '708090',
        snow: 'fffafa',
        springgreen: '00ff7f',
        steelblue: '4682b4',
        tan: 'd2b48c',
        teal: '008080',
        thistle: 'd8bfd8',
        tomato: 'ff6347',
        turquoise: '40e0d0',
        violet: 'ee82ee',
        violetred: 'd02090',
        wheat: 'f5deb3',
        white: 'ffffff',
        whitesmoke: 'f5f5f5',
        yellow: 'ffff00',
        yellowgreen: '9acd32'
    };*/
    for (var key in simple_colors) {
        if (color_string == key) {
            color_string = simple_colors[key];
        }
    }
    // emd of simple type-in colors

    // array of color definition objects
    var color_defs = [
        {
            re: /^rgb\((\d{1,3}),\s*(\d{1,3}),\s*(\d{1,3})\)$/,
            example: ['rgb(123, 234, 45)', 'rgb(255,234,245)'],
            process: function (bits){
                return [
                    parseInt(bits[1]),
                    parseInt(bits[2]),
                    parseInt(bits[3])
                ];
            }
        },
        {
            re: /^(\w{2})(\w{2})(\w{2})$/,
            example: ['#00ff00', '336699'],
            process: function (bits){
                return [
                    parseInt(bits[1], 16),
                    parseInt(bits[2], 16),
                    parseInt(bits[3], 16)
                ];
            }
        },
        {
            re: /^(\w{1})(\w{1})(\w{1})$/,
            example: ['#fb0', 'f0f'],
            process: function (bits){
                return [
                    parseInt(bits[1] + bits[1], 16),
                    parseInt(bits[2] + bits[2], 16),
                    parseInt(bits[3] + bits[3], 16)
                ];
            }
        }
    ];

    // search through the definitions to find a match
    for (var i = 0; i < color_defs.length; i++) {
        var re = color_defs[i].re;
        var processor = color_defs[i].process;
        var bits = re.exec(color_string);
        if (bits) {
            channels = processor(bits);
            this.r = channels[0];
            this.g = channels[1];
            this.b = channels[2];
            this.ok = true;
        }

    }

    // validate/cleanup values
    this.r = (this.r < 0 || isNaN(this.r)) ? 0 : ((this.r > 255) ? 255 : this.r);
    this.g = (this.g < 0 || isNaN(this.g)) ? 0 : ((this.g > 255) ? 255 : this.g);
    this.b = (this.b < 0 || isNaN(this.b)) ? 0 : ((this.b > 255) ? 255 : this.b);

    // some getters
    this.toRGB = function () {
        return 'rgb(' + this.r + ', ' + this.g + ', ' + this.b + ')';
    }
    this.toHex = function () {
        var r = this.r.toString(16);
        var g = this.g.toString(16);
        var b = this.b.toString(16);
        if (r.length == 1) r = '0' + r;
        if (g.length == 1) g = '0' + g;
        if (b.length == 1) b = '0' + b;
        return '#' + r + g + b;
    }

}


/** @namespace */
n2i.ease = {
	slowFastSlow : function(val) {
		var a = 1.6;
		var b = 1.4;
		return -1*Math.pow(Math.cos((Math.PI/2)*Math.pow(val,a)),Math.pow(Math.PI,b))+1;
	},
	fastSlow : function(val) {
		var a = .5;
		var b = .7
		return -1*Math.pow(Math.cos((Math.PI/2)*Math.pow(val,a)),Math.pow(Math.PI,b))+1;
	},
	elastic : function(t) {
		return 1 - n2i.ease.elastic2(1-t);
	},

	elastic2 : function (t, a, p) {
		if (t<=0 || t>=1) return t;
		if (!p) p=0.45;
		var s;
		if (!a || a < 1) {
			a=1;
			s=p/4;
		} else {
			s = p/(2*Math.PI) * Math.asin (1/a);
		}
		return -(a*Math.pow(2,10*(t-=1)) * Math.sin( (t-s)*(2*Math.PI)/p ));
	},
	bounce : function(t) {
		if (t < (1/2.75)) {
			return 7.5625*t*t;
		} else if (t < (2/2.75)) {
			return (7.5625*(t-=(1.5/2.75))*t + .75);
		} else if (t < (2.5/2.75)) {
			return (7.5625*(t-=(2.25/2.75))*t + .9375);
		} else {
			return (7.5625*(t-=(2.625/2.75))*t + .984375);
		}
	},
	
	linear: function(/* Decimal? */n){
		// summary: A linear easing function
		return n;
	},

	quadIn: function(/* Decimal? */n){
		return Math.pow(n, 2);
	},

	quadOut: function(/* Decimal? */n){
		return n * (n-2) * -1;
	},

	quadInOut: function(/* Decimal? */n){
		n=n*2;
		if(n<1){ return Math.pow(n, 2) / 2; }
		return -1 * ((--n)*(n-2) - 1) / 2;
	},

	cubicIn: function(/* Decimal? */n){
		return Math.pow(n, 3);
	},

	cubicOut: function(/* Decimal? */n){
		return Math.pow(n-1, 3) + 1;
	},

	cubicInOut: function(/* Decimal? */n){
		n=n*2;
		if(n<1){ return Math.pow(n, 3) / 2; }
		n-=2;
		return (Math.pow(n, 3) + 2) / 2;
	},

	quartIn: function(/* Decimal? */n){
		return Math.pow(n, 4);
	},

	quartOut: function(/* Decimal? */n){
		return -1 * (Math.pow(n-1, 4) - 1);
	},

	quartInOut: function(/* Decimal? */n){
		n=n*2;
		if(n<1){ return Math.pow(n, 4) / 2; }
		n-=2;
		return -1/2 * (Math.pow(n, 4) - 2);
	},

	quintIn: function(/* Decimal? */n){
		return Math.pow(n, 5);
	},

	quintOut: function(/* Decimal? */n){
		return Math.pow(n-1, 5) + 1;
	},

	quintInOut: function(/* Decimal? */n){
		n=n*2;
		if(n<1){ return Math.pow(n, 5) / 2; };
		n-=2;
		return (Math.pow(n, 5) + 2) / 2;
	},

	sineIn: function(/* Decimal? */n){
		return -1 * Math.cos(n * (Math.PI/2)) + 1;
	},

	sineOut: function(/* Decimal? */n){
		return Math.sin(n * (Math.PI/2));
	},

	sineInOut: function(/* Decimal? */n){
		return -1 * (Math.cos(Math.PI*n) - 1) / 2;
	},

	expoIn: function(/* Decimal? */n){
		return (n==0) ? 0 : Math.pow(2, 10 * (n - 1));
	},

	expoOut: function(/* Decimal? */n){
		return (n==1) ? 1 : (-1 * Math.pow(2, -10 * n) + 1);
	},

	expoInOut: function(/* Decimal? */n){
		if(n==0){ return 0; }
		if(n==1){ return 1; }
		n = n*2;
		if(n<1){ return Math.pow(2, 10 * (n-1)) / 2; }
		--n;
		return (-1 * Math.pow(2, -10 * n) + 2) / 2;
	},

	circIn: function(/* Decimal? */n){
		return -1 * (Math.sqrt(1 - Math.pow(n, 2)) - 1);
	},

	circOut: function(/* Decimal? */n){
		n = n-1;
		return Math.sqrt(1 - Math.pow(n, 2));
	},

	circInOut: function(/* Decimal? */n){
		n = n*2;
		if(n<1){ return -1/2 * (Math.sqrt(1 - Math.pow(n, 2)) - 1); }
		n-=2;
		return 1/2 * (Math.sqrt(1 - Math.pow(n, 2)) + 1);
	},

	backIn: function(/* Decimal? */n){
		var s = 1.70158;
		return Math.pow(n, 2) * ((s+1)*n - s);
	},

	backOut: function(/* Decimal? */n){
		// summary: an easing function that pops past the range briefly, and 
		// 	slowly comes back. 
		n = n - 1;
		var s = 1.70158;
		return Math.pow(n, 2) * ((s + 1) * n + s) + 1;
	},

	backInOut: function(/* Decimal? */n){
		var s = 1.70158 * 1.525;
		n = n*2;
		if(n < 1){ return (Math.pow(n, 2)*((s+1)*n - s))/2; }
		n-=2;
		return (Math.pow(n, 2)*((s+1)*n + s) + 2)/2;
	},

	elasticIn: function(/* Decimal? */n){
		if(n==0){ return 0; }
		if(n==1){ return 1; }
		var p = .3;
		var s = p/4;
		n = n - 1;
		return -1 * Math.pow(2,10*n) * Math.sin((n-s)*(2*Math.PI)/p);
	},

	elasticOut: function(/* Decimal? */n){
		// summary: An easing function that elasticly snaps around the target value, near the end of the Animation
		if(n==0) return 0;
		if(n==1) return 1;
		var p = .3;
		var s = p/4;
		return Math.pow(2,-10*n) * Math.sin((n-s)*(2*Math.PI)/p) + 1;
	},

	elasticInOut: function(/* Decimal? */n){
		// summary: An easing function that elasticly snaps around the value, near the beginning and end of the Animation		
		if(n==0) return 0;
		n = n*2;
		if(n==2) return 1;
		var p = .3*1.5;
		var s = p/4;
		if(n<1){
			n-=1;
			return -.5*(Math.pow(2,10*n) * Math.sin((n-s)*(2*Math.PI)/p));
		}
		n-=1;
		return .5*(Math.pow(2,-10*n) * Math.sin((n-s)*(2*Math.PI)/p)) + 1;
	},

	bounceIn: function(/* Decimal? */n){
		// summary: An easing function that "bounces" near the beginning of an Animation
		return (1 - n2i.ease.bounceOut(1-n)); // Decimal
	},

	bounceOut: function(/* Decimal? */n){
		// summary: An easing function that "bounces" near the end of an Animation
		var s=7.5625;
		var p=2.75;
		var l; 
		if(n < (1 / p)){
			l = s*Math.pow(n, 2);
		}else if(n < (2 / p)){
			n -= (1.5 / p);
			l = s * Math.pow(n, 2) + .75;
		}else if(n < (2.5 / p)){
			n -= (2.25 / p);
			l = s * Math.pow(n, 2) + .9375;
		}else{
			n -= (2.625 / p);
			l = s * Math.pow(n, 2) + .984375;
		}
		return l;
	},

	bounceInOut: function(/* Decimal? */n){
		// summary: An easing function that "bounces" at the beginning and end of the Animation
		if(n<0.5){ return n2i.ease.bounceIn(n*2) / 2; }
		return (n2i.ease.bounceOut(n*2-1) / 2) + 0.5; // Decimal
	}
};

if (!Function.prototype.bind) {
	Function.prototype.bind = function () {
	    if (arguments.length < 2 && arguments[0] === undefined) {
	        return this;
	    }
	    var thisObj = this,
	    args = Array.prototype.slice.call(arguments),
	    obj = args.shift();
	    return function () {
	        return thisObj.apply(obj, args.concat(Array.prototype.slice.call(arguments)));
	    };
	};

	Function.bind = function() {
	    var args = Array.prototype.slice.call(arguments);
	    return Function.prototype.bind.apply(args.shift(), args);
	}
}var in2igui = {
	locales : {
		'da/DK':{decimal:',',thousands:'.'},
		'en/US':{decimal:'.',thousands:','}
	},
	locale : {decimal:',',thousands:'.'},
	setLocale : function(code) {
		if (this.locales[code]) {
			this.locale = this.locales[code];
		}
	}
};

/**
  The base class of the In2iGui framework
  @constructor
 */
function In2iGui() {
	/** {boolean} Is true when the DOM is loaded */
	this.domLoaded = false;
	/** @private */
	this.overflows = null;
	/** @private */
	this.delegates = [];
	/** @private */
	this.objects = {};
	/** @private */
	this.layoutWidgets = [];
	/** @private */
	this.state = 'default';
	this.addBehavior();
}

window.ui = In2iGui;

/** @private */
In2iGui.latestObjectIndex = 0;
/** @private */
In2iGui.latestIndex = 500;
/** @private */
In2iGui.latestPanelIndex = 1000;
/** @private */
In2iGui.latestAlertIndex = 1500;
/** @private */
In2iGui.latestTopIndex = 2000;
/** @private */
In2iGui.toolTips = {};

/** Gets the one instance of In2iGui */
In2iGui.get = function(nameOrWidget) {
	if (!In2iGui.instance) {
		In2iGui.instance = new In2iGui();
	}
	if (nameOrWidget) {
		if (nameOrWidget.element) {
			return nameOrWidget;
		}
		return In2iGui.instance.objects[nameOrWidget];
	} else {
		return In2iGui.instance;
	}
};

//document.observe('dom:loaded', function () {
//	In2iGui.get().ignite();
//});

n2i.onReady(function() {
	In2iGui.get().ignite();
});

In2iGui.prototype = {
	/** @private */
	ignite : function() {
		if (window.dwr) {
			if (dwr && dwr.engine && dwr.engine.setErrorHandler) {
				dwr.engine.setErrorHandler(function(msg,e) {
					n2i.log(msg);
					n2i.log(e);
					In2iGui.get().alert({title:'An unexpected error occurred!',text:msg,emotion:'gasp'});
				});
			}
		}
		this.domLoaded = true;
		In2iGui.domReady = true;
		this.resize();
		//In2iGui.callSuperDelegates(this,'interfaceIsReady');
		In2iGui.callSuperDelegates(this,'ready');

		this.reLayout();
		n2i.listen(window,'resize',this.reLayout.bind(this));
	},
	/** @private */
	addBehavior : function() {
		n2i.listen(window,'resize',this.resize.bind(this));
	},
	/** Adds a global delegate
	 * @deprecated
	*/
	listen : function(delegate) {
		this.delegates.push(delegate);
	},
	getTopPad : function(element) {
		var all,top;
		all = parseInt(n2i.getStyle(element,'padding'),10);
		top = parseInt(n2i.getStyle(element,'padding-top'),10);
		if (all) {return all;}
		if (top) {return top;}
		return 0;
	},
	getBottomPad : function(element) {
		var all,bottom;
		all = parseInt(n2i.getStyle(element,'padding'),10);
		bottom = parseInt(n2i.getStyle(element,'padding-bottom'),10);
		if (all) {return all;}
		if (bottom) {return bottom;}
		return 0;
	},
	/** @private */
	resize : function() {
		if (!this.overflows) {return;}
		var height = n2i.getInnerHeight();
		for (var i=0; i < this.overflows.length; i++) {
			var overflow = this.overflows[i];
			if (n2i.browser.webkit || n2i.browser.gecko) {
				overflow.element.style.display='none';
				overflow.element.style.width = overflow.element.parentNode.clientWidth+'px';
				overflow.element.style.display='';
			}
			overflow.element.style.height = height+overflow.diff+'px';
		};
	},
	registerOverflow : function(id,diff) {
		if (!this.overflows) {this.overflows=[];}
		var overflow = n2i.get(id);
		this.overflows.push({element:overflow,diff:diff});
	},
	/** @private */
	alert : function(options) {
		if (!this.alertBox) {
			this.alertBox = In2iGui.Alert.create(options);
			this.alertBoxButton = In2iGui.Button.create({name:'in2iGuiAlertBoxButton',text : 'OK'});
			this.alertBoxButton.listen(this);
			this.alertBox.addButton(this.alertBoxButton);
		} else {
			this.alertBox.update(options);
		}
		this.alertBoxCallBack = options.onOK;
		this.alertBoxButton.setText(options.button ? options.button : 'OK');
		this.alertBox.show();
	},
	/** @private */
	$click$in2iGuiAlertBoxButton : function() {
		In2iGui.get().alertBox.hide();
		if (this.alertBoxCallBack) {
			this.alertBoxCallBack();
			this.alertBoxCallBack = null;
		}
	},
	confirm : function(options) {
		if (!options.name) {
			options.name = 'in2iguiConfirm';
		}
		var alert = In2iGui.get(options.name);
		var ok;
		if (!alert) {
			alert = In2iGui.Alert.create(options);
			var cancel = In2iGui.Button.create({name:name+'_cancel',text : options.cancel || 'Cancel',highlighted:options.highlighted==='cancel'});
			cancel.listen({$click:function(){
				alert.hide();
				if (options.onCancel) {
					options.onCancel();
				}
				In2iGui.callDelegates(alert,'cancel');
			}});
			alert.addButton(cancel);
		
			ok = In2iGui.Button.create({name:name+'_ok',text : options.ok || 'OK',highlighted:options.highlighted==='ok'});
			alert.addButton(ok);
		} else {
			alert.update(options);
			ok = In2iGui.get(name+'_ok');
			ok.setText(options.ok || 'OK');
			ok.setHighlighted(options.highlighted=='ok');
			ok.clearDelegates();
			In2iGui.get(name+'_cancel').setText(options.ok || 'Cancel');
			In2iGui.get(name+'_cancel').setHighlighted(options.highlighted=='cancel');
			if (options.cancel) {In2iGui.get(name+'_cancel').setText(options.cancel);}
		}
		ok.listen({$click:function(){
			alert.hide();
			if (options.onOK) {
				options.onOK();
			}
			In2iGui.callDelegates(alert,'ok');
		}});
		alert.show();
	},
	reLayout : function() {
		for (var i = this.layoutWidgets.length - 1; i >= 0; i--){
			this.layoutWidgets[i]['$$layout']();
		};
	},
	getDescendants : function(widgetOrElement) {
		var desc = [],e = widgetOrElement.getElement ? widgetOrElement.getElement() : widgetOrElement;
		if (e) {
			var d = e.getElementsByTagName('*');
			var o = [];
			for (key in this.objects) {
				o.push(this.objects[key]);
			}
			for (var i=0; i < d.length; i++) {
				for (var j=0; j < o.length; j++) {
					if (d[i]==o[j].element) {
						desc.push(o[j]);
					}
				};
				
			};
		}
		return desc;
	},
	/** Gets all ancestors of a widget
		@param {Widget} A widget
		@returns {Array} An array of all ancestors
	*/
	getAncestors : function(widget) {
		var desc = [];
		var e = widget.element;
		if (e) {
			var a = n2i.getAncestors(e);
			var o = [];
			for (key in this.objects) {
				o.push(this.objects[key]);
			}
			for (var i=0; i < a.length; i++) {
				for (var j=0; j < o.length; j++) {
					if (o[j].element==a[i]) {
						desc.push(o[j]);
					}
					
				};
			};
		}
		return desc;
	},
	getAncestor : function(widget,cls) {
		var a = this.getAncestors(widget);
		for (var i=0; i < a.length; i++) {
			if (n2i.hasClass(a[i].getElement(),cls)) {
				return a[i];
			}
		};
		return null;
	}
};

In2iGui.confirmOverlays = {};

In2iGui.confirmOverlay = function(options) {
	var node = options.element || options.widget.getElement();
	if (In2iGui.confirmOverlays[node]) {
		var overlay = In2iGui.confirmOverlays[node];
		overlay.clear();
	} else {
		var overlay = ui.Overlay.create({modal:true});
		In2iGui.confirmOverlays[node] = overlay;
	}
	if (options.text) {
		overlay.addText(options.text);
	}
	var ok = ui.Button.create({text:options.okText || 'OK',highlighted:'true'});
	ok.click(function() {
		if (options.onOk) {
			options.onOk();
		}
		overlay.hide();
	});
	overlay.add(ok);
	var cancel = ui.Button.create({text:options.cancelText || 'Cancel'});
	cancel.onClick(function() {
		overlay.hide();
	});
	overlay.add(cancel);
	overlay.show({element:node});
}

In2iGui.destroyDescendants = function(element) {
	var desc = In2iGui.get().getDescendants(element);
	var objects = In2iGui.get().objects;
	for (var i=0; i < desc.length; i++) {
		var obj  = delete(objects[desc[i].name]);
		if (!obj) {
			n2i.log('not found: '+desc[i].name);
		}
	};
}

In2iGui.changeState = function(state) {
	if (In2iGui.get().state===state) {return;}
	var all = In2iGui.get().objects;
	for (key in all) {
		var obj = all[key];
		if (obj.options && obj.options.state) {
			if (obj.options.state==state) {
				obj.show();
			} else {
				obj.hide();
			}
		}
	}
	In2iGui.get().state==state;
}

///////////////////////////////// Indexes /////////////////////////////

In2iGui.nextIndex = function() {
	In2iGui.latestIndex++;
	return 	In2iGui.latestIndex;
};

In2iGui.nextPanelIndex = function() {
	In2iGui.latestPanelIndex++;
	return 	In2iGui.latestPanelIndex;
};

In2iGui.nextAlertIndex = function() {
	In2iGui.latestAlertIndex++;
	return 	In2iGui.latestAlertIndex;
};

In2iGui.nextTopIndex = function() {
	In2iGui.latestTopIndex++;
	return 	In2iGui.latestTopIndex;
};

///////////////////////////////// Curtain /////////////////////////////

In2iGui.showCurtain = function(options,zIndex) {
	var widget = options.widget;
	if (!widget.curtain) {
		widget.curtain = n2i.build('div',{'class':'in2igui_curtain',style:'z-index:none'});
		widget.curtain.onclick = function() {
			if (widget['$curtainWasClicked']) {
				widget['$curtainWasClicked']();
			}
		};
		var body = n2i.firstByClass(document.body,'in2igui_body');
		if (!body) {
			body=document.body;
		}
		body.appendChild(widget.curtain);
	}
	if (options.color) {
		widget.curtain.style.backgroundColor=options.color;
	}
	if (n2i.browser.msie) {
		widget.curtain.style.height=n2i.getDocumentHeight()+'px';
	} else {
		widget.curtain.style.position='fixed';
		widget.curtain.style.top='0';
		widget.curtain.style.left='0';
		widget.curtain.style.bottom='0';
		widget.curtain.style.right='0';
	}
	widget.curtain.style.zIndex=options.zIndex;
	n2i.setOpacity(widget.curtain,0);
	widget.curtain.style.display='block';
	n2i.ani(widget.curtain,'opacity',0.7,1000,{ease:n2i.ease.slowFastSlow});
}

In2iGui.hideCurtain = function(widget) {
	if (widget.curtain) {
		n2i.ani(widget.curtain,'opacity',0,200,{hideOnComplete:true});
	}
};

//////////////////////////////// Message //////////////////////////////

In2iGui.alert = function(o) {
	In2iGui.get().alert(o);
};

In2iGui.showMessage = function(options) {
	if (typeof(options)=='string') {
		// TODO: Backwards compatibility
		options={text:options};
	}
	if (!In2iGui.message) {
		In2iGui.message = n2i.build('div',{'class':'in2igui_message',html:'<div><div></div></div>'});
		if (!n2i.browser.msie) {
			n2i.setOpacity(In2iGui.message,0);
		}
		document.body.appendChild(In2iGui.message);
	}
	In2iGui.message.getElementsByTagName('div')[1].innerHTML=options.text;
	In2iGui.message.style.display='block';
	In2iGui.message.style.zIndex=In2iGui.nextTopIndex();
	In2iGui.message.style.marginLeft=(In2iGui.message.clientWidth/-2)+'px';
	In2iGui.message.style.marginTop=n2i.getScrollTop()+'px';
	if (!n2i.browser.msie) {
		n2i.ani(In2iGui.message,'opacity',1,300);
	}
	window.clearTimeout(In2iGui.messageTimer);
	if (options.duration) {
		In2iGui.messageTimer = window.setTimeout(In2iGui.hideMessage,options.duration);
	}
};

In2iGui.hideMessage = function() {
	if (In2iGui.message) {
		if (!n2i.browser.msie) {
			n2i.ani(In2iGui.message,'opacity',0,300,{hideOnComplete:true});
		} else {
			In2iGui.message.setStyle({display:'none'});
		}
	}
};

In2iGui.showToolTip = function(options) {
	var key = options.key || 'common';
	var t = In2iGui.toolTips[key];
	if (!t) {
		t = n2i.build('div',{'class':'in2igui_tooltip',style:'display:none;',html:'<div><div></div></div>',parent:document.body});
		In2iGui.toolTips[key] = t;
	}
	t.onclick = function() {In2iGui.hideToolTip(options);};
	var n = $(options.element);
	var pos = n.cumulativeOffset();
	t.select('div')[1].update(options.text);
	if (t.style.display=='none' && !n2i.browser.msie) {t.setStyle({opacity:0});}
	t.setStyle({'display':'block',zIndex:In2iGui.nextTopIndex()});
	t.setStyle({left:(pos.left-t.getWidth()+4)+'px',top:(pos.top+2-(t.getHeight()/2)+(n.getHeight()/2))+'px'});
	if (!n2i.browser.msie) {
		n2i.ani(t,'opacity',1,300);
	}
};

In2iGui.hideToolTip = function(options) {
	var key = options ? options.key || 'common' : 'common';
	var t = In2iGui.toolTips[key];
	if (t) {
		if (!n2i.browser.msie) {
			n2i.ani(t,'opacity',0,300,{hideOnComplete:true});
		} else {
			t.setStyle({display:'none'});
		}
	}
};

/////////////////////////////// Utilities /////////////////////////////

In2iGui.getElement = function(widgetOrElement) {
	if (n2i.dom.isElement(widgetOrElement)) {
		return widgetOrElement;
	} else if (widgetOrElement.getElement) {
		return widgetOrElement.getElement();
	}
	return null;
}

In2iGui.isWithin = function(e,element) {
	e = new n2i.Event(e);
	var offset = {left:n2i.getLeft(element),top:n2i.getTop(element)};
	var dims = {width:element.clientWidth,height:element.clientHeight};
	return e.left()>offset.left && e.left()<offset.left+dims.width && e.top()>offset.top && e.top()<offset.top+dims.height;
};

In2iGui.getIconUrl = function(icon,size) {
	return In2iGui.context+'/In2iGui/icons/'+icon+size+'.png';
};

In2iGui.createIcon = function(icon,size) {
	return n2i.build('span',{'class':'in2igui_icon in2igui_icon_'+size,style:'background-image: url('+In2iGui.getIconUrl(icon,size)+')'});
};

In2iGui.onDomReady = In2iGui.onReady = function(func) {
	if (In2iGui.domReady) {return func();}
	if (n2i.browser.gecko && document.baseURI.endsWith('xml')) {
		window.setTimeout(func,1000);
		return;
	}
	n2i.onReady(func);
	//document.observe('dom:loaded', func);
};

In2iGui.wrapInField = function(e) {
	var w = n2i.build('div',{'class':'in2igui_field',html:
		'<span class="in2igui_field_top"><span><span></span></span></span>'+
		'<span class="in2igui_field_middle"><span class="in2igui_field_middle"><span class="in2igui_field_content"></span></span></span>'+
		'<span class="in2igui_field_bottom"><span><span></span></span></span>'
	});
	n2i.firstByClass(w,'in2igui_field_content').appendChild(e);
	return w;
};

In2iGui.addFocusClass = function(o) {
	var ce = o.classElement || o.element, c = o['class'];
	n2i.listen(o.element,'focus',function() {
		n2i.addClass(ce,c);
	});
	n2i.listen(o.element,'blur',function() {
		n2i.removeClass(ce,c);
	});
};


/////////////////////////////// Validation /////////////////////////////

In2iGui.NumberValidator = function(options) {
	n2i.override({allowNull:false,min:0,max:10},options)
	this.min = options.min;
	this.max = options.max;
	this.allowNull = options.allowNull;
	this.middle = Math.max(Math.min(this.max,0),this.min);
}

In2iGui.NumberValidator.prototype = {
	validate : function(value) {
		if (n2i.isBlank(value) && this.allowNull) {
			return {valid:true,value:null};
		}
		var number = parseFloat(value);
		if (isNaN(number)) {
			return {valid:false,value:this.middle};
		} else if (number<this.min) {
			return {valid:false,value:this.min};
		} else if (number>this.max) {
			return {valid:false,value:this.max};
		}
		return {valid:true,value:number};
	}
}

/////////////////////////////// Animation /////////////////////////////

In2iGui.fadeIn = function(node,time) {
	if (n2i.getStyle(node,'display')=='none') {
		n2i.setStyle(node,{opacity:0,display:''});
	}
	n2i.animate(node,'opacity',1,time);
};

In2iGui.fadeOut = function(node,time) {
	hui.animate(node,'opacity',0,time,{hideOnComplete:true});
};

In2iGui.bounceIn = function(node,time) {
	if (n2i.browser.msie) {
		n2i.setStyle(node,{'display':'block',visibility:'visible'});
	} else {
		n2i.setStyle(node,{'display':'block','opacity':0,visibility:'visible'});
		n2i.animate(node,'transform','scale(0.1)',0);// rotate(10deg)
		window.setTimeout(function() {
			n2i.animate(node,'opacity',1,300);
			n2i.animate(node,'transform','scale(1)',400,{ease:n2i.ease.backOut}); // rotate(0deg)
		});
	}
};

//////////////////////////// Positioning /////////////////////////////

In2iGui.positionAtElement = function(element,target,options) {
	options = options || {};
	element = n2i.get(element);
	target = n2i.get(target);
	var origDisplay = n2i.getStyle(element,'display');
	if (origDisplay=='none') {
		n2i.setStyle(element,{'visibility':'hidden','display':'block'});
	}
	var pos = left = n2i.getLeft(target),top = n2i.getTop(target);
	var vert=options.vertical || null;
	if (options.horizontal && options.horizontal=='right') {
		left = left+target.clientWidth-element.clientWidth;
	}
	if (vert=='topOutside') {
		top = top-element.clientHeight;
	} else if (vert=='bottomOutside') {
		top = top+target.clientHeight;
	}
	left+=(options.left || 0);
	top+=(options.top || 0);
	n2i.setStyle(element,{'left':left+'px','top':top+'px'});
	if (origDisplay=='none') {
		n2i.setStyle(element,{'visibility':'visible','display':'none'});
	}
};

In2iGui.getTextAreaHeight = function(input) {
	var t = this.textAreaDummy;
	if (!t) {
		var t = this.textAreaDummy = document.createElement('div');
		t.className='in2igui_textarea_dummy';
		document.body.appendChild(t);
	}
	var html = input.value;
	if (html[html.length-1]==='\n') {
		html+='x';
	}
	html = n2i.escape(html).replace(/\n/g,'<br/>');
	t.innerHTML = html;
	t.style.width=(input.clientWidth)+'px';
	return t.clientHeight;
}

//////////////////// Delegating ////////////////////

In2iGui.extend = function(obj,options) {
	if (!obj.name) {
		In2iGui.latestObjectIndex++;
		obj.name = 'unnamed'+In2iGui.latestObjectIndex;
	}
	if (options!==undefined) {
		if (obj.options) {
			obj.options = n2i.override(obj.options,options);
		}
		obj.element = n2i.get(options.element);
		obj.name = options.name;
	}
	var ctrl = In2iGui.get();
	ctrl.objects[obj.name] = obj;
	obj.delegates = [];
	obj.listen = function(delegate) {
		n2i.addToArray(this.delegates,delegate);
	}
	obj.removeDelegate = function(delegate) {
		n2i.removeFromArray(this.delegates,delegate);
	}
	obj.clearDelegates = function() {
		this.delegates = [];
	}
	obj.fire = function(method,value,event) {
		In2iGui.callDelegates(this,method,value,event);
	}
	obj.fireProperty = function(key,value) {
		In2iGui.firePropertyChange(this,key,value);
	}
	if (!obj.getElement) {
		obj.getElement = function() {
			return this.element;
		}
	}
	if (!obj.valueForProperty) {
		obj.valueForProperty = function(p) {return this[p]};
	}
	if (obj['$$layout']) {
		ctrl.layoutWidgets.push(obj);
	}
};

In2iGui.callDelegatesDrop = function(dragged,dropped) {
	var gui = In2iGui.get();
	var result = null;
	for (var i=0; i < gui.delegates.length; i++) {
		if (gui.delegates[i]['$drop$'+dragged.kind+'$'+dropped.kind]) {
			gui.delegates[i]['$drop$'+dragged.kind+'$'+dropped.kind](dragged,dropped);
		}
	}
};

In2iGui.callAncestors = function(obj,method,value,event) {
	if (typeof(value)=='undefined') value=obj;
	var d = In2iGui.get().getAncestors(obj);
	for (var i=0; i < d.length; i++) {
		if (d[i][method]) {
			d[i][method](value,event);
		}
	};
};

In2iGui.callDescendants = function(obj,method,value,event) {
	if (typeof(value)=='undefined') {
		value=obj;
	}
	if (!method[0]=='$') {
		method = '$'+method;
	}
	var d = In2iGui.get().getDescendants(obj);
	for (var i=0; i < d.length; i++) {
		if (d[i][method]) {
			thisResult = d[i][method](value,event);
		}
	};
};

In2iGui.callVisible = function(widget) {
	In2iGui.callDescendants(widget,'$visibilityChanged');
}

In2iGui.listen = function(d) {
	In2iGui.get().listen(d);
}

In2iGui.callDelegates = function(obj,method,value,event) {
	if (typeof(value)=='undefined') {
		value=obj;
	}
	var result = null;
	if (obj.delegates) {
		for (var i=0; i < obj.delegates.length; i++) {
			var delegate = obj.delegates[i];
			var thisResult = null;
			if (obj.name && delegate['$'+method+'$'+obj.name]) {
				thisResult = delegate['$'+method+'$'+obj.name](value,event);
			} else if (delegate['$'+method]) {
				thisResult = delegate['$'+method](value,event);
			}
			if (result==null && thisResult!=null && typeof(thisResult)!='undefined') {
				result = thisResult;
			}
		};
	}
	var superResult = In2iGui.callSuperDelegates(obj,method,value,event);
	if (result==null && superResult!=null) {
		result = superResult;
	}
	return result;
};

In2iGui.callSuperDelegates = function(obj,method,value,event) {
	if (typeof(value)=='undefined') value=obj;
	var gui = In2iGui.get();
	var result = null;
	for (var i=0; i < gui.delegates.length; i++) {
		var delegate = gui.delegates[i];
		var thisResult = null;
		if (obj.name && delegate['$'+method+'$'+obj.name]) {
			thisResult = delegate['$'+method+'$'+obj.name](value,event);
		} else if (delegate['$'+method]) {
			thisResult = delegate['$'+method](value,event);
		}
		if (result==null && thisResult!=null && typeof(thisResult)!='undefined') {
			result = thisResult;
		}
	};
	return result;
};

In2iGui.resolveImageUrl = function(widget,img,width,height) {
	for (var i=0; i < widget.delegates.length; i++) {
		if (widget.delegates[i].$resolveImageUrl) {
			return widget.delegates[i].$resolveImageUrl(img,width,height);
		}
	};
	var gui = In2iGui.get();
	for (var i=0; i < gui.delegates.length; i++) {
		var delegate = gui.delegates[i];
		if (delegate.$resolveImageUrl) {
			return delegate.$resolveImageUrl(img,width,height);
		}
	}
	return null;
};

////////////////////////////// Bindings ///////////////////////////

In2iGui.firePropertyChange = function(obj,name,value) {
	In2iGui.callDelegates(obj,'propertyChanged',{property:name,value:value});
};

In2iGui.bind = function(expression,delegate) {
	if (expression.charAt(0)=='@') {
		var pair = expression.substring(1).split('.');
		var obj = In2iGui.get(pair[0]);
		if (!obj) {
			n2i.log('Unable to bind to '+expression);
			return;
		}
		var p = pair.slice(1).join('.');
		obj.listen({
			$propertyChanged : function(prop) {
				if (prop.property==p) {
					delegate(prop.value);
				}
			}
		});
		return obj.valueForProperty(p);
	}
	return expression;
};

//////////////////////////////// Data /////////////////////////////

In2iGui.dwrUpdate = function() {
	var func = arguments[0];
	var delegate = {
  		callback:function(data) { In2iGui.handleDwrUpdate(data) }
	}
	var num = arguments.length;
	if (num==1) {
		func(delegate);
	} else if (num==2) {
		func(arguments[1],delegate);
	} else {
		alert('Too many parameters');
	}
};

In2iGui.handleDwrUpdate = function(data) {
	var gui = In2iGui.get();
	for (var i=0; i < data.length; i++) {
		if (gui.objects.get(data[i].name)) {
			gui.objects.get(data[i].name).updateFromObject(data[i]);
		}
	};
};

In2iGui.update = function(url,delegate) {
	var dlgt = {
		onSuccess:function(t) {In2iGui.handleUpdate(t,delegate)}
	}
	$get(url,dlgt);
};

In2iGui.handleUpdate = function(t,delegate) {
	var gui = In2iGui.get();
	var doc = t.responseXML.firstChild;
	var children = doc.childNodes;
	for (var i=0; i < children.length; i++) {
		if (children[i].nodeType==1) {
			var name = children[i].getAttribute('name');
			if (name && name!='' && gui.objects.get(name)) {
				gui.objects.get(name).updateFromNode(children[i]);
			}
		}
	};
	delegate.onSuccess();
};

/** @private */
In2iGui.jsonResponse = function(t,key) {
	if (!t.responseXML || !t.responseXML.documentElement) {
		var str = t.responseText.replace(/^\s+|\s+$/g, '');
		if (str.length>0) {
			var json = n2i.fromJSON(t.responseText);
		} else {
			json = '';
		}
		In2iGui.callDelegates(json,'success$'+key)
	} else {
		In2iGui.callDelegates(t,'success$'+key)
	}
};

/** @deprecated */
In2iGui.json = function(data,url,delegateOrKey) {
	var options = {url:url,method:'post',parameters:{},onException:function(e) {throw e}};
	if (typeof(delegateOrKey)=='string') {
		options.onSuccess=function(t) {In2iGui.jsonResponse(t,delegateOrKey)};
	} else {
		delegate = delegateOrKey;
	}
	for (key in data) {
		options.parameters[key]=n2i.toJSON(data[key])
	}
	n2i.request(options);
};

In2iGui.jsonRequest = function(o) {
	var options = {method:'post',parameters:{},onException:function(e) {throw e}};
	if (typeof(o.event)=='string') {
		options.onSuccess=function(t) {In2iGui.jsonResponse(t,o.event)};
	} else {
		delegate = delegateOrKey;
	}
	for (key in o.parameters) {
		options.parameters[key]=n2i.toJSON(o.parameters[key])
	}
	options.url = o.url;
	n2i.request(options);
};

In2iGui.request = function(options) {
	options = n2i.override({method:'post',parameters:{}},options);
	if (options.json) {
		for (key in options.json) {
			options.parameters[key]=n2i.toJSON(options.json[key]);
		}
	}
	var onSuccess = options.onSuccess;
	options.onSuccess=function(t) {
		if (typeof(onSuccess)=='string') {
			In2iGui.jsonResponse(t,onSuccess);
		} else if (t.responseXML && t.responseXML.documentElement && t.responseXML.documentElement.nodeName!='parsererror' && options.onXML) {
			options.onXML(t.responseXML);
		} else if (options.onJSON) {
			var str = t.responseText.replace(/^\s+|\s+$/g, '');
			if (str.length>0) {
				var json = n2i.fromJSON(t.responseText);
			} else {
				var json = null;
			}
			options.onJSON(json);
		} else if (typeof(onSuccess)=='function') {
			onSuccess(t);
		} else if (options.onText) {
			options.onText(t.responseText);
		}
	};
	var onFailure = options.onFailure;
	options.onFailure = function(t) {
		if (typeof(onFailure)=='string') {
			In2iGui.callDelegates(t,'failure$'+onFailure)
		} else if (typeof(onFailure)=='function') {
			onFailure(t);
		}
	}
	options.onException = function(t,e) {n2i.log(e)};
	n2i.request(options);
};

In2iGui.parseItems = function(doc) {
	var root = doc.documentElement;
	var out = [];
	In2iGui.parseSubItems(root,out);
	return out;
};

In2iGui.parseSubItems = function(parent,array) {
	var children = parent.childNodes;
	for (var i=0; i < children.length; i++) {
		var node = children[i];
		if (node.nodeType==1 && node.nodeName=='title') {
			array.push({title:node.getAttribute('title'),type:'title'})
		} else if (node.nodeType==1 && node.nodeName=='item') {
			var sub = [];
			In2iGui.parseSubItems(node,sub);
			array.push({
				title:node.getAttribute('title'),
				value:node.getAttribute('value'),
				icon:node.getAttribute('icon'),
				kind:node.getAttribute('kind'),
				badge:node.getAttribute('badge'),
				children:sub
			});
		}
	};
}




//////////////////////////// Prototype extensions ////////////////////////////////
/*
if (Element.addMethods) {
	Element.addMethods({
		setClassName : function(element,name,set) {
			if (set) {
				element.addClassName(name);
			} else {
				element.removeClassName(name);
			}
			return element;
		}
	});
}
*/
/* EOF */
/**
 @constructor
 */
In2iGui.ImageViewer = function(options) {
	this.options = n2i.override({
		maxWidth:800,maxHeight:600,perimeter:100,sizeSnap:100,
		margin:0,
		ease:n2i.ease.slowFastSlow,
		easeEnd:n2i.ease.bounce,
		easeAuto:n2i.ease.slowFastSlow,
		easeReturn:n2i.ease.cubicInOut,transition:400,transitionEnd:1000,transitionReturn:300
		},options);
	this.element = n2i.get(options.element);
	this.box = this.options.box;
	this.viewer = n2i.firstByClass(this.element,'in2igui_imageviewer_viewer');
	this.innerViewer = n2i.firstByClass(this.element,'in2igui_imageviewer_inner_viewer');
	this.status = n2i.firstByClass(this.element,'in2igui_imageviewer_status');
	this.previousControl = n2i.firstByClass(this.element,'in2igui_imageviewer_previous');
	this.controller = n2i.firstByClass(this.element,'in2igui_imageviewer_controller');
	this.nextControl = n2i.firstByClass(this.element,'in2igui_imageviewer_next');
	this.playControl = n2i.firstByClass(this.element,'in2igui_imageviewer_play');
	this.closeControl = n2i.firstByClass(this.element,'in2igui_imageviewer_close');
	this.text = n2i.firstByClass(this.element,'in2igui_imageviewer_text');
	this.dirty = false;
	this.width = 600;
	this.height = 460;
	this.index = 0;
	this.playing=false;
	this.name = options.name;
	this.images = [];
	this.box.listen(this);
	this.addBehavior();
	In2iGui.extend(this);
}

In2iGui.ImageViewer.create = function(options) {
	options = options || {};
	var element = options.element = n2i.build('div',
		{'class':'in2igui_imageviewer',
		html:
		'<div class="in2igui_imageviewer_viewer"><div class="in2igui_imageviewer_inner_viewer"></div></div>'+
		'<div class="in2igui_imageviewer_text"></div>'+
		'<div class="in2igui_imageviewer_status"></div>'+
		'<div class="in2igui_imageviewer_controller"><div><div>'+
		'<a class="in2igui_imageviewer_previous"></a>'+
		'<a class="in2igui_imageviewer_play"></a>'+
		'<a class="in2igui_imageviewer_next"></a>'+
		'<a class="in2igui_imageviewer_close"></a>'+
		'</div></div></div>'});
	var box = In2iGui.Box.create({absolute:true,modal:true,closable:true});
	box.add(element);
	box.addToDocument();
	options.box=box;
	return new In2iGui.ImageViewer(options);
}

In2iGui.ImageViewer.prototype = {
	/** @private */
	addBehavior : function() {
		var self = this;
		this.nextControl.onclick = function() {
			self.next(true);
		}
		this.previousControl.onclick = function() {
			self.previous(true);
		}
		this.playControl.onclick = function() {
			self.playOrPause();
		}
		this.closeControl.onclick = this.hide.bind(this);
		n2i.listen(this.viewer,'click',this.zoom.bind(this));
		this.timer = function() {
			self.next(false);
		}
		this.keyListener = function(e) {
			e = n2i.event(e);
			if (e.rightKey) {
				self.next(true);
			} else if (e.leftKey) {
				self.previous(true);
			} else if (e.escapeKey) {
				self.hide();
			} else if (e.returnKey) {
				self.playOrPause();
			}
		},
		n2i.listen(this.viewer,'mousemove',this.mouseMoveEvent.bind(this));
		n2i.listen(this.controller,'mouseenter',function() {
			self.overController = true;
		});
		n2i.listen(this.controller,'mouseleave',function() {
			self.overController = false;
		});
		n2i.listen(this.viewer,'mouseout',function(e) {
			if (!In2iGui.isWithin(e,this.viewer)) {
				self.hideController();
			}
		}.bind(this));
	},
	/** @private */
	mouseMoveEvent : function() {
		window.clearTimeout(this.ctrlHider);
		if (this.shouldShowController()) {
			this.ctrlHider = window.setTimeout(this.hideController.bind(this),2000);
			if (n2i.browser.msie) {
				this.controller.show();
			} else {
				In2iGui.fadeIn(this.controller,200);
			}
		}
	},
	/** @private */
	hideController : function() {
		if (!this.overController) {
			if (n2i.browser.msie) {
				this.controller.hide();
			} else {
				In2iGui.fadeOut(this.controller,500);
			}
		}
	},
	/** @private */
	zoom : function(e) {
		var img = this.images[this.index];
		if (img.width<=this.width && img.height<=this.height) {
			return; // Don't zoom if small
		}
		if (!this.zoomer) {
			this.zoomer = n2i.build('div',{
				'class' : 'in2igui_imageviewer_zoomer',
				style : 'width:'+this.viewer.clientWidth+'px;height:'+this.viewer.clientHeight+'px'
			});
			this.element.insertBefore(this.zoomer,n2i.firstByTag(this.element,'*'));
			n2i.listen(this.zoomer,'mousemove',this.zoomMove.bind(this));
			n2i.listen(this.zoomer,'click',function() {
				this.style.display='none';
			});
		}
		this.pause();
		var size = this.getLargestSize({width:2000,height:2000},img);
		var url = In2iGui.resolveImageUrl(this,img,size.width,size.height);
		this.zoomer.innerHTML = '<div style="width:'+size.width+'px;height:'+size.height+'px;"><img src="'+url+'"/></div>';
		this.zoomer.style.display = 'block';
		this.zoomInfo = {width:size.width,height:size.height};
		this.zoomMove(e);
	},
	zoomMove : function(e) {
		e = new n2i.Event(e);
		if (!this.zoomInfo) {
			return;
		}
		var offset = {left:n2i.getLeft(this.zoomer),top:n2i.getTop(this.zoomer)};
		var x = (e.left()-offset.left)/this.zoomer.clientWidth*(this.zoomInfo.width-this.zoomer.clientWidth);
		var y = (e.top()-offset.top)/this.zoomer.clientHeight*(this.zoomInfo.height-this.zoomer.clientHeight);
		this.zoomer.scrollLeft = x;
		this.zoomer.scrollTop = y;
	},
	/** @private */
	getLargestSize : function(canvas,image) {
		if (image.width<=canvas.width && image.height<=canvas.height) {
			return {width:image.width,height:image.height};
		} else if (canvas.width/canvas.height>image.width/image.height) {
			return {width:Math.round(canvas.height/image.height*image.width),height:canvas.height};
		} else if (canvas.width/canvas.height<image.width/image.height) {
			return {width:canvas.width,height:Math.round(canvas.width/image.width*image.height)};
		} else {
			return {width:canvas.width,height:canvas.height};
		}
	},
	/** @private */
	calculateSize : function() {
		var snap = this.options.sizeSnap;
		var newWidth = n2i.getInnerWidth()-this.options.perimeter;
		newWidth = Math.floor(newWidth/snap)*snap;
		newWidth = Math.min(newWidth,this.options.maxWidth);
		var newHeight = n2i.getInnerHeight()-this.options.perimeter;
		newHeight = Math.floor(newHeight/snap)*snap;
		newHeight = Math.min(newHeight,this.options.maxHeight);
		var maxWidth = 0;
		var maxHeight = 0;
		for (var i=0; i < this.images.length; i++) {
			var dims = this.getLargestSize({width:newWidth,height:newHeight},this.images[i]);
			maxWidth = Math.max(maxWidth,dims.width);
			maxHeight = Math.max(maxHeight,dims.height);
		};
		newHeight = Math.floor(Math.min(newHeight,maxHeight));
		newWidth = Math.floor(Math.min(newWidth,maxWidth));
		
		if (newWidth!=this.width || newHeight!=this.height) {
			this.width = newWidth;
			this.height = newHeight;
			this.dirty = true;
		}
	},
	adjustSize : function() {
		
	},
	showById: function(id) {
		for (var i=0; i < this.images.length; i++) {
			if (this.images[i].id==id) {
				this.show(i);
				break;
			}
		};
	},
	show: function(index) {
		this.index = index || 0;
		this.calculateSize();
		this.updateUI();
		var margin = this.options.margin;
		n2i.setStyle(this.element, {width:(this.width+margin)+'px',height:(this.height+margin*2-1)+'px'});
		n2i.setStyle(this.viewer, {width:(this.width+margin)+'px',height:(this.height-1)+'px'});
		n2i.setStyle(this.innerViewer, {width:((this.width+margin)*this.images.length)+'px',height:(this.height-1)+'px'});
		n2i.setStyle(this.controller, {marginLeft:((this.width-180)/2+margin*0.5)+'px',display:'none'});
		this.box.show();
		this.goToImage(false,0,false);
		n2i.listen(document,'keydown',this.keyListener);
	},
	hide: function(index) {
		this.pause();
		this.box.hide();
		n2i.unListen(document,'keydown',this.keyListener);
	},
	/** @private */
	$boxCurtainWasClicked : function() {
		this.hide();
	},
	/** @private */
	$boxWasClosed : function() {
		this.hide();
	},
	/** @private */
	updateUI : function() {
		if (this.dirty) {
			this.innerViewer.innerHTML='';
			for (var i=0; i < this.images.length; i++) {
				var element = n2i.build('div',{'class':'in2igui_imageviewer_image'});
				n2i.setStyle(element,{'width':(this.width+this.options.margin)+'px','height':(this.height-1)+'px'});
				this.innerViewer.appendChild(element);
			};
			if (this.shouldShowController()) {
				this.controller.style.display='block';
			} else {
				this.controller.style.display='none';
			}
			this.dirty = false;
			this.preload();
		}
	},
	/** @private */
	shouldShowController : function() {
		return this.images.length>1;
	},
	/** @private */
	goToImage : function(animate,num,user) {	
		if (animate) {
			if (num>1) {
				n2i.ani(this.viewer,'scrollLeft',this.index*(this.width+this.options.margin),Math.min(num*this.options.transitionReturn,2000),{ease:this.options.easeReturn});				
			} else {
				var end = this.index==0 || this.index==this.images.length-1;
				var ease = (end ? this.options.easeEnd : this.options.ease);
				if (!user) {
					ease = this.options.easeAuto;
				}
				n2i.ani(this.viewer,'scrollLeft',this.index*(this.width+this.options.margin),(end ? this.options.transitionEnd : this.options.transition),{ease:ease});
			}
		} else {
			this.viewer.scrollLeft=this.index*(this.width+this.options.margin);
		}
		var text = this.images[this.index].text;
		if (text) {
			this.text.innerHTML=text;
			this.text.style.display='block';
		} else {
			this.text.innerHTML='';
			this.text.style.display='none';
		}
	},
	clearImages : function() {
		this.images = [];
		this.dirty = true;
	},
	addImages : function(images) {
		for (var i=0; i < images.length; i++) {
			this.addImage(images[i]);
		};
	},
	addImage : function(img) {
		this.images.push(img);
		this.dirty = true;
	},
	play : function() {
		if (!this.interval) {
			this.interval = window.setInterval(this.timer,6000);
		}
		this.next(false);
		this.playing=true;
		this.playControl.className='in2igui_imageviewer_pause';
	},
	pause : function() {
		window.clearInterval(this.interval);
		this.interval = null;
		this.playControl.className='in2igui_imageviewer_play';
		this.playing = false;
	},
	playOrPause : function() {
		if (this.playing) {
			this.pause();
		} else {
			this.play();
		}
	},
	resetPlay : function() {
		if (this.playing) {
			window.clearInterval(this.interval);
			this.interval = window.setInterval(this.timer,6000);
		}
	},
	previous : function(user) {
		var num = 1;
		this.index--;
		if (this.index<0) {
			this.index=this.images.length-1;
			num = this.images.length-1;
		}
		this.goToImage(true,num,user);
		this.resetPlay();
	},
	next : function(user) {
		var num = 1;
		this.index++;
		if (this.index==this.images.length) {
			this.index=0;
			num = this.images.length-1;
		}
		this.goToImage(true,num,user);
		this.resetPlay();
	},
	/** @private */
	preload : function() {
		var guiLoader = new n2i.Preloader();
		guiLoader.addImages(In2iGui.context+'In2iGui/gfx/imageviewer_controls.png');
		var self = this;
		guiLoader.setDelegate({allImagesDidLoad:function() {self.preloadImages()}});
		guiLoader.load();
	},
	/** @private */
	preloadImages : function() {
		var loader = new n2i.Preloader();
		loader.setDelegate(this);
		for (var i=0; i < this.images.length; i++) {
			var url = In2iGui.resolveImageUrl(this,this.images[i],this.width,this.height);
			if (url!==null) {
				loader.addImages(url);
			}
		};
		this.status.innerHTML = '0%';
		this.status.style.display='';
		loader.load(this.index);
	},
	/** @private */
	allImagesDidLoad : function() {
		this.status.style.display='none';
	},
	/** @private */
	imageDidLoad : function(loaded,total,index) {
		this.status.innerHTML = Math.round(loaded/total*100)+'%';
		var url = In2iGui.resolveImageUrl(this,this.images[index],this.width,this.height);
		url = url.replace(/&amp;/g,'&');
		this.innerViewer.childNodes[index].style.backgroundImage="url('"+url+"')";
		n2i.setClass(this.innerViewer.childNodes[index],'in2igui_imageviewer_image_abort',false);
		n2i.setClass(this.innerViewer.childNodes[index],'in2igui_imageviewer_image_error',false);
	},
	/** @private */
	imageDidGiveError : function(loaded,total,index) {
		n2i.setClass(this.innerViewer.childNodes[index],'in2igui_imageviewer_image_error',true);
	},
	/** @private */
	imageDidAbort : function(loaded,total,index) {
		n2i.setClass(this.innerViewer.childNodes[index],'in2igui_imageviewer_image_abort',true);
	}
}

/* EOF *//**
 * @constructor
 * @param {Object} options The options : {modal:false}
 */
In2iGui.Box = function(options) {
	this.options = n2i.override({},options);
	this.name = options.name;
	this.element = n2i.get(options.element);
	this.body = n2i.firstByClass(this.element,'in2igui_box_body');
	this.close = n2i.firstByClass(this.element,'in2igui_box_close');
	if (this.close) {
		n2i.listen(this.close,'click',function(e) {
			n2i.stop(e);
			this.hide();
			this.fire('boxWasClosed');
		}.bind(this));
	}
	In2iGui.extend(this);
};

/**
 * Creates a new box widget
 * @param {Object} options The options : {width:0,padding:0,absolute:false,closable:false}
 */
In2iGui.Box.create = function(options) {
	options = options || {};
	options.element = n2i.build('div',{
		'class' : options.absolute ? 'in2igui_box in2igui_box_absolute' : 'in2igui_box',
		html : (options.closable ? '<a class="in2igui_box_close" href="#"></a>' : '')+
			'<div class="in2igui_box_top"><div><div></div></div></div>'+
			'<div class="in2igui_box_middle"><div class="in2igui_box_middle">'+
			(options.title ? '<div class="in2igui_box_header"><strong class="in2igui_box_title">'+n2i.escape(options.title)+'</strong></div>' : '')+
			'<div class="in2igui_box_body"'+(options.padding ? ' style="padding: '+options.padding+'px;"' : '')+'></div>'+
			'</div></div>'+
			'<div class="in2igui_box_bottom"><div><div></div></div></div>',
		style : options.width ? options.width+'px' : null
	});
	return new In2iGui.Box(options);
};

In2iGui.Box.prototype = {
	/**
	 * Adds the box to the end of the body
	 */
	addToDocument : function() {
		document.body.appendChild(this.element);
	},
	/**
	 * Adds a child widget or node
	 */
	add : function(widget) {
		if (widget.getElement) {
			this.body.appendChild(widget.getElement());
		} else {
			this.body.appendChild(widget);
		}
	},
	/**
	 * Shows the box
	 */
	show : function() {
		var e = this.element;
		if (this.options.modal) {
			var index = In2iGui.nextPanelIndex();
			e.style.zIndex=index+1;
			In2iGui.showCurtain({widget:this,zIndex:index});
		}
		if (this.options.absolute) {
			n2i.setStyle(e,{display:'block',visibility:'hidden'});
			var w = e.clientWidth;
			var top = (n2i.getInnerHeight()-e.clientHeight)/2+n2i.getScrollTop();
			n2i.setStyle(e,{'marginLeft':(w/-2)+'px',top:top+'px'});
			n2i.setStyle(e,{display:'block',visibility:'visible'});
		} else {
			e.style.display='block';
		}
		In2iGui.callVisible(this);
	},
	/**
	 * Hides the box
	 */
	hide : function() {
		In2iGui.hideCurtain(this);
		this.element.style.display='none';
	},
	/** @private */
	curtainWasClicked : function() {
		this.fire('boxCurtainWasClicked');
	}
};