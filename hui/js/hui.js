/** @namespace */
var hui = {
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
    KEY_INSERT : 45
}

if (!window.n2i) {
	var n2i = hui;
}

hui.browser.opera = /opera/i.test(navigator.userAgent);
hui.browser.msie = !hui.browser.opera && /MSIE/.test(navigator.userAgent);
hui.browser.msie6 = navigator.userAgent.indexOf('MSIE 6')!==-1;
hui.browser.msie7 = navigator.userAgent.indexOf('MSIE 7')!==-1;
hui.browser.msie8 = navigator.userAgent.indexOf('MSIE 8')!==-1;
hui.browser.msie9 = navigator.userAgent.indexOf('MSIE 9')!==-1;
hui.browser.webkit = navigator.userAgent.indexOf('WebKit')!==-1;
hui.browser.safari = navigator.userAgent.indexOf('Safari')!==-1;
hui.browser.webkitVersion = null;
hui.browser.gecko = !hui.browser.webkit && navigator.userAgent.indexOf('Gecko')!=-1;

hui.browser.opacity = !hui.browser.msie || hui.browser.msie9;

(function() {
	var result = /Safari\/([\d.]+)/.exec(navigator.userAgent);
	if (result) {
		hui.browser.webkitVersion=parseFloat(result[1]);
	}
})()

/** Log something */
hui.log = function(obj) {
	try {
		console.log(obj);
	} catch (ignore) {};
}

/** Override the properties on the first argument with properties from the last object */
hui.override = function(original,subject) {
	if (subject) {
		for (prop in subject) {
			original[prop] = subject[prop];
		}
	}
	return original;
}

/** Inserts invisible break chars in string so it will wrap */
hui.wrap = function(str) {
	if (str===null || str===undefined) {
		return '';
	}
	return str.split('').join("\u200B");
}

/** Trim whitespace including unicode chars */
hui.trim = function(str) {
	if (str===null || str===undefined) {return ''};
	if (typeof(str)!='string') {str=new String(str)}
	return str.replace(/^[\s\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000]+|[\s\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000]+$/g, '');
}

/** Escape the html in a string */
hui.escapeHTML = function(str) {
	if (str===null || str===undefined) {return ''};
   	return hui.build('div',{text:str}).innerHTML;
}

hui.escape = function(str) {
	if (!hui.isDefined(str)) {return ''};
	return str.replace(/&/g,'&amp;').
		replace(/>/g,'&gt;').
		replace(/</g,'&lt;').
		replace(/"/g,'&quot;')
}

/** Checks if a string has characters */
hui.isBlank = function(str) {
	if (str===null || typeof(str)==='undefined' || str==='') {return true};
	return typeof(str)=='string' && hui.trim(str).length==0;
}

hui.isEmpty = function(str) {
	hui.log('hui.isEmpty is deprecated');
	return hui.isBlank(str);
}

/** Checks that an object is not null or undefined */
hui.isDefined = function(obj) {
	return obj!==null && typeof(obj)!=='undefined';
}

hui.isArray = function(obj) {
	if (obj==null || obj==undefined) {
		return false;
	}
	if (obj.constructor == Array) {
		return true;
	} else {
		return Object.prototype.toString.call(obj) === '[object Array]';
	}
}

/** @namespace */
hui.string = {
	
	/**
	Test that a string ends with another string
	@param str The string to test
	@param end The string to look for at the end
	*/
	endsWith : function(str,end) {
		if (!typeof(str)=='string' || !typeof(end)=='string') {
			return false;
		}
		return (str.match(end+"$")==end);
	},
	
	/** Make a string camelized */
	camelize : function(str) {
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
}

hui.inArray = function(arr,value) {
	for (var i=0; i < arr.length; i++) {
		if (arr[i]===value) {
			return true;
		}
	};
	return false;
}

hui.indexInArray = function(arr,value) {
	for (var i=0; i < arr.length; i++) {
		if (arr[i]===value) {
			return i;
		}
	};
	return -1;
}

hui.each = function(items,func) {
	if (hui.isArray(items)) {		
		for (var i=0; i < items.length; i++) {
			func(items[i],i);
		};
	} else {
		for (var key in items) {
			func(key,items[key]);
		}
	}
}

/**
 * Converts a string to an int if it is only digits, otherwise remains a string
 */
hui.intOrString = function(str) {
	if (hui.isDefined(str)) {
		var result = /[0-9]+/.exec(str);
		if (result!==null && result[0]==str) {
			if (parseInt(str,10)==str) {
				return parseInt(str,10);
			}
		}
	}
	return str;
}

hui.flipInArray = function(arr,value) {
	if (hui.inArray(arr,value)) {
		hui.removeFromArray(arr,value);
	} else {
		arr.push(value);
	}
}

hui.removeFromArray = function(arr,value) {
	for (var i = arr.length - 1; i >= 0; i--){
		if (arr[i]==value) {
			arr.splice(i,1);
		}
	};
}

hui.addToArray = function(arr,value) {
	if (value.constructor==Array) {
		for (var i=0; i < value.length; i++) {
			if (!hui.inArray(arr,value[i])) {
				arr.push(value);
			}
		};
	} else {
		if (!hui.inArray(arr,value)) {
			arr.push(value);
		}
	}
}

hui.toIntArray = function(str) {
	var array = str.split(',');
	for (var i = array.length - 1; i >= 0; i--){
		array[i] = parseInt(array[i]);
	};
	return array;
}

hui.scrollTo = function(element) {
	element = hui.get(element);
	if (element) {
		var pos = hui.getPosition(element);
		window.scrollTo(pos.left, pos.top-50);
	}
}

////////////////////// DOM ////////////////////

/** @namespace */
hui.dom = {
	isElement : function(n,name) {
		return n.nodeType==hui.ELEMENT_NODE && (name===undefined ? true : n.nodeName.toLowerCase()==name);
	},
	isDefinedText : function(node) {
		return node.nodeType==hui.TEXT_NODE && node.nodeValue.length>0;
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
	replaceNode : function(oldNode,newNode) {
		newNode.parentNode.removeChild(newNode);
		oldNode.parentNode.insertBefore(newNode,oldNode);
		oldNode.parentNode.removeChild(oldNode);
	},
	replaceHTML : function(node,html) {
		node = hui.get(node);
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
		for (var i = c.length - 1; i >= 0; i--){
			if (!updated && c[i].nodeType==hui.TEXT_NODE) {
				c[i].nodeValue=text;
				updated = true;
			} else {
				node.removeChild(c[i]);
			}
		}
		if (!updated) {
			hui.dom.addText(node,text);
		}
	},
	getText : function(node) {
		var txt = '';
		var c = node.childNodes;
		for (var i=0; i < c.length; i++) {
			if (c[i].nodeType==hui.TEXT_NODE && c[i].nodeValue!=null) {
				txt+=c[i].nodeValue;
			} else if (c[i].nodeType==hui.ELEMENT_NODE) {
				txt+=hui.dom.getText(c[i]);
			}
		};
		return txt;
	},
	isVisible : function(node) {
		while (node) {
			if (node.style && (hui.getStyle(node,'display')==='none' || hui.getStyle(node,'visibility')==='hidden')) {
				return false;
			}
			node = node.parentNode;
		}
		return true;
	}
}

hui.form = {
	getValues : function(node) {
		var params = {};
		var inputs = node.getElementsByTagName('input');
		for (var i=0; i < inputs.length; i++) {
			if (hui.isDefined(inputs[i].name)) {
				params[inputs[i].name] = inputs[i].value;
			}
		};
		return params;
	}
}

///////////////////////////// Quering ////////////////////////

hui.get = function(str) {
	if (typeof(str)=='string') {
		return document.getElementById(str);
	}
	return str;
}

hui.getChildren = function(node) {
	var children = [];
	var x = node.childNodes;
	for (var i=0; i < x.length; i++) {
		if (hui.dom.isElement(x[i])) {
			children.push(x[i]);
		}
	};
	return children;
}


if (document.querySelector) {
	hui.firstByClass = function(parentElement,className,tag) {
		parentElement = parentElement || document.body;
		return parentElement.querySelector((tag ? tag+'.' : '.')+className);
	}
} else {
	hui.firstByClass = function(parentElement,className,tag) {
		var children = (hui.get(parentElement) || document.body).getElementsByTagName(tag || '*');
		for (var i=0;i<children.length;i++) {
			if (hui.hasClass(children[i],className)) {
				return children[i];
			}
		}
		return null;
	}
}

if (document.querySelectorAll) {
	hui.byClass = function(parentElement,className,tag) {
		parentElement = parentElement || document.body;
		var nl = parentElement.querySelectorAll((tag ? tag+'.' : '.')+className);
		// Important to convert into array...
		var l=[];
		for(var i=0, ll=nl.length; i!=ll; l.push(nl[i++]));
		return l;
	}
} else {
	hui.byClass = function(parentElement,className,tag) {
		var children = (hui.get(parentElement) || document.body).getElementsByTagName(tag || '*'),
			out = [];
		for (var i=0;i<children.length;i++) {
			if (hui.hasClass(children[i],className)) {
				out[out.length]=children[i];
			}
		}
		return out;
	}
}

hui.byTag = function(node,name) {
	var nl = node.getElementsByTagName(name),
		l=[];
	for(var i=0, ll=nl.length; i!=ll; l.push(nl[i++]));
	return l;
}

hui.byId = function(e,id) {
	var children = e.childNodes;
	for (var i = children.length - 1; i >= 0; i--) {
		if (children[i].nodeType===hui.ELEMENT_NODE && children[i].getAttribute('id')===id) {
			return children[i];
		} else {
			var found = hui.byId(children[i],id);
			if (found) {
				return found;
			}
		}
	}
	return null;
}

hui.firstParentByTag = function(node,tag) {
	var parent = node;
	while (parent) {
		if (parent.tagName && parent.tagName.toLowerCase()==tag) {
			return parent;
		}
		parent = parent.parentNode;
	}
	return null;
}

hui.firstParentByClass = function(node,tag) {
	var parent = node;
	while (parent) {
		if (hui.hasClass(parent)) {
			return parent;
		}
		parent = parent.parentNode;
	}
	return null;
}

hui.firstByTag = function(parentElement,tag) {
	parentElement = hui.get(parentElement) || document.body;
	if (document.querySelector && tag!=='*') {
		return parentElement.querySelector(tag);
	}
	var children = parentElement.getElementsByTagName(tag);
	return children[0];
}

hui.build = function(tag,options) {
	var e = document.createElement(tag);
	if (options) {
		for (prop in options) {
			if (prop=='text') {
				e.appendChild(document.createTextNode(options.text));
			} else if (prop=='html') {
				e.innerHTML=options.html;
			} else if (prop=='parent') {
				options.parent.appendChild(e);
			} else if (prop=='parentFirst') {
				if (options.parentFirst.childNodes.length==0) {
					options.parentFirst.appendChild(e);
				} else {
					options.parentFirst.insertBefore(e,options.parentFirst.childNodes[0]);
				}
			} else if (prop=='className') {
				e.className=options.className;
			} else if (prop=='class') {
				e.className=options['class'];
			} else if (prop=='style' && (hui.browser.msie7 || hui.browser.msie6)) {
				e.style.setAttribute('cssText',options[prop]);
			} else if (hui.isDefined(options[prop])) {
				e.setAttribute(prop,options[prop]);
			}
		}
	}
	return e;
}

hui.getAncestors = function(element) {
	var ancestors = [];
	var parent = element.parentNode;
	while (parent) {
		ancestors[ancestors.length] = parent;
		parent = parent.parentNode;
	}
	return ancestors;
}

hui.getNext = function(element) {
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

hui.getAllNext = function(element) {
	var elements = [];
	var next = hui.getNext(element);
	while (next) {
		elements.push(next);
		next = hui.getNext(next);
	}
	return elements;
}

hui.getTop = function(element) {
    element = hui.get(element);
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

hui.getLeft = function(element) {
    element = hui.get(element);
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

hui.getPosition = function(element) {
	return {
		left : hui.getLeft(element),
		top : hui.getTop(element)
	}
}

/////////////////////////// Class handling //////////////////////

hui.hasClass = function(element, className) {
	element = hui.get(element);
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

hui.addClass = function(element, className) {
    element = hui.get(element);
	if (!element) {return};
	
    hui.removeClass(element, className);
    element.className += ' ' + className;
}

hui.removeClass = function(element, className) {
	element = hui.get(element);
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

hui.toggleClass = function(element,className) {
	if (hui.hasClass(element,className)) {
		hui.removeClass(element,className);
	} else {
		hui.addClass(element,className);
	}
}

hui.setClass = function(element,className,add) {
	if (add) {
		hui.addClass(element,className);
	} else {
		hui.removeClass(element,className);
	}
}

hui.fromJSON = function(json) {
	return JSON.parse(json);
	//return eval('(' + json + ')');
}

hui.toJSON = function(obj) {
	return JSON.stringify(obj);
}

///////////////////// Events //////////////////

hui.listen = function(el,type,listener,useCapture) {
	el = hui.get(el);
	if(document.addEventListener) {
		el.addEventListener(type,listener,useCapture ? true : false);
	} else {
		el.attachEvent('on'+type, listener);
	}
}

hui.unListen = function(el,type,listener,useCapture) {
	el = hui.get(el);
	if(document.removeEventListener) {
		el.removeEventListener(type,listener,useCapture ? true : false);
	} else {
		el.detachEvent('on'+type, listener);
	}
}

hui.event = function(event) {
	return new hui.Event(event);
}

hui.Event = function(event) {
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

hui.Event.prototype = {
	left : function() {
	    var left = 0;
		if (this.event) {
		    if (this.event.pageX) {
			    left = this.event.pageX;
		    } else if (this.event.clientX) {
			    left = this.event.clientX + hui.getScrollLeft();
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
			    top = this.event.clientY + hui.getScrollTop();
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
			if (hui.hasClass(parent,cls)) {
				return parent;
			}
			parent = parent.parentNode;
		}
		return null;
	},
	findByTag : function(tag) {
		var parent = this.element;
		while (parent) {
			if (parent.tagName && parent.tagName.toLowerCase()==tag) {
				return parent;
			}
			parent = parent.parentNode;
		}
		return null;
	},

	stop : function() {
		hui.stop(this.event);
	}
}

hui.stop = function(e) {
	if (!e) {e = window.event};
	if (e.stopPropagation) {e.stopPropagation()};
	if (e.preventDefault) {e.preventDefault()};
	e.cancelBubble = true;
    e.stopped = true;
}

hui.onReady = function(delegate) {
	if(window.addEventListener) {
		window.addEventListener('DOMContentLoaded',delegate,false);
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

hui.request = function(options) {
	options = hui.override({method:'POST',async:true},options);
	var transport = hui.request.createTransport();
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
	var body = '';
    if (method=='POST' && options.parameters) {
		body = hui.request.buildPostBody(options.parameters);
		transport.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	}
	transport.send(body);
}

hui.request.isXMLResponse = function(t) {
	return t.responseXML && t.responseXML.documentElement && t.responseXML.documentElement.nodeName!='parsererror';
}

hui.request.buildPostBody = function(parameters) {
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

hui.request.createTransport = function() {
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
			return hui.request.getActiveX();
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

hui.request.getActiveX = function() {
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

hui.getStyle = function(element, style) {
	element = hui.get(element);
	var cameled = hui.string.camelize(style);
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
		if (hui.getStyle(element, 'position') == 'static') value = 'auto';
	}
	return value == 'auto' ? null : value;
}

hui.getTopPad = function(element) {
	var all,top;
	all = parseInt(hui.getStyle(element,'padding'),10);
	top = parseInt(hui.getStyle(element,'padding-top'),10);
	if (all) {return all;}
	if (top) {return top;}
	return 0;
}

hui.getBottomPad = function(element) {
	var all,bottom;
	all = parseInt(hui.getStyle(element,'padding'),10);
	bottom = parseInt(hui.getStyle(element,'padding-bottom'),10);
	if (all) {return all;}
	if (bottom) {return bottom;}
	return 0;
}

hui.setOpacity = function(element,opacity) {
	if (!hui.browser.opacity) {
		if (opacity==1) {
			element.style['filter']=null;
		} else {
			element.style['filter']='alpha(opacity='+(opacity*100)+')';
		}
	} else {
		element.style['opacity']=opacity;
	}
}

hui.setStyle = function(element,styles) {
	for (style in styles) {
		if (style==='opacity') {
			hui.setOpacity(element,styles[style]);
		} else {
			element.style[style] = styles[style];
		}
	}
}

hui.copyStyle = function(source,target,styles) {
	for (var i=0; i < styles.length; i++) {
		var s = styles[i];
		var r = hui.getStyle(source,s);
		if (r) {
			target.style[s] = r;
		}
	};
}

//////////////////// Frames ////////////////////

hui.getFrameDocument = function(frame) {
    if (frame.contentDocument) {
        return frame.contentDocument;
    } else if (frame.contentWindow) {
        return frame.contentWindow.document;
    } else if (frame.document) {
        return frame.document;
    }
}

hui.getFrameWindow = function(frame) {
    if (frame.defaultView) {
        return frame.defaultView;
    } else if (frame.contentWindow) {
        return frame.contentWindow;
    }
}

/////////////////// Selection /////////////////////

hui.getSelectedText = function(doc) {
	doc = doc || document;
	if (doc.getSelection) {
		return doc.getSelection()+'';
	} else if (doc.selection) {
		return doc.selection.createRange().text;
	}
	return '';
}

hui.selection = {
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

hui.effect = {
	makeFlippable : function(options) {
		if (hui.browser.webkit) {
			hui.addClass(options.container,'in2igui_flip_container');
			hui.addClass(options.front,'in2igui_flip_front');
			hui.addClass(options.back,'in2igui_flip_back');
		} else {
			hui.addClass(options.front,'in2igui_flip_front_legacy');
			hui.addClass(options.back,'in2igui_flip_back_legacy');
		}
	},
	flip : function(options) {
		if (!hui.browser.webkit) {
			hui.toggleClass(options.element,'in2igui_flip_flipped_legacy');
		} else {
			var element = hui.get(options.element);
			var duration = options.duration || '1s';
			var front = hui.firstByClass(element,'in2igui_flip_front');
			var back = hui.firstByClass(element,'in2igui_flip_back');
			front.style.webkitTransitionDuration=duration;
			back.style.webkitTransitionDuration=duration;
			hui.toggleClass(options.element,'in2igui_flip_flipped');
		}
	}
}

/////////////////// Position /////////////////////

hui.getScrollTop = function() {
	if (self.pageYOffset) {
		return self.pageYOffset;
	} else if (document.documentElement && document.documentElement.scrollTop) {
		return document.documentElement.scrollTop;
	} else if (document.body) {
		return document.body.scrollTop;
	}
}

hui.getScrollLeft = function() {
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
hui.getViewPortHeight = function() {
	if (window.innerHeight) {
		return window.innerHeight;
	} else if (document.documentElement && document.documentElement.clientHeight) {
		return document.documentElement.clientHeight;
	} else if (document.body) {
		return document.body.clientHeight;
	}
}

/**
 * Get the height of the viewport
 */
hui.getViewPortWidth = function() {
	if (window.innerWidth) {
		return window.innerWidth;
	} else if (document.documentElement && document.documentElement.clientWidth) {
		return document.documentElement.clientWidth;
	} else if (document.body) {
		return document.body.clientWidth;
	}
}

hui.getDocumentWidth = function() {
	return Math.max(document.body.clientWidth,document.documentElement.clientWidth,document.documentElement.scrollWidth)
	return document.body.scrollWidth;
}

hui.getDocumentHeight = function() {
	if (hui.browser.msie6) {
		// In IE6 check the children too
		var max = Math.max(document.body.clientHeight,document.documentElement.clientHeight,document.documentElement.scrollHeight);
		var children = document.body.childNodes;
		for (var i=0; i < children.length; i++) {
			if (hui.dom.isElement(children[i])) {
				max = Math.max(max,children[i].clientHeight);
			}
		}
		return max;
	}
	if (window.scrollMaxY && window.innerHeight) {
		return window.scrollMaxY+window.innerHeight;
	} else {
		return Math.max(document.body.clientHeight,document.documentElement.clientHeight,document.documentElement.scrollHeight);
	}
}

//////////////////////////// Placement /////////////////////////

/**
 * Example hui.place({target : {element : myTarget, horizontal : 1}, source : {element : mySource, vertical : 0.5}})
 */
hui.place = function(options) {
	var left=0,top=0;
	var trgt = options.target.element;
	var trgtPos = {left:hui.getLeft(trgt),top:hui.getTop(trgt)};
	left = trgtPos.left+trgt.clientWidth*options.target.horizontal;
	top = trgtPos.top+trgt.clientHeight*options.target.vertical;
	
	var src = options.source.element;
	left-=src.clientWidth*options.source.horizontal;
	top-=src.clientHeight*options.source.vertical;
	
	if (options.insideViewPort) {
		var w = hui.getViewPortWidth();
		hui.log((left+src.clientWidth)+'>'+w);
		if (left+src.clientWidth>w) {
			left=w-src.clientWidth;
		}
		if (left<0) {left=0}
		if (top<0) {top=0}
	}
	
	src.style.top=top+'px';
	src.style.left=left+'px';
}

//////////////////////////// Preloader /////////////////////////

/** @constructor */
hui.Preloader = function(options) {
	this.options = options || {};
	this.delegate = {};
	this.images = [];
	this.loaded = 0;
}

hui.Preloader.prototype = {
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
hui.cookie = {
	set : function(name,value,days) {
		var expires;
		if (days) {
			var date = new Date();
			date.setTime(date.getTime()+(days*24*60*60*1000));
			expires = "; expires="+date.toGMTString();
		} else {
			expires = "";
		}
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

hui.URL = function(url) {
	this.url = url || '';
}

hui.URL.prototype = {
	addParameter : function(key,value) {
		this.url+=this.url.indexOf('?')!=-1 ? '&' : '?';
		this.url+=key+'='+(hui.isDefined(value) ? value : '');
	},
	toString : function() {
		return this.url;
	}
}

/* @namespace */
hui.location = {
	getParameter : function(name) {
		var parms = hui.location.getParameters();
		for (var i=0; i < parms.length; i++) {
			if (parms[i].name==name) {
				return parms[i].value;
			}
		};
		return null;
	},
	setParameter : function(name,value) {
		var parms = hui.location.getParameters();
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
		hui.location.setParameters(parms);
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
		var value = hui.location.getParameter(name);
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


hui.ani = hui.animate = function(element,style,value,duration,delegate) {
	hui.animation.get(element).animate(null,value,style,duration,delegate);
}

/** @namespace */
hui.animation = {
	objects : {},
	running : false,
	latestId : 0,
	get : function(element) {
		element = hui.get(element);
		if (!element.n2iAnimationId) {
			element.n2iAnimationId = this.latestId++;
		}
		if (!this.objects[element.n2iAnimationId]) {
			this.objects[element.n2iAnimationId] = new hui.animation.Item(element);
		}
		return this.objects[element.n2iAnimationId];
	},
	start : function() {
		if (!this.running) {
			hui.animation.render();
		}
	}
};

hui.animation.render = function(element) {
	this.running = true;
	var next = false;
	var stamp = new Date().getTime();
	for (var id in this.objects) {
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
				if (work.delegate && work.delegate.callback) {
					work.delegate.callback(obj.element,v);
				} else if (!work.css) {
					obj.element[work.property] = Math.round(work.from+(work.to-work.from)*v);
				} else if (work.property=='transform' && !hui.browser.msie) {
					var t = work.transform;
					var str = '';
					if (t.rotate) {
						str+=' rotate('+(t.rotate.from+(t.rotate.to-t.rotate.from)*v)+t.rotate.unit+')';
					}
					if (t.scale) {
						str+=' scale('+(t.scale.from+(t.scale.to-t.scale.from)*v)+')';
					}
					obj.element.style[hui.animation.TRANSFORM]=str;
				} else if (work.to.red!=null) {
					var red = Math.round(work.from.red+(work.to.red-work.from.red)*v);
					var green = Math.round(work.from.green+(work.to.green-work.from.green)*v);
					var blue = Math.round(work.from.blue+(work.to.blue-work.from.blue)*v);
					value = 'rgb('+red+','+green+','+blue+')';
					obj.element.style[work.property]=value;
				} else if (!hui.browser.opacity && work.property=='opacity') {
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
			hui.animation.render();
		},0);
	} else {
		this.running = false;
	}
}

hui.animation.parseStyle = function(value) {
	var parsed = {type:null,value:null,unit:null};
	var match;
	if (!hui.isDefined(value)) {
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
		var color = new hui.Color(value);
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

hui.animation.Item = function(element) {
	this.element = element;
	this.work = [];
}

hui.animation.Item.prototype.animate = function(from,to,property,duration,delegate) {
	var css = true;
	if (property=='scrollLeft' || property=='scrollTop') {
		css = false;
	}
	
	var work = this.getWork(css ? hui.string.camelize(property) : property);
	work.delegate = delegate;
	work.finished = false;
	work.css = css;
	if (from!=null) {
		work.from = from;
	} else if (property=='transform') {
		work.transform = hui.animation.Item.parseTransform(to,this.element);
	} else if (work.css && !hui.browser.opacity && property=='opacity') {
		work.from = this.getIEOpacity(this.element);
	} else if (work.css) {
		var style = hui.getStyle(this.element,property);
		var parsedStyle = hui.animation.parseStyle(style);
		work.from = parsedStyle.value;
	} else {
		work.from = this.element[property];
	}
	if (work.css) {
		var parsed = hui.animation.parseStyle(to);
		work.to = parsed.value;
		work.unit = parsed.unit;
	} else {
		work.to = to;
		work.unit = null;
	}
	work.start = new Date().getTime();
	if (delegate && delegate.delay) {
		work.start+=delegate.delay;
	}
	work.end = work.start+duration;
	hui.animation.start();
}

hui.animation.TRANSFORM = hui.browser.gecko ? 'MozTransform' : 'WebkitTransform';

hui.animation.Item.parseTransform = function(value,element) {
	var result = {};
	var from,fromMatch;
	var rotateReg = /rotate\(([0-9\.]+)([a-z]+)\)/i;
	var rotate = value.match(rotateReg);
	if (rotate) {
		from = 0;
		if (element.style[hui.animation.TRANSFORM]) {
			fromMatch = element.style[hui.animation.TRANSFORM].match(rotateReg);
			if (fromMatch) {
				from = parseFloat(fromMatch[1]);
			}
		}
		result.rotate = {from:from,to:parseFloat(rotate[1]),unit:rotate[2]};
	}
	var scaleReg = /scale\(([0-9\.]+)\)/i;
	var scale = value.match(scaleReg);
	if (scale) {
		from = 1;
		if (element.style[hui.animation.TRANSFORM]) {
			fromMatch = element.style[hui.animation.TRANSFORM].match(scaleReg);
			if (fromMatch) {
				from = parseFloat(fromMatch[1]);
			}
		}
		result.scale = {from:from,to:parseFloat(scale[1])};
	}
	
	return result;
}

hui.animation.Item.prototype.getIEOpacity = function(element) {
	var filter = hui.getStyle(element,'filter').toLowerCase();
	var match;
	if (match = filter.match(/opacity=([0-9]+)/)) {
		return parseFloat(match[1])/100;
	} else {
		return 1;
	}
}

hui.animation.Item.prototype.getWork = function(property) {
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

hui.animation.Loop = function(recipe) {
	this.recipe = recipe;
	this.position = -1;
	this.running = false;
}

hui.animation.Loop.prototype.next = function() {
	this.position++;
	if (this.position>=this.recipe.length) {
		this.position = 0;
	}
	var item = this.recipe[this.position];
	if (typeof(item)=='function') {
		item();
	} else if (item.element) {
		hui.ani(item.element,item.property,item.value,item.duration,{ease:item.ease});
	}
	var self = this;
	var time = item.duration || 0;
	window.setTimeout(function() {self.next()},time);
}

hui.animation.Loop.prototype.start = function() {
	this.running=true;
	this.next();
}

/** @constructor */
hui.Color = function(str) {
    this.ok = false;
	if (hui.isBlank(str)) {
		return;
	}
    // strip any leading #
    if (str.charAt(0) == '#') { // remove # if any
        str = str.substr(1,6);
    }

    str = str.replace(/ /g,'');
    str = str.toLowerCase();
		
    for (var key in hui.Color.table) {
        if (str == key) {
            str = hui.Color.table[key];
        }
    }
    // emd of simple type-in colors

    // array of color definition objects
    var color_defs = [
        {
            re: /^rgb\((\d{1,3}),\s*(\d{1,3}),\s*(\d{1,3})\)$/,
            process: function (bits){
                return [
                    parseInt(bits[1]),
                    parseInt(bits[2]),
                    parseInt(bits[3])
                ];
            }
        },
        {
            re: /^rgb\((\d{1,3})%,\s*(\d{1,3})%,\s*(\d{1,3})%\)$/	,
            process: function (bits){
                return [
                    Math.round(parseInt(bits[1])/100*255),
                    Math.round(parseInt(bits[2])/100*255),
                    Math.round(parseInt(bits[3])/100*255)
                ];
            }
        },
        {
            re: /^(\w{2})(\w{2})(\w{2})$/,
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
        var re = color_defs[i].re,
			processor = color_defs[i].process,
			bits = re.exec(str);
        if (bits) {
            channels = processor(bits);
            this.r = channels[0];
            this.g = channels[1];
            this.b = channels[2];
            this.ok = true;
			break;
        }
    }

    // validate/cleanup values
    this.r = (this.r < 0 || isNaN(this.r)) ? 0 : ((this.r > 255) ? 255 : this.r);
    this.g = (this.g < 0 || isNaN(this.g)) ? 0 : ((this.g > 255) ? 255 : this.g);
    this.b = (this.b < 0 || isNaN(this.b)) ? 0 : ((this.b > 255) ? 255 : this.b);
}

hui.Color.prototype = {
	toRGB : function () {
        return 'rgb(' + this.r + ', ' + this.g + ', ' + this.b + ')';
    },
	toHex : function() {
        var r = this.r.toString(16);
        var g = this.g.toString(16);
        var b = this.b.toString(16);
        if (r.length == 1) {
			r = '0' + r;
		}
        if (g.length == 1) {
			g = '0' + g;
		}
        if (b.length == 1) {
			b = '0' + b;
		}
        return '#' + r + g + b;
	}
}

hui.Color.table = {
	white : 'ffffff',
	black : '000000',
	red : 'ff0000',
	green : '00ff00',
	blue : '0000ff'
}

hui.Color.hex2rgb = function(hex) {
	if (hui.isBlank(hex)) {
		return null;
	}
	if (hex[0]=="#") {
		hex=hex.substr(1);
	}
	if (hex.length==3) {
		var temp=hex;
		hex='';
		temp = /^([a-f0-9])([a-f0-9])([a-f0-9])$/i.exec(temp).slice(1);
		for (var i=0;i<3;i++) {
			hex+=temp[i]+temp[i];
		}
	}
	var triplets = /^([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})$/i.exec(hex).slice(1);
	return {
		r:   parseInt(triplets[0],16),
		g: parseInt(triplets[1],16),
		b:  parseInt(triplets[2],16)
	}
}

hui.Color.hsv2rgb = function (Hdeg,S,V) {
  	var H = Hdeg/360,R,G,B;     // convert from degrees to 0 to 1
  	if (S==0) {       // HSV values = From 0 to 1
		R = V*255;     // RGB results = From 0 to 255
		G = V*255;
		B = V*255;
	} else {
    	var h = H*6,
			var_r,var_g,var_b;
    	var i = Math.floor( h );
    	var var_1 = V*(1-S);
    	var var_2 = V*(1-S*(h-i));
    	var var_3 = V*(1-S*(1-(h-i)));
    	if (i==0) {
			var_r=V ;
			var_g=var_3;
			var_b=var_1
		}
    	else if (i==1) {
			var_r=var_2;
			var_g=V;
			var_b=var_1
		}
    	else if (i==2) {var_r=var_1; var_g=V;     var_b=var_3}
    	else if (i==3) {var_r=var_1; var_g=var_2; var_b=V}
    	else if (i==4) {var_r=var_3; var_g=var_1; var_b=V}
    	else {var_r=V;     var_g=var_1; var_b=var_2}
    	R = Math.round(var_r*255);   //RGB results = From 0 to 255
    	G = Math.round(var_g*255);
    	B = Math.round(var_b*255);
  	}
  	return new Array(R,G,B);
}

hui.Color.rgb2hsv = function(r, g, b) {

    r = (r / 255);
    g = (g / 255);
	b = (b / 255);	

    var min = Math.min(Math.min(r, g), b),
        max = Math.max(Math.max(r, g), b),
		value = max,
        saturation,
        hue;

    // Hue
    if (max == min) {
        hue = 0;
    } else if (max == r) {
        hue = (60 * ((g-b) / (max-min))) % 360;
    } else if (max == g) {
        hue = 60 * ((b-r) / (max-min)) + 120;
    } else if (max == b) {
        hue = 60 * ((r-g) / (max-min)) + 240;
    }

    if (hue < 0) {
        hue += 360;
    }

    // Saturation
    if (max == 0) {
        saturation = 0;
    } else {
        saturation = 1 - (min/max);
    }

    return [Math.round(hue), Math.round(saturation * 100), Math.round(value * 100)];
}

hui.Color.rgb2hex = function(rgbary) {
	var c = '#';
  	for (var i=0; i < 3; i++) {
		var str = parseInt(rgbary[i]).toString(16);
    	if (str.length < 2) {
			str = '0'+str;
		}
		c+=str;
  	}
  	return c;
}


/** @namespace */
hui.ease = {
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
		return 1 - hui.ease.elastic2(1-t);
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
	flicker : function(value) {
		if (value==1) return 1;
		return Math.random()*value;
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
		return (1 - hui.ease.bounceOut(1-n)); // Decimal
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
		if(n<0.5){ return hui.ease.bounceIn(n*2) / 2; }
		return (hui.ease.bounceOut(n*2-1) / 2) + 0.5; // Decimal
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
}

if (!Function.prototype.argumentNames) {
	Function.prototype.argumentNames = function() {
		var names = this.toString().match(/^[\s\(]*function[^(]*\(([^)]*)\)/)[1]
			.replace(/\/\/.*?[\r\n]|\/\*(?:.|[\r\n])*?\*\//g, '')
			.replace(/\s+/g, '').split(',');
		return names.length == 1 && !names[0] ? [] : names;
	}
}