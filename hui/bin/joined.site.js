/** @namespace */
window.hui = window.hui || {};



(function(hui,agent,window) {
  hui.KEY_BACKSPACE = 8;
  hui.KEY_TAB = 9;
  hui.KEY_RETURN = 13;
  hui.KEY_ESC = 27;
  hui.KEY_LEFT = 37;
  hui.KEY_UP = 38;
  hui.KEY_RIGHT = 39;
  hui.KEY_DOWN = 40;
  hui.KEY_DELETE = 46;
  hui.KEY_HOME = 36;
  hui.KEY_END = 35;
  hui.KEY_PAGEUP = 33;
  hui.KEY_PAGEDOWN = 34;
  hui.KEY_INSERT = 45;

  var browser = hui.browser = {};
  
	/** If the browser is any version of InternetExplorer */
	browser.msie = !/opera/i.test(agent) && /MSIE/.test(agent) || /Trident/.test(agent);
	/** If the browser is InternetExplorer 6 */
	browser.msie6 = agent.indexOf('MSIE 6') !== -1;
	/** If the browser is InternetExplorer 7 */
	browser.msie7 = agent.indexOf('MSIE 7') !== -1;
	/** If the browser is InternetExplorer 8 */
	browser.msie8 = agent.indexOf('MSIE 8') !== -1;
	/** If the browser is InternetExplorer 9 */
	browser.msie9 = agent.indexOf('MSIE 9') !== -1;
	/** If the browser is InternetExplorer 9 in compatibility mode */
	browser.msie9compat = browser.msie7 && agent.indexOf('Trident/5.0') !== -1;
	/** If the browser is InternetExplorer 10 */
	browser.msie10 = agent.indexOf('MSIE 10') !== -1;
	/** If the browser is InternetExplorer 11 */
	browser.msie11 = agent.indexOf('Trident/7.0') !== -1;
	/** If the browser is WebKit based */
	browser.webkit = agent.indexOf('WebKit') !== -1;
	/** If the browser is any version of Safari */
	browser.safari = agent.indexOf('Safari') !== -1;
	/** If the browser is any version of Chrome */
	//browser.chrome = agent.indexOf('Chrome') !== -1;
	/** The version of WebKit (null if not WebKit) */
	browser.webkitVersion = null;
	/** If the browser is Gecko based */
	browser.gecko = !browser.webkit && !browser.msie && agent.indexOf('Gecko') !== -1;
	/** If the browser is Gecko based */
	//browser.chrome = agent.indexOf('Chrome') !== -1;
	/** If the browser is safari on iPad */
	browser.ipad = browser.webkit && agent.indexOf('iPad') !== -1;
	/** If the browser is on Windows */
	browser.windows = agent.indexOf('Windows') !== -1;

	/** If the browser supports CSS opacity */
	browser.opacity = !browser.msie6 && !browser.msie7 && !browser.msie8;
	/** If the browser supports CSS Media Queries */
	browser.mediaQueries = browser.opacity;
	/** If the browser supports CSS animations */
	browser.animation = !browser.msie6 && !browser.msie7 && !browser.msie8 && !browser.msie9;

	browser.wordbreak = !browser.msie6 && !browser.msie7 && !browser.msie8;

	browser.touch = (!!('ontouchstart' in window) || (!!('onmsgesturechange' in window) && !!window.navigator.maxTouchPoints)) ? true : false;

	var result = /Safari\/([\d.]+)/.exec(agent);
	if (result) {
		browser.webkitVersion = parseFloat(result[1]);
	}
})(hui,navigator.userAgent,window);



////////////////////// Common ////////////////////////

/**
 * Log something
 * @param {Object} obj The object to log
 */
hui.log = function(obj) {
	if (window.console && window.console.log) {
		if (arguments.length==1) {
			console.log(obj);
		}
		else if (arguments.length==2) {
			console.log(arguments[0],arguments[1]);
		} else {
			console.log(arguments);			
		}
	}
};

/**
 * Defer a function so it will fire when the current "thread" is done
 * @param {Function} func The function to defer
 * @param {Object} ?bind Optional, the object to bind "this" to
 */
hui.defer = function(func,bind) {
	if (bind) {
		func = func.bind(bind);
	}
	window.setTimeout(func);
};

hui.extend = function(subClass, superClass) {
  var methods = subClass.prototype;
  for (var p in superClass) {
    if (superClass.hasOwnProperty(p)) {
      subClass[p] = superClass[p];
    }
  }
  function __() { this.constructor = subClass; }
  __.prototype = superClass.prototype;
  subClass.prototype = new __();
  if (methods) {
    for (var p in methods) {
      subClass.prototype[p] = methods[p];
    }    
  }
};

/**
 * Override the properties on the first argument with properties from the last object
 * @param {Object} original The object to override
 * @param {Object} subject The object to copy the properties from
 * @return {Object} The original
 */
hui.override = function(original,subject) {
	if (subject) {
		for (var prop in subject) {
			original[prop] = subject[prop];
		}
	}
	return original;
};

/**
 * Loop through items in array or properties in an object.
 * If «items» is an array «func» is called with each item.
 * If «items» is an object «func» is called with each (key,value)
 * @param {Object | Array} items The object or array to loop through
 * @param {Function} func The callback to handle each item
 */
hui.each = function(items,func) {
    var i;
	if (hui.isArray(items)) {		
		for (i = 0; i < items.length; i++) {
			func(items[i],i);
		}
    } else if (items instanceof NodeList) {
		for (i = 0; i < items.length; i++) {
			func(items.item(i),i);
		}
	} else {
		for (var key in items) {
			func(key,items[key]);
		}
	}
};

/**
 * Return text if condition is met
 * @param {Object} condition The condition to test
 * @param {String} text The text to return when condition evaluates to true
 */
hui.when = function(condition,text) {
	return condition ? text : '';
};

/**
 * Converts a string to an int if it is only digits, otherwise remains a string
 * @param {String} str The string to convert
 * @returns {Object} An int of the string or the same string
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
};

/** 
 * Make sure a number is between a min / max
 */
hui.between = function(min,value,max) {
	var result = Math.min(max,Math.max(min,value));
	return isNaN(result) ? min : result;
};

/**
 * Fit a box inside a container while preserving aspect ratio (note: expects sane input)
 * @param {Object} box The box to scale {width : 200, height : 100}
 * @param {Object} container The container to fit the box inside {width : 20, height : 40}
 * @returns {Object} An object of the new box {width : 20, height : 10}
 */
hui.fit = function(box,container,options) {
  options = options || {};
  var boxRatio = box.width / box.height;
  var containerRatio = container.width / container.height;
  var width, height;
  if (options.upscale===false && box.width<=container.width && box.height<=container.height) {
    width = box.width;
    height = box.height;
  }
  else if (boxRatio > containerRatio) {
    width = container.width;
    height = Math.round(container.width/box.width * box.height);
  } else {
    width = Math.round(container.height/box.height * box.width);
    height = container.height;
  }
  return {width : width, height : height};
}

/**
 * Checks if a string has non-whitespace characters
 * @param {String} str The string
 */
hui.isBlank = function(str) {
	if (str===null || typeof(str)==='undefined' || str==='') {
		return true;
	}
	return typeof(str)=='string' && hui.string.trim(str).length === 0;
};

/**
 * Checks that an object is not null and not undefined
 * @param {Object} obj The object to check
 */
hui.isDefined = function(obj) {
	return obj!==null && typeof(obj)!=='undefined';
};



/**
 * Checks if an object is a string
 * @param {Object} obj The object to check
 */
hui.isString = function(obj) {
	return typeof(obj)==='string';
};

/**
 * Checks if an object is an array
 * @param {Object} obj The object to check
 */
hui.isArray = function(obj) {
	if (obj === null || obj === undefined) {
		return false;
	}
	if (obj.constructor == Array) {
		return true;
	} else {
		return Object.prototype.toString.call(obj) === '[object Array]';
	}
};

///////////////////////// Strings ///////////////////////

/** @namespace */
hui.string = {
	
	/**
	 * Test that a string start with another string
	 * @param {String} str The string to test
	 * @param {String} start The string to look for at the start
	 * @returns {Boolean} True if «str» starts with «start»
	 */
	startsWith : function(str,start) {
		if (typeof(str) !== 'string' || typeof(start) !== 'string') {
			return false;
		}
		return (str.match("^"+start)==start);
	},
	/**
	 * Test that a string ends with another string
	 * @param {String} str The string to test
	 * @param {String} end The string to look for at the end
	 * @returns {Boolean} True if «str» ends with «end»
	 */
	endsWith : function(str,end) {
		if (typeof(str) !== 'string' || typeof(end) !== 'string') {
			return false;
		}
		return (str.match(end+"$")==end);
	},
	
	/** 
	 * Make a string camelized
	 * @param {String} The string to camelize
	 * @returns {String} The camelized string
	 */
	camelize : function(str) {
		if (str.indexOf('-')==-1) {
            return str;
        }
	    var oStringList = str.split('-');

	    var camelizedString = str.indexOf('-') === 0 ? oStringList[0].charAt(0).toUpperCase() + oStringList[0].substring(1) : oStringList[0];

	    for (var i = 1, len = oStringList.length; i < len; i++) {
	      var s = oStringList[i];
	      camelizedString += s.charAt(0).toUpperCase() + s.substring(1);
	    }

	    return camelizedString;
	},
	/**
 	 * Trim whitespace including unicode chars
	 * @param {String} str The text to trim
	 * @returns {String} The trimmed text
	 */
	trim : function(str) {
		if (str===null || str===undefined) {
			return '';
		}
		if (typeof(str) != 'string') {
			str = String(str);
		}
		return str.replace(/^[\s\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000]+|[\s\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000]+$/g, '');
	},
	/**
	 * Inserts invisible break chars in string so it will wrap
	 * @param {String} str The text to wrap
	 * @returns {String} The wrapped text
	 */
	wrap : function(str) {
		if (str===null || str===undefined) {
			return '';
		}
		return str.split('').join("\u200B");
	},
	/**
	 * Shorten a string to a maximum length
	 * @param {String} str The text to shorten
	 * @param {int} length The maximum length
	 * @returns {String} The shortened text, '' if undefined or null string
	 */
	shorten : function(str,length) {
		if (!hui.isDefined(str)) {return '';}
		if (str.length > length) {
			return str.substring(0,length-3) + '...';
		}
		return str;
	},
	/**
	 * Escape the html in a string (robust)
	 * @param {String} str The text to escape
	 * @returns {String} The escaped text
	 */
	escapeHTML : function(str) {
		if (str===null || str===undefined) {return '';}
	   	return hui.build('div',{text:str}).innerHTML;
	},
	/**
	 * Escape the html in a string (fast)
	 * @param {String} str The text to escape
	 * @returns {String} The escaped text
	 */
	escape : function(str) {
		if (!hui.isString(str)) {return str};
		var tagsToReplace = {
	        '&': '&amp;',
	        '<': '&lt;',
	        '>': '&gt;',
	        '"': '&quot;',
		    "'": '&#x27;',
		    '`': '&#x60;'
	    };
	    return str.replace(/[&<>'`"]/g, function(tag) {
	        return tagsToReplace[tag] || tag;
	    });
	},
	/**
	 * Converts a JSON string into an object
	 * @param json {String} The JSON string to parse
	 * @returns {Object} The object
	 */
	fromJSON : function(json) {
		try {
			return JSON.parse(json);
		} catch (e) {
			hui.log(e);
			return null;
		}
	},

	/**
	 * Converts an object into a JSON string
	 * @param obj {Object} the object to convert
	 * @returns {String} A JSON representation
	 */
	toJSON : function(obj) {
		return JSON.stringify(obj);
	}
};







//////////////////////// Array //////////////////////////

/** @namespace */
hui.array = {
	/**
	 * Add an object to an array if it not already exists
	 * @param {Array} arr The array
	 * @param {Object} value The object to add
	 */
	add : function(arr,value) {
		if (value.constructor==Array) {
			for (var i=0; i < value.length; i++) {
				if (!hui.array.contains(arr,value[i])) {
					arr.push(value);
				}
			}
		} else {
			if (!hui.array.contains(arr,value)) {
				arr.push(value);
			}
		}
	},
	/**
	 * Check if an array contains a value
	 * @param {Array} arr The array
	 * @param {Object} value The object to check for
	 * @returns {boolean} true if the value is in the array
	 */
	contains : function(arr,value) {
		return hui.array.indexOf(arr,value) !== -1;
	},
	/**
	 * Add or remove a value from an array.
	 * If the value exists all instances are removed, otherwise the value is added
	 * @param {Array} arr The array to change
	 * @param {Object} value The value to flip
	 */
	flip : function(arr,value) {
		if (hui.array.contains(arr,value)) {
			hui.array.remove(arr,value);
		} else {
			arr.push(value);
		}
	},
	/**
	 * Remove all instances of a value from an array
	 * @param {Array} arr The array to change
	 * @param {Object} value The value to remove
	 */
	remove : function(arr,value) {
		for (var i = arr.length - 1; i >= 0; i--){
			if (arr[i]==value) {
				arr.splice(i,1);
			}
		}
	},
	/**
	 * Find the first index of a value in an array, -1 if not found
	 * @param {Array} arr The array to inspect
	 * @param {Object} value The value to find
	 * @returns {Number} The index of the first occurrence, -1 if not found.
	 */
	indexOf : function(arr,value) {
		for (var i=0; i < arr.length; i++) {
			if (arr[i]===value) {
				return i;
			}
		}
		return -1;
	},
	/**
	 * Split a string, like "1,4,6" into an array of integers.
	 * @param {String} The string to split
	 * @returns {Array} An array of integers
	 */
	toIntegers : function(str) {
		var array = str.split(',');
		for (var i = array.length - 1; i >= 0; i--){
			array[i] = parseInt(array[i],10);
		}
		return array;
	}
};










////////////////////// DOM ////////////////////

/** @namespace */
hui.dom = {
	isElement : function(node,name) {
		return node.nodeType==1 && (name===undefined ? true : node.nodeName.toLowerCase()==name);
	},
	isDefinedText : function(node) {
		return node.nodeType==3 && node.nodeValue.length>0;
	},
	addText : function(node,text) {
		node.appendChild(document.createTextNode(text));
	},
	// TODO: Move to hui.get
	firstChild : function(node) {
		var children = node.childNodes;
		for (var i=0; i < children.length; i++) {
			if (children[i].nodeType==1) {
				return children[i];
			}
		}
		return null;
	},
	parse : function(html) {
		var dummy = hui.build('div',{html:html});
		return hui.get.firstChild(dummy);
	},
	clear : function(node) {
		var children = node.childNodes;
		for (var i = children.length - 1; i >= 0; i--) {
			children[i].parentNode.removeChild(children[i]);
		}
	},
	remove : function(node) {
		if (node.parentNode) {
			node.parentNode.removeChild(node);
		}
	},
	replaceNode : function(oldNode,newNode) {
		if (newNode.parentNode) {
			newNode.parentNode.removeChild(newNode);
		}
		oldNode.parentNode.insertBefore(newNode,oldNode);
		oldNode.parentNode.removeChild(oldNode);
	},
	changeTag : function(node,tagName) {
		var replacement = hui.build(tagName);
	
		// Copy the children
		while (node.firstChild) {
		    replacement.appendChild(node.firstChild); // *Moves* the child
		}

		// Copy the attributes
		for (var i = node.attributes.length - 1; i >= 0; --i) {
		    replacement.attributes.setNamedItem(node.attributes[i].cloneNode());
		}

		// Insert it
		node.parentNode.insertBefore(replacement, node);

		// Remove the wns
		node.parentNode.removeChild(node);
		return replacement;
	},
	insertBefore : function(target,newNode) {
		target.parentNode.insertBefore(newNode,target);
	},
	insertAfter : function(target,newNode) {
		var next = target.nextSibling;
		if (next) {
			next.parentNode.insertBefore(newNode,next);			
		} else {
			target.parentNode.appendChild(newNode);
		}
	},
	replaceHTML : function(node,html) {
		node = hui.get(node);
		node.innerHTML = html;
	},
	runScripts : function(node) {
		if (hui.dom.isElement(node)) {
			if (hui.dom.isElement(node,'script')) {
				eval(node.innerHTML);
			} else {
				var scripts = node.getElementsByTagName('script');
				for (var i=0; i < scripts.length; i++) {
					eval(scripts[i].innerHTML);
				}
			}
		}
	},
	setText : function(node,text) {
		if (text===undefined || text===null) {
			text = '';
		}
		var c = node.childNodes;
		var updated = false;
		for (var i = c.length - 1; i >= 0; i--){
			if (!updated && c[i].nodeType === 3) {
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
			if (c[i].nodeType === 3 && c[i].nodeValue !== null) {
				txt+= c[i].nodeValue;
			} else if (c[i].nodeType == 1) {
				txt+= hui.dom.getText(c[i]);
			}
		}
		return txt;
	},
	isVisible : function(node) {
		while (node) {
			if (node.style && (hui.style.get(node,'display')==='none' || hui.style.get(node,'visibility')==='hidden')) {
				return false;
			}
			node = node.parentNode;
		}
		return true;
	},
 	isDescendantOrSelf : function(element,parent) {
		while (element) {
			if (element==parent) {
				return true;
			}
			element = element.parentNode;
		}
		return false;
	}
};









///////////////////// Form //////////////////////

/** @namespace */
hui.form = {
	getValues : function(node) {
		var params = {};
		var inputs = node.getElementsByTagName('input');
		for (var i=0; i < inputs.length; i++) {
			if (hui.isDefined(inputs[i].name)) {
				params[inputs[i].name] = inputs[i].value;
			}
		}
		return params;
	}
};







///////////////////////////// Quering ////////////////////////

/**
 * @namespace
 * Functions for finding elements
 *
 * @function
 * Get an element by ID. If the ID is not a string it is returned.
 * @param {String | Element} id The ID to find
 * @returns {Element} The element with the ID or null
 */
hui.get = function(id) {
	if (typeof(id)=='string') {
		return document.getElementById(id);
	}
	return id;
};

/**
 * Get array of child elements of «node», not a NodeList
 */
hui.get.children = function(node) {
	var children = [];
	var x = node.childNodes;
	for (var i=0; i < x.length; i++) {
		if (hui.dom.isElement(x[i])) {
			children.push(x[i]);
		}
	}
	return children;
};

hui.get.ancestors = function(element) {
	var ancestors = [];
	var parent = element.parentNode;
	while (parent) {
		ancestors[ancestors.length] = parent;
		parent = parent.parentNode;
	}
	return ancestors;
};

/**
 * Find the first ancestor with a given class (including self)
 */
hui.get.firstAncestorByClass = function(element,className) {
	while (element) {
		if (hui.cls.has(element,className)) {
			return element;
		}
		element = element.parentNode;
	}
	return null;
};

hui.get.next = function(element) {
	if (!element) {
		return null;
	}
	if (element.nextElementSibling) {
		return element.nextElementSibling;
	}
	if (!element.nextSibling) {
		return null;
	}
	var next = element.nextSibling;
	while (next && next.nodeType!=1) {
		next = next.nextSibling;
	}
	if (next && next.nodeType==1) { 
    	return next;
	}
	return null;
};

hui.get.previous = function(element) {
	if (!element) {
		return null;
	}
	if (element.previousElementSibling) {
		return element.previousElementSibling;
	}
	if (!element.previousSibling) {
		return null;
	}
	var previous = element.previousSibling;
	while (previous && previous.nodeType!=1) {
		previous = previous.previousSibling;
	}
	if (previous && previous.nodeType==1) { 
    	return previous;
	}
	return null;
};

hui.get.before = function(element) {
	var elements = [];
	if (element) {
		var nodes = element.parentNode.childNodes;
		for (var i=0; i < nodes.length; i++) {
			if (nodes[i]==element) {
				break;
			} else if (nodes[i].nodeType===1) {
				elements.push(nodes[i]);
			}
		}
	}
	return elements;
};

/**
 * Find all sibling elements after «element»
 */ 
hui.get.after = function(element) {
	var elements = [];
	var next = hui.get.next(element);
	while (next) {
		elements.push(next);
		next = hui.get.next(next);
	}
	return elements;
};

hui.get.firstByClass = function(parentElement,className,tag) {
	parentElement = hui.get(parentElement) || document.body;
  if (parentElement.querySelector) {
		return parentElement.querySelector((tag ? tag+'.' : '.')+className);
	} else {
		var children = parentElement.getElementsByTagName(tag || '*');
		for (var i=0;i<children.length;i++) {
			if (hui.cls.has(children[i],className)) {
				return children[i];
			}
		}
	}
	return null;
};

hui.get.byClass = function(parentElement,className,tag) {
	parentElement = hui.get(parentElement) || document.body;
    var i;
	if (parentElement.querySelectorAll) {
		var nl = parentElement.querySelectorAll((tag ? tag+'.' : '.')+className);
		// Important to convert into array...
		var l=[];
		for(i=0, ll=nl.length; i!=ll; l.push(nl[i++]));
		return l;
	} else {
		var children = parentElement.getElementsByTagName(tag || '*'),
		out = [];
		for (i=0;i<children.length;i++) {
			if (hui.cls.has(children[i],className)) {
				out[out.length] = children[i];
			}
		}
		return out;
	}
};

/**
 * Get array of descendants of «node» with the name «name»
 * @param node The node to start from
 * @param name The name of the nodes to find
 * @returns An array of nodes (not NodeList)
 */
hui.get.byTag = function(node,name) {
	var nl = node.getElementsByTagName(name),
		l=[];
	for(var i=0, ll=nl.length; i!=ll; l.push(nl[i++]));
	return l;
};

hui.get.byId = function(e,id) {
	var children = e.childNodes;
	for (var i = children.length - 1; i >= 0; i--) {
		if (children[i].nodeType===1 && children[i].getAttribute('id')===id) {
			return children[i];
		} else {
			var found = hui.get.byId(children[i],id);
			if (found) {
				return found;
			}
		}
	}
	return null;
};

hui.get.firstParentByTag = function(node,tag) {
	var parent = node;
	while (parent) {
		if (parent.tagName && parent.tagName.toLowerCase()==tag) {
			return parent;
		}
		parent = parent.parentNode;
	}
	return null;
};

hui.get.firstParentByClass = function(node,tag) {
	var parent = node;
	while (parent) {
		if (hui.cls.has(parent)) {
			return parent;
		}
		parent = parent.parentNode;
	}
	return null;
};

/**
 * Find first descendant by tag (excluding self)
 * @param {Element} node The node to start from, will start from body if null
 * @param {String} tag The name of the node to find
 * @returns {Element} The found element or null
 */
hui.get.firstByTag = function(node,tag) {
	node = hui.get(node) || document.body;
	if (node.querySelector && tag!=='*') {
		return node.querySelector(tag);
	}
	var children = node.getElementsByTagName(tag);
	return children[0];
};


hui.get.firstChild = hui.dom.firstChild;

hui.find = function(selector,context) {
	return (context || document).querySelector(selector);
}

hui.findAll = function(selector,context) {
	var nl = (context || document).querySelectorAll(selector),
    l=[];
  for(var i=0, ll=nl.length; i!=ll; l.push(nl[i++]));
  return l;
}

if (!document.querySelector) {
  hui.find = function(selector,context) {
    context = context || document.documentElement;
    if (selector[0] == '.') {
      return hui.get.firstByClass(context,selector.substr(1));
    } else {
      return hui.get.firstByTag(context,selector);
    }
  }
}

hui.collect = function(selectors,context) {
	var copy = {};
	for (key in selectors) {
		copy[key] = hui.find(selectors[key],context);
	}
	return copy;
}







//////////////////////// Elements ///////////////////////////


/**
 * Builds an element with the «name» and «options»
 *
 * @param {String} name The name of the new element (. adds class)
 * @param {Object} options The options
 * @param {String} options.html Inner HTML
 * @param {String} options.text Inner text
 * @param {String} options.className
 * @param {String} options.class
 * @param {Object} options.style Map of styles (see: hui.style.set)
 * @param {Element} options.parent
 * @param {Element} options.parentFirst
 * @param {Document} doc (Optional) The document to create the element for
 * @returns {Element} The new element
 */
hui.build = function(name,options,doc) {
	doc = doc || document;
  var cls = '';
	if (name.indexOf('.') !== -1) {
	  var split = name.split('.');
    name = split[0];
    for (var i = 1; i < split.length; i++) {
      if (i>1) {cls+=' '};
      cls+=split[i];
    }
	}
  var e = doc.createElement(name);
  if (cls) {
    e.className = cls;
  }
	if (options) {
		for (var prop in options) {
			if (prop=='text') {
				e.appendChild(doc.createTextNode(options.text));
			} else if (prop=='html') {
				e.innerHTML=options.html;
			} else if (prop=='parent' && hui.isDefined(options.parent)) {
				options.parent.appendChild(e);
			} else if (prop=='parentFirst') {
				if (options.parentFirst.childNodes.length === 0) {
					options.parentFirst.appendChild(e);
				} else {
					options.parentFirst.insertBefore(e,options.parentFirst.childNodes[0]);
				}
			} else if (prop=='before') {
				options.before.parentNode.insertBefore(e,options.before);
			} else if (prop=='className') {
				e.className=options.className;
			} else if (prop=='class') {
				e.className=options['class'];
			} else if (prop=='style' && typeof(options[prop])=='object') {
				hui.style.set(e,options[prop]);
			} else if (prop=='style' && (hui.browser.msie7 || hui.browser.msie6)) {
				e.style.setAttribute('cssText',options[prop]);
			} else if (hui.isDefined(options[prop])) {
				e.setAttribute(prop,options[prop]);
			}
		}
	}
	return e;
};








/////////////////////// Position ///////////////////////

/** @namespace
 * Functions for getting and changing the position of elements
 */
hui.position = {
	getTop : function(element) {
	    element = hui.get(element);
		if (element) {
			var top = element.offsetTop,
				tempEl = element.offsetParent;
			while (tempEl !== null) {
				top += tempEl.offsetTop;
				tempEl = tempEl.offsetParent;
			}
			return top;
		}
		else return 0;
	},
	getLeft : function(element) {
	    element = hui.get(element);
		if (element) {
			var left = element.offsetLeft,
				tempEl = element.offsetParent;
			while (tempEl !== null) {
				left += tempEl.offsetLeft;
				tempEl = tempEl.offsetParent;
			}
			return left;
		}
		else return 0;
	},
	get : function(element) {
		return {
			left : hui.position.getLeft(element),
			top : hui.position.getTop(element)
		};
	},
	getScrollOffset : function(element) {
	    element = hui.get(element);
		var top = 0, left = 0;
	    do {
	      top += element.scrollTop  || 0;
	      left += element.scrollLeft || 0;
	      element = element.parentNode;
		  if (element && element.tagName === 'HTML') {
			  break; // TODO Temporary hack - Chrome has the same scrollTop on html as on body
		  }
	    } while (element);
		return {top:top,left:left};
	},
	/**
	 * Place on element relative to another
	 * Example hui.position.place({target : {element : «node», horizontal : «0-1»}, source : {element : «node», vertical : «0 - 1»}, insideViewPort:«boolean», viewPortMargin:«integer»})
	 */
	place : function(options) {
		var left = 0,
			top = 0,
			src = hui.get(options.source.element),
			trgt = hui.get(options.target.element),
			trgtPos = {left : hui.position.getLeft(trgt), top : hui.position.getTop(trgt) };

		left = trgtPos.left + trgt.clientWidth * (options.target.horizontal || 0);
		top = trgtPos.top + trgt.clientHeight * (options.target.vertical || 0);

		left -= src.clientWidth * (options.source.horizontal || 0);
		top -= src.clientHeight * (options.source.vertical || 0);

		if (options.top) {
			top += options.top;
		}
		if (options.left) {
			left += options.left;
		}

		if (options.insideViewPort) {
			var w = hui.window.getViewWidth();
			if (left + src.clientWidth > w) {
				left = w - src.clientWidth - (options.viewPartMargin || 0);
				//hui.log(options.viewPartMargin)
			}
			if (left < 0) {left=0;}
			if (top < 0) {top=0;}
			
			var height = hui.window.getViewHeight();
			var vertMax = hui.window.getScrollTop()+hui.window.getViewHeight()-src.clientHeight,
				vertMin = hui.window.getScrollTop();
			top = Math.max(Math.min(top,vertMax),vertMin);
		}
		src.style.top = Math.round(top)+'px';
		src.style.left = Math.round(left)+'px';
	},
	/** Get the remaining height within parent when all siblings has used their height */
	getRemainingHeight : function(element) {
		var height = element.parentNode.clientHeight;
		var siblings = element.parentNode.childNodes;
		for (var i=0; i < siblings.length; i++) {
			var sib = siblings[i];
			if (sib!==element && hui.dom.isElement(siblings[i])) {
				if (hui.style.get(sib,'position')!='absolute') {
					height-=sib.offsetHeight;
				}
			}
		}
		return height;
	}
};







////////////////////// Window /////////////////////

/** @namespace */
hui.window = {
	getScrollTop : function() {
		if (window.pageYOffset) {
			return window.pageYOffset;
		} else if (document.documentElement && document.documentElement.scrollTop) {
			return document.documentElement.scrollTop;
		} else if (document.body) {
			return document.body.scrollTop;
		}
		return 0;
	},
	getScrollLeft : function() {
		if (window.pageYOffset) {
			return window.pageXOffset;
		} else if (document.documentElement && document.documentElement.scrollTop) {
			return document.documentElement.scrollLeft;
		} else if (document.body) {
			return document.body.scrollLeft;
		}
		return 0;
	},
	/**
	 * Scroll to an element, will try to show the element in the middle of the screen and only scroll if it makes sence
	 * @param {Object} options {element:«the element to scroll to»,duration:«milliseconds»,top:«disregarded pixels at top»}
	 */
	scrollTo : function(options) {
		options = hui.override({duration:0,top:0},options);
		var node = options.element;
		var pos = hui.position.get(node);
		var viewTop = hui.window.getScrollTop();
		var viewHeight = hui.window.getViewHeight();
		var viewBottom = viewTop+viewHeight;
		if (viewTop < pos.top + node.clientHeight || (pos.top)<viewBottom) {
			var top = pos.top - Math.round((viewHeight - node.clientHeight) / 2);
			top-= options.top/2;
			top = Math.max(0, top);
		
			var startTime = new Date().getTime();
			var func;
		
			func = function() {
				var pos = (new Date().getTime()-startTime)/options.duration;
				if (pos>1) {
					pos = 1;
				}
				window.scrollTo(0, viewTop+Math.round((top-viewTop)*hui.ease.fastSlow(pos)));
				if (pos<1) {
					window.setTimeout(func);
				}
			};
			func();
		}
	},
	/**
	 * Get the height of the viewport (the visible part of the page)
	 */
	getViewHeight : function() {
		if (window.innerHeight) {
			return window.innerHeight;
		} else if (document.documentElement && document.documentElement.clientHeight) {
			return document.documentElement.clientHeight;
		} else if (document.body) {
			return document.body.clientHeight;
		}
	},
	/**
	 * Get the width of the viewport (the visible part of the page)
	 */
	getViewWidth : function() {
		if (window.innerWidth) {
			return window.innerWidth;
		} else if (document.documentElement && document.documentElement.clientWidth) {
			return document.documentElement.clientWidth;
		} else if (document.body) {
			return document.body.clientWidth;
		}
	}
};













/////////////////////////// Class handling //////////////////////

/** @namespace */
hui.cls = {
	/**
	 * Check if an element has a class
	 * @param {Element} element The element
	 * @param {String} className The class
	 * @returns {boolean} true if the element has the class 
	 */
	has : function(element, className) {
		element = hui.get(element);
		if (!element || !element.className) {
			return false;
		}
		if (element.hasClassName) {
			return element.hasClassName(className);
		}
		if (element.className==className) {
			return true;
		}
    if (element.className.animVal!==undefined) {
      return false; // TODO (handle SVG stuff)
    }
		var a = element.className.split(/\s+/);
		for (var i = 0; i < a.length; i++) {
			if (a[i] == className) {
				return true;
			}
		}
		return false;
	},
	/**
	 * Add a class to an element
	 * @param {Element} element The element to add the class to
	 * @param {String} className The class
	 */
	add : function(element, className) {
	    element = hui.get(element);
		if (!element) {
			return;
		}
		if (element.addClassName) {
			element.addClassName(className);
		}
	    hui.cls.remove(element, className);
	    element.className += ' ' + className;
	},
	/**
	 * Remove a class from an element
	 * @param {Element} element The element to remove the class from
	 * @param {String} className The class
	 */
	remove : function(element, className) {
		element = hui.get(element);
		if (!element || !element.className) {return;}
		if (element.removeClassName) {
			element.removeClassName(className);
		}
		if (element.className==className) {
			element.className='';
			return;
		}
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
	},
	/**
	 * Add or remove a class from an element
	 * @param {Element} element The element
	 * @param {String} className The class
	 */
	toggle : function(element,className) {
		if (hui.cls.has(element,className)) {
			hui.cls.remove(element,className);
		} else {
			hui.cls.add(element,className);
		}
	},
	/**
	 * Add or remove a class from an element
	 * @param {Element} element The element
	 * @param {String} className The class
	 * @param {boolean} add If the class should be added or removed
	 */
	set : function(element,className,add) {
		if (add) {
			hui.cls.add(element,className);
		} else {
			hui.cls.remove(element,className);
		}
	}
};










///////////////////// Events //////////////////

/**
 * Add an event listener to an element
 * @param {Element} element The element to listen on
 * @param {String} type The event to listen for
 * @param {Function} listener The function to be called
 * @param {object} ?bindTo Bind the listener to it
 */
hui.listen = function(element,type,listener,bindTo) {
	element = hui.get(element);
	if (!element) {
		return;
	}
  if (bindTo) {
    listener = listener.bind(bindTo)
  }
	if(document.addEventListener) {
		element.addEventListener(type,listener);
	} else {
		element.attachEvent('on'+type, listener);
	}
};

/**
 * Add an event listener to an element, it will only fire once
 * @param {Element} element The element to listen on
 * @param {String} type The event to listen for
 * @param {Function} listener The function to be called
 */
hui.listenOnce = function(element,type,listener) {	
	var func = null;
	func = function(e) {
		hui.unListen(element,type,func);
		listener(e);
	};
	hui.listen(element,type,func);
};

/**
 * Remove an event listener from an element
 * @param {Element} element The element to remove listener from
 * @param {String} type The event to remove
 * @param {Function} listener The function to remove
 * @param {boolean} useCapture If the listener should "capture"
 */
hui.unListen = function(el,type,listener,useCapture) {
	el = hui.get(el);
	if(document.removeEventListener) {
		el.removeEventListener(type,listener,useCapture ? true : false);
	} else {
		el.detachEvent('on'+type, listener);
	}
};

/** Creates an event wrapper for an event
 * @param event The DOM event
 * @returns {hui.Event} An event wrapper
 */
hui.event = function(event) {
	if (event!==undefined && event.huiEvent===true) {
		return event;
	}
	return new hui.Event(event);
};

/**
 * Wrapper for events
 * @class
 * @param event {Event} The DOM event
 */
hui.Event = function(event) {
	this.huiEvent = true;
	/** The event */
	this.event = event = event || window.event;
	
	if (!event) {
		hui.log('No event');
	}
	
	/** The target element */
	this.element = event.target ? event.target : event.srcElement;
	/** If the shift key was pressed */
	this.shiftKey = event.shiftKey;
	/** If the alt key was pressed */
	this.altKey = event.altKey;
	/** If the command key was pressed */
	this.metaKey = event.metaKey;
	/** If the return key was pressed */
	this.returnKey = event.keyCode==13;
	/** If the escape key was pressed */
	this.escapeKey = event.keyCode==27;
	/** If the space key was pressed */
	this.spaceKey = event.keyCode==32;
	/** If the backspace was pressed */
	this.backspaceKey = event.keyCode==8;
	/** If the up key was pressed */
	this.upKey = event.keyCode==38;
	/** If the down key was pressed */
	this.downKey = event.keyCode==40;
	/** If the left key was pressed */
	this.leftKey = event.keyCode==37;
	/** If the right key was pressed */
	this.rightKey = event.keyCode==39;
	/** The key code */
	this.keyCode = event.keyCode;
};

hui.Event.prototype = {
	/**
	 * Get the left coordinate
	 * @returns {Number} The left coordinate
	 * @type {Number}
	 */
	getLeft : function() {
	    var left = 0;
		if (this.event) {
		    if (this.event.pageX) {
			    left = this.event.pageX;
		    } else if (this.event.clientX) {
			    left = this.event.clientX + hui.window.getScrollLeft();
		    }
		}
	    return left;
	},
	/**
	 * Get the top coordinate
	 * @returns {Number} The top coordinate
	 */
	getTop : function() {
	    var top = 0;
		if (this.event) {
		    if (this.event.pageY) {
			    top = this.event.pageY;
		    } else if (this.event.clientY) {
			    top = this.event.clientY + hui.window.getScrollTop();
		    }
		}
	    return top;
	},
	/** Get the node the event originates from
	 * @returns {ELement} The originating element
	 */
	getElement : function() {
		return this.element;
	},
	/** Finds the nearest ancester with a certain class name
	 * @param cls The css class name
	 * @returns {Element} The found element or null
	 */
	findByClass : function(cls) {
		return hui.get.firstAncestorByClass(this.element,cls);
	},
	/** Finds the nearest ancester with a certain tag name
	 * @param tag The tag name
	 * @returns {Element} The found element or null
	 */
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
	find : function(func) {
		var parent = this.element;
		while (parent) {
			if (parent.tagName && parent.tagName.toLowerCase()==tag) {
				if (func(parent)) {
					return parent;
				}
			}
			parent = parent.parentNode;
		}
		return null;
	},
	isDescendantOf : function(node) {
		
		var parent = this.element;
		while (parent) {
			if (parent===node) {
				return true;
			}
			parent = parent.parentNode;
		}
		return false;
	},
	/** Stops the event from propagating */
	stop : function() {
		hui.stop(this.event);
	}
};

/** 
 * Stops an event from propagating
 * @param event A standard DOM event, NOT an hui.Event
 */
hui.stop = function(event) {
	if (!event) {event = window.event;}
	if (event.stopPropagation) {event.stopPropagation();}
	if (event.preventDefault) {event.preventDefault();}
	event.cancelBubble = true;
    event.stopped = true;
};

hui._defered = [];

hui._ready = document.readyState == 'complete';// || document.readyState;
// TODO Maybe interactive is too soon???

hui.onReady = function(func) {
	if (hui._ready) {
		func();
	} else {
		hui._defered.push(func);
	}
	if (hui._defered.length==1) {
		hui._onReady(function() {
  			hui._ready = true;
			for (var i = 0; i < hui._defered.length; i++) {
				hui._defered[i]();
			}
            hui._defered = null;
		});
	}
};

hui.onDraw = function(func) {
  window.setTimeout(func,13);
}

hui.onDraw = (function(vendors,window) {
  var found = window.requestAnimationFrame;
  for(var x = 0; x < vendors.length && !found; ++x) {
      found = window[vendors[x]+'RequestAnimationFrame'];
  }
  return found ? found.bind(window) : hui.onDraw;
})(['ms', 'moz', 'webkit', 'o'],window);

/**
 * Execute a function when the DOM is ready
 * @param delegate The function to execute
 */
hui._onReady = function(delegate) {
  if (document.readyState == 'interactive') {
    window.setTimeout(delegate);
  }
	else if(window.addEventListener) {
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
};








///////////////////////// Request /////////////////////////

/**
 * Send a HTTP request
 * <pre><strong>options:</strong> {
 *  method : «'<strong>POST</strong>' | 'get' | 'rEmOVe'»,
 *  async : <strong>true</strong>,
 *  headers : {<strong>Ajax : true</strong>, header : 'value'},
 *  file : «HTML5-file»,
 *  files : «HTML5-files»,
 *  parameters : {key : 'value'},
 *
 *  $success : function(transport) {
 *    // when status is 200
 *  },
 *  $forbidden : function(transport) {
 *    // when status is 403
 *  },
 *  $abort : function(transport) {
 *    // when request is aborted
 *  },
 *  $failure : function(transport) {
 *    // when status is not 200 (if status is 403 and $forbidden is set then $failure will not be called)
 *  },
 *  $exception : function(exception,transport) {
 *    // When an exception has occurred while calling on«Something», If not set the exception will be thrown
 *  },
 *  $progress : function(current,total) {
 *    // Progress for file uploads (maybe also other requests?)
 *  },
 *  $load : functon() {
 *    // When file upload is transfered?
 *  }
 *}
 * </pre>
 *
 * @param options The options
 * @returns {XMLHttpRequest} The transport
 */
hui.request = function(options) {
	options = hui.override({method:'POST',async:true,headers:{Ajax:true}},options);
	var transport = new XMLHttpRequest();
	if (!transport) {return;}
	transport.onreadystatechange = function() {
		if (transport.readyState == 4) {
			if (transport.status == 200 && options.$success) {
				options.$success(transport);
			} else if (transport.status == 403 && options.$forbidden) {
				options.$forbidden(transport);
			} else if (transport.status !== 0 && options.$failure) {
				options.$failure(transport);
			} else if (transport.status === 0 && options.$abort) {
				options.$abort(transport);
			}
			if (options.$finally) {
				options.$finally();
			}
		}
	};
	var method = options.method.toUpperCase();
	transport.open(method, options.url, options.async);
	var body = null;
	if (method=='POST' && options.file) {
		if (false) {
			body = options.file;
        	transport.setRequestHeader("Content-type", options.file.type);  
        	transport.setRequestHeader("X_FILE_NAME", options.file.name);
		} else {
			body = new FormData();
			body.append('file', options.file);
			if (options.parameters) {
				for (var param in options.parameters) {
					body.append(param, options.parameters[param]);
				}
			}
		}
		if (options.$progress) {
			transport.upload.addEventListener("progress", function(e) {
				options.$progress(e.loaded,e.total);
			}, false);
		}
		if (options.$load) {
	        transport.upload.addEventListener("load", function(e) {
				options.$load();
			}, false); 
		}
	} else if (method=='POST' && options.files) {
		body = new FormData();
		//form.append('path', '/');
		for (var i = 0; i < options.files.length; i++) {
			body.append('file'+i, options.files[i]);
		}
	} else if (method=='POST' && options.parameters) {
		body = hui.request._buildPostBody(options.parameters);
		transport.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	} else {
		body = '';
	}
	if (options.headers) {
		for (var name in options.headers) {
			transport.setRequestHeader(name, options.headers[name]);
		}
	}
	transport.send(body);
	//hui.request._transports.push(transport);
	//hui.log('Add: '+hui.request._transports.length);
	return transport;
};

/*
hui.request._transports = [];

hui.request._forget = function(t) {
	hui.log('Forget: '+hui.request._transports.length);
	hui.array.remove(hui.request._transports, t);
}

hui.request.abort = function() {
	window.stop();
	for (var i = hui.request._transports.length - 1; i >= 0; i--){
		hui.log('aborting: '+hui.request._transports[i].readyState)
		hui.request._transports[i].abort();
	};
}*/

/**
 * Check if a http request has a valid XML response
 * @param {XMLHttpRequest} t The request
 * @return true if a valid XML request exists
 */
hui.request.isXMLResponse = function(t) {
	return t.responseXML && t.responseXML.documentElement && t.responseXML.documentElement.nodeName!='parsererror';
};

hui.request._buildPostBody = function(parameters) {
	if (!parameters) return null;
	var output = '',
        param;
    if (hui.isArray(parameters)) {
        for (var i = 0; i < parameters.length; i++) {
            param = parameters[i];
    		if (i > 0) {output += '&';}
    		output+=encodeURIComponent(param.name)+'=';
    		if (param.value!==undefined && param.value!==null) {
    			output+=encodeURIComponent(param.value);
    		}
        }
    } else {
    	for (param in parameters) {
    		if (output.length > 0) {output += '&';}
    		output+=encodeURIComponent(param)+'=';
    		if (parameters[param]!==undefined && parameters[param]!==null) {
    			output+=encodeURIComponent(parameters[param]);
    		}
    	}        
    }
	return output;
};

///////////////////// Style ///////////////////

/** @namespace */
hui.style = {
	/**
	 * Copy the style from one element to another
	 * @param source The element to copy from
	 * @param target The element to copy to
	 * @param styles An array of properties to copy
	 */
	copy : function(source,target,styles) {
		for (var i=0; i < styles.length; i++) {
			var property = styles[i];
			var value = hui.style.get(source,property);
			if (value) {
				target.style[hui.string.camelize(property)] = value;
			}
		}
	},
	set : function(element,styles) {
		for (var style in styles) {
			if (style==='transform') {
				element.style.webkitTransform = styles[style];
			} else if (style==='opacity') {
				hui.style.setOpacity(element,styles[style]);
			} else {
				element.style[style] = styles[style];
			}
		}
	},
	/**
	 * Get the computed style of an element
	 * @param {Element} element The element
	 * @param {String} style The CSS property in the form font-size NOT fontSize; 
	 */
	get : function(element, style) {
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
		if (window.opera && hui.array.contains(['left', 'top', 'right', 'bottom'],style)) {
			if (hui.style.get(element, 'position') == 'static') {
				value = 'auto';
			}
		}
		return value == 'auto' ? '' : value;
	},
	/** Cross browser way of setting opacity */
	setOpacity : function(element,opacity) {
		if (!hui.browser.opacity) {
			if (opacity==1) {
				element.style.filter = null;
			} else {
				element.style.filter = 'alpha(opacity='+(opacity*100)+')';
			}
		} else {
			element.style.opacity = opacity;
		}
	},
    length : function(value) {
        if (typeof(value) === 'number') {
            return value + 'px';
        }
        return value;
    }
};






//////////////////// Frames ////////////////////

/** @namespace */
hui.frame = {
	/**
	 * Get the document object of a frame
	 * @param frame The frame to get the document from
	 */
	getDocument : function(frame) {
	    if (frame.contentDocument) {
	        return frame.contentDocument;
	    } else if (frame.contentWindow) {
	        return frame.contentWindow.document;
	    } else if (frame.document) {
	        return frame.document;
	    }
	},
	/**
	 * Get the window object of a frame
	 * @param frame The frame to get the window from
	 */
	getWindow : function(frame) {
	    if (frame.defaultView) {
	        return frame.defaultView;
	    } else if (frame.contentWindow) {
	        return frame.contentWindow;
	    }
	}
};






/////////////////// Selection /////////////////////

/** @namespace */
hui.selection = {
	/** Clear the text selection */
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
	/** Get the selected text
	 * @param doc The document, defaults to current document
	 */
	getText : function(doc) {
		doc = doc || document;
		if (doc.getSelection) {
			return doc.getSelection()+'';
		} else if (doc.selection) {
			return doc.selection.createRange().text;
		}
		return '';
	},
	getNode : function(doc) {
		doc = doc || document;
		if (doc.getSelection) {
			var range = doc.getSelection().getRangeAt(0);
			if (typeof(range.commonAncestorContainer) == 'function') {
				return range.commonAncestorContainer(); // TODO Not sure why?
			}
			return range.commonAncestorContainer;
		}
		return null;
	},
	get : function(doc) {
		return {
			node : hui.selection.getNode(doc),
			text : hui.selection.getText(doc)
		};
	},
	enable : function(on) {
		document.onselectstart = on ? null : function () { return false; };
		document.body.style.webkitUserSelect = on ? null : 'none';
	}
};





/////////////////// Effects //////////////////////

/** @namespace */
hui.effect = {
	makeFlippable : function(options) {
		if (hui.browser.webkit) {
			hui.cls.add(options.container,'hui_flip_container');
			hui.cls.add(options.front,'hui_flip_front');
			hui.cls.add(options.back,'hui_flip_back');
		} else {
			hui.cls.add(options.front,'hui_flip_front_legacy');
			hui.cls.add(options.back,'hui_flip_back_legacy');
		}
	},
	flip : function(options) {
		if (!hui.browser.webkit) {
			hui.cls.toggle(options.element,'hui_flip_flipped_legacy');
		} else {
			var element = hui.get(options.element);
			var duration = options.duration || '1s';
			var front = hui.get.firstByClass(element,'hui_flip_front');
			var back = hui.get.firstByClass(element,'hui_flip_back');
			front.style.webkitTransitionDuration=duration;
			back.style.webkitTransitionDuration=duration;
			hui.cls.toggle(options.element,'hui_flip_flipped');
		}
	},
	/**
	 * Reveal an element using a bounce/zoom effect
	 * @param {Object} options {element:«Element»}
	 */
	bounceIn : function(options) {
		var node = options.element;
		if (hui.browser.msie) {
			hui.style.set(node,{'display':'block',visibility:'visible'});
		} else {
			hui.style.set(node,{'display':'block','opacity':0,visibility:'visible'});
			hui.animate(node,'transform','scale(0.1)',0);// rotate(10deg)
			window.setTimeout(function() {
				hui.animate(node,'opacity',1,300);
				hui.animate(node,'transform','scale(1)',400,{ease:hui.ease.backOut}); // rotate(0deg)
			});
		}
	},
	/**
	 * Fade an element in - making it visible
	 * @param {Object} options {element : «Element», duration : «milliseconds», delay : «milliseconds», $complete : «Function» }
	 */
	fadeIn : function(options) {
		var node = options.element;
		if (hui.style.get(node,'display')=='none') {
			hui.style.set(node,{opacity : 0,display : 'inherit'});
		}
		hui.animate({
			node : node,
			css : { opacity : 1 },
			delay : options.delay || null,
			duration : options.duration || 500,
			$complete : options.onComplete || options.$complete
		});
	},
	/**
	 * Fade an element out - making it invisible
	 * @param {Object} options {element : «Element», duration : «milliseconds», delay : «milliseconds», $complete : «Function» }
	 */
	fadeOut : function(options) {
		hui.animate({
			node : options.element,
			css : { opacity : 0 },
			delay : options.delay || null,
			duration : options.duration || 500,
			hideOnComplete : true,
			complete : options.onComplete || options.$complete
		});
	},
	/**
	 * Make an element wiggle
	 * @param {Object} options {element : «Element», duration : «milliseconds» }
	 */
	wiggle : function(options) {
		var e = hui.ui.getElement(options.element);
		hui.cls.add(options.element,'hui_effect_wiggle');
		window.setTimeout(function() {
			hui.cls.remove(options.element,'hui_effect_wiggle');
		},options.duration || 1000);
	
	},
	/**
	 * Make an element shake
	 * @param {Object} options {element : «Element», duration : «milliseconds» }
	 */
	shake : function(options) {
    this._do(options.element,'hui_effect_shake',options.duration || 1000);
	},
	/**
	 * Make an element shake
	 * @param {Object} options {element : «Element», duration : «milliseconds» }
	 */
	tada : function(options) {
    this._do(options.element,'hui_effect_tada',1000);
	},
  _do : function(e,cls,time) {
    e = hui.ui.getElement(e);
		hui.cls.add(e,cls);
		window.setTimeout(function() {
			hui.cls.remove(e,cls);
		},time);    
  }
};





/////////////////// Document /////////////////////

/** @namespace */
hui.document = {
	/**
	 * Get the height of the document (including the invisble part)
	 */
	getWidth : function() {
		return Math.max(document.body.clientWidth,document.documentElement.clientWidth,document.documentElement.scrollWidth);
	},
	/**
	 * Get the width of the document (including the invisble part)
	 */
	getHeight : function() {
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
};








/////////////////////////////// Drag ///////////////////////////

/** @namespace */
hui.drag = {
	/** Register dragging on an element
	 * <pre><strong>options:</strong> {
	 *  element : «Element»
	 *  <em>see hui.drag.start for more options</em>
	 * }
	 * @param {Object} options The options
	 * @param {Element} options.element The element to attach to
	 */
	register : function(options) {
		var touch = options.touch && hui.browser.touch;
		hui.listen(options.element,touch ? 'touchstart' : 'mousedown',function(e) {
			e = hui.event(e);
			// TODO This shuould be a hui.Event
			if (options.$check && options.$check(e)===false) {
				return;
			}
			e.stop();
			hui.drag.start(options,e);
		});
	},
	/** Start dragging
	 * <pre><strong>options:</strong> {
	 *  onBeforeMove : function(event), // Called when the cursor moves for the first time
	 *  onMove : function(event), // Called when the cursor moves
	 *  onAfterMove : function(event), // Called if the cursor has moved
   *  onNotMoved : function(event), // Called if the cursor has not moved
	 *  onEnd : function(event), // Called when the mouse is released, even if the cursor has not moved
	 * }
	 * @param {Object} options The options
	 */
	start : function(options,e) {
		var target = hui.browser.msie ? document : window;
		var touch = options.touch && hui.browser.touch;
		if (options.onStart) {
			options.onStart();
		}
		var latest = {
			x: e.getLeft(),
			y: e.getTop(),
			time: Date.now()
		};
		var initial = latest;
		var mover,
			upper,
			moved = false;
		mover = function(e) {
			e = hui.event(e);
			e.stop(e);
			if (!moved && options.onBeforeMove) {
				options.onBeforeMove(e);
			}
			moved = true;
			options.onMove(e);
		}.bind(this);
		hui.listen(target,touch ? 'touchmove' : 'mousemove',mover);
		upper = function() {
			hui.unListen(target,touch ? 'touchmove' : 'mousemove',mover);
			hui.unListen(target,touch ? 'touchend' : 'mouseup',upper);
			if (options.onEnd) {
				options.onEnd();
			}
			if (moved && options.onAfterMove) {
				options.onAfterMove();
			}
			if (!moved && options.onNotMoved) {
				options.onNotMoved();
			}
			hui.selection.enable(true);
		}.bind(this);
		hui.listen(target,touch ? 'touchend' : 'mouseup',upper);
		hui.selection.enable(false);
	},
	
	_nativeListeners : [],
	
	_activeDrop : null,
	
	/** Listen for native drops
	 * <pre><strong>options:</strong> {
	 *  elements : «Element»,
	 *  hoverClass : «String»,
	 *  $drop : function(event),
	 *  $dropFiles : function(fileArray),
	 *  $dropURL : function(url),
	 *  $dropText : function(url)
	 * }
	 * @param {Object} options The options
	 */
	listen : function(options) {
		if (hui.browser.msie) {
			return;
		}
		hui.drag._nativeListeners.push(options);
		if (hui.drag._nativeListeners.length>1) {
            return;
        }
		hui.listen(document.body,'dragenter',function(e) {
			var l = hui.drag._nativeListeners;
			var found = null;
			for (var i=0; i < l.length; i++) {
				var lmnt = l[i].element;
				if (hui.dom.isDescendantOrSelf(e.target,lmnt)) {
					found = l[i];
					if (hui.drag._activeDrop === null || hui.drag._activeDrop != found) {
						hui.cls.add(lmnt,found.hoverClass);
					}
					break;
				}
			}
			if (hui.drag._activeDrop) {
				//var foundElement = found ? found.element : null;
				if (hui.drag._activeDrop!=found) {
					hui.cls.remove(hui.drag._activeDrop.element,hui.drag._activeDrop.hoverClass);
					if (hui.drag._activeDrop.$leave) {
						hui.drag._activeDrop.$leave(e);
					}
				} else if (hui.drag._activeDrop.$hover) {
					hui.drag._activeDrop.$hover(e);
				}
				
			}
			hui.drag._activeDrop = found;
		});
		
		hui.listen(document.body,'dragover',function(e) {
			hui.stop(e);
			if (hui.drag._activeDrop) {
				if (hui.drag._activeDrop.$hover) {
					hui.drag._activeDrop.$hover(e);
				}
			}
		});
		
		hui.listen(document.body,'dragend',function(e) {
			hui.log('drag end');
		});
		
		hui.listen(document.body,'dragstart',function(e) {
			hui.log('drag start');
		});
		
		hui.listen(document.body,'drop',function(e) {
			var event = hui.event(e);
			event.stop();
			var options = hui.drag._activeDrop;
			hui.drag._activeDrop = null;
			if (options) {
				hui.cls.remove(options.element,options.hoverClass);
				if (options.$drop) {
					options.$drop(e,{event:event});
				}
				if (e.dataTransfer) {
					hui.log(e.dataTransfer.types);
					if (options.$dropFiles && e.dataTransfer.files && e.dataTransfer.files.length>0) {
						options.$dropFiles(e.dataTransfer.files,{event:event});
					} else if (options.$dropURL && e.dataTransfer.types !== null && (hui.array.contains(e.dataTransfer.types,'public.url') || hui.array.contains(e.dataTransfer.types,'text/uri-list'))) {
						var url = e.dataTransfer.getData('public.url');
						var uriList = e.dataTransfer.getData('text/uri-list');
						if (url && !hui.string.startsWith(url,'data:')) {
							options.$dropURL(url,{event:event});
						} else if (uriList && !hui.string.startsWith(url,'data:')) {
							options.$dropURL(uriList,{event:event});
						}
					} else if (options.$dropText && e.dataTransfer.types !== null && hui.array.contains(e.dataTransfer.types,'text/plain')) {
						options.$dropText(e.dataTransfer.getData('text/plain'),{event:event});
					}
				}
			}
		});
	}
};



//////////////////////////// Preloader /////////////////////////

/** 
 * A preloader for images
 * @constructor
 * @param options {Object}
 * @param options.context {String} Prefix for all URLs
 */
hui.Preloader = function(options) {
	this.options = options || {};
	this.delegate = {};
	this.images = [];
	this.loaded = 0;
};

hui.Preloader.prototype = {
	/** Add images either as a single url or an array of urls */
	addImages : function(imageOrImages) {
		if (typeof(imageOrImages)=='object') {
			for (var i=0; i < imageOrImages.length; i++) {
				this.images.push(imageOrImages[i]);
			}
		} else {
			this.images.push(imageOrImages);
		}
	},
	/**
   * Set the delegate (listener)
   * @param {Object} listener
   */
	setDelegate : function(listener) {
		this.delegate = listener;
	},
	/**
	 * Start loading images beginning at startIndex
	 */
	load : function(startIndex) {
		startIndex = startIndex || 0;
		var self = this;
		this.obs = [];
		var onLoad = function() {self._imageChanged(this.huiPreloaderIndex,'imageDidLoad');};
		var onError = function() {self._imageChanged(this.huiPreloaderIndex,'imageDidGiveError');};
		var onAbort = function() {self._imageChanged(this.huiPreloaderIndex,'imageDidAbort');};
		for (var i=startIndex; i < this.images.length+startIndex; i++) {
			var index=i;
			if (index>=this.images.length) {
				index = index-this.images.length;
			}
			var img = new Image();
			img.huiPreloaderIndex = index;
			img.onload = onLoad;
			img.onerror = onError;
			img.onabort = onAbort;
			img.src = (this.options.context ? this.options.context : '')+this.images[index];
			this.obs.push(img);
		}
	},
	_imageChanged : function(index,method) {
		this.loaded++;
		if (this.delegate[method]) {
			this.delegate[method](this.loaded,this.images.length,index);
		}
		if (this.loaded==this.images.length && this.delegate.allImagesDidLoad) {
			this.delegate.allImagesDidLoad();
		}
	}
};




///////////////// Cookies //////////////////

/** @namespace */
hui.cookie = {
	/** Adds a cookie value by name */
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
	/** Gets a cookie value by name */
	get : function(name) {
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') {
				c = c.substring(1,c.length);
			}
			if (c.indexOf(nameEQ) === 0) {
				return c.substring(nameEQ.length,c.length);
			}
		}
		return null;
	},
	/** Clears a cookie by name */
	clear : function(name) {
		this.set(name,"",-1);
	}
};






///////////////////////// Location /////////////////////

/** @namespace */
hui.location = {
	/** Get an URL parameter */
	getParameter : function(name) {
		var parms = hui.location.getParameters();
		for (var i=0; i < parms.length; i++) {
			if (parms[i].name==name) {
				return parms[i].value;
			}
		}
		return null;
	},
	/** Set an URL parameter - initiates a new request */
	setParameter : function(name,value) {
		var parms = hui.location.getParameters();
		var found = false;
		for (var i=0; i < parms.length; i++) {
			if (parms[i].name==name) {
				parms[i].value=value;
				found=true;
				break;
			}
		}
		if (!found) {
			parms.push({name:name,value:value});
		}
		hui.location.setParameters(parms);
	},
	/** Checks if the URL has a certain hash */
	hasHash : function(name) {
		var h = document.location.hash;
		if (h!=='') {
			return h=='#'+name;
		}
		return false;
	},
	getHash : function() {
		var h = document.location.hash;
		if (h!=='') {
			return h.substring(1);
		}
		return null;
	},
	/** Gets a hash parameter (#name=value&other=text) */
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
	/** Clears the URL hash */
	clearHash : function() {
		document.location.hash='#';
	},
	/** Sets a number of parameters
	 * @param params {Array} Parameters [{name:'hep',value:'hey'}]
	 */
	setParameters : function(parms) {
		var query = '';
		for (var i=0; i < parms.length; i++) {
			query+= i === 0 ? '?' : '&';
			query+=parms[i].name+'='+parms[i].value;
		}
		document.location.search=query;
	},
	/** Checks if a parameter exists with the value 'true' or 1 */
	getBoolean : function(name) {
		var value = hui.location.getParameter(name);
		return (value=='true' || value=='1');
	},
	/** Checks if a parameter exists with the value 'true' or 1 */
	getInt : function(name) {
		var value = parseInt(hui.location.getParameter(name));
		if (!isNaN(value)) {
			return value;
		}
		return null;
	},
	/** Gets all parameters as an array like : [{name:'hep',value:'hey'}] */
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
		}
		return parsed;
	}	
};



if (window.define) {
	define('hui',hui);
}

/////////////////////////// Animation ///////////////////////////

/**
 * Animate something
 * <pre><strong>options:</strong> {
 *  node : «Element», 
 *  css : { fontSize : '11px', color : '#f00', opacity : 0.5 }, 
 *  duration : 1000, // 1sec 
 *  ease : function(num) {},
 *  $complete : function() {}
 *}
 * TODO Document options.property, options.value
 * 
 * @param {Element | Object} options Options or an element
 * @param {String} style The css property
 * @param {String} value The css value
 * @param {Number} duration The duration in milisecons
 * @param {Object} deleagte The options if first param is an element
 * 
 */
hui.animate = function(options, property, value, duration, delegate) {
  if (typeof(options) == 'string' || hui.dom.isElement(options)) {
    hui.animation.get(options).animate(null, value, property, duration, delegate);
  } else {
    var item = hui.animation.get(options.node);
    if (options.property) {
      item.animate(null, options.value, options.property, options.duration, options);
    } else if (!options.css) {
      item.animate(null, '', '', options.duration, options);
    } else {
      var o = options;
      for (var prop in options.css) {
        item.animate(null, options.css[prop], prop, options.duration, o);
        o = hui.override({}, options);
        o.$complete = undefined;
      }
    }
  }
};


/** @namespace */
hui.animation = {
  objects: {},
  running: false,
  latestId: 0,
  /** Get an animation item for a node */
  get: function(element) {
    element = hui.get(element);
    if (!element.huiAnimationId) {
      element.huiAnimationId = this.latestId++;
    }
    if (!this.objects[element.huiAnimationId]) {
      this.objects[element.huiAnimationId] = new hui.animation.Item(element);
    }
    return this.objects[element.huiAnimationId];
  },
  /** Start animating any pending tasks */
  start: function() {
    if (!this.running) {
      hui.animation._render();
    }
  }
};


hui.animation._lengthUpater = function(element,v,work) {
	element.style[work.property] = (work.from+(work.to-work.from)*v)+(work.unit!=null ? work.unit : '');
}

hui.animation._transformUpater = function(element, v, work) {
  var t = work.transform;
  var str = '';
  if (t.rotate) {
    str += ' rotate(' + (t.rotate.from + (t.rotate.to - t.rotate.from) * v) + t.rotate.unit + ')';
  }
  if (t.scale) {
    str += ' scale(' + (t.scale.from + (t.scale.to - t.scale.from) * v) + ')';
  }
  element.style[hui.animation.TRANSFORM] = str;
};

hui.animation._colorUpater = function(element, v, work) {
  var red = Math.round(work.from.red + (work.to.red - work.from.red) * v);
  var green = Math.round(work.from.green + (work.to.green - work.from.green) * v);
  var blue = Math.round(work.from.blue + (work.to.blue - work.from.blue) * v);
  value = 'rgb(' + red + ',' + green + ',' + blue + ')';
  element.style[work.property] = value;
};

hui.animation._propertyUpater = function(element, v, work) {
	element[work.property] = Math.round(work.from+(work.to-work.from)*v);
}

hui.animation._ieOpacityUpdater = function(element, v, work) {
  var opacity = (work.from + (work.to - work.from) * v);
  if (opacity == 1) {
    element.style.removeAttribute('filter');
  } else {
    element.style.filter = 'alpha(opacity=' + (opacity * 100) + ')';
  }
};

hui.animation._render = function() {
	hui.animation.running = true;
	var next = false,
	stamp = Date.now();
	for (var id in hui.animation.objects) {
		var obj = hui.animation.objects[id];
		if (obj.work) {
			var element = obj.element;
			for (var i=0; i < obj.work.length; i++) {
				var work = obj.work[i];
				if (work.finished) {
					continue
				};
				var place = (stamp-work.start)/(work.end-work.start);
				if (place<0) {
					next=true;
					continue;
				}
				else if (isNaN(place) || place>1) {
					place = 1;
				}
				else if (place<1) {
					next=true;
				}
				var v = place,
					value = null;
				if (work.delegate && work.delegate.ease) {
					v = work.delegate.ease(v);
				}
				if (work.delegate && work.delegate.$render) {
					work.delegate.$render(element,v);
				} else if (work.delegate && work.delegate.callback) {
					work.delegate.callback(element,v);
				} else if (work.updater) {
					work.updater(element,v,work);
				}
				if (place==1) {
					work.finished = true;
					if (work.delegate && work.delegate.$complete) {
						window.setTimeout(work.delegate.$complete);
					} else if (work.delegate && work.delegate.onComplete) {
						window.setTimeout(work.delegate.onComplete);
					} else if (work.delegate && work.delegate.hideOnComplete) {
						element.style.display='none';
					}
				}
			};
		}
	}
	if (next) {
		hui.onDraw(hui.animation._render);
	} else {
		hui.animation.running = false;
	}
};

hui.animation._parseStyle = function(value) {
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
  		parsed.type = 'color';
			parsed.value = {
				red:color.r,
				green:color.g,
				blue:color.b
			};
		}
	}
	return parsed;
};

///////////////////////////// Item ///////////////////////////////

/** 
 * An animation item describing what to animate on an element
 * @constructor
 */
hui.animation.Item = function(element) {
	this.element = element;
	this.work = [];
};

hui.animation.Item.prototype.animate = function(from,to,property,duration,delegate) {
	var work = this.getWork(hui.string.camelize(property));
	work.delegate = delegate;
	work.finished = false;
	var css = !(property=='scrollLeft' || property=='scrollTop' || property=='');
	if (from!==null) {
		work.from = from;
	} else if (property=='transform') {
		work.transform = hui.animation.Item.parseTransform(to,this.element);
	} else if (!hui.browser.opacity && property=='opacity') {
		work.from = this._getIEOpacity(this.element);
	} else if (css) {
		var style = hui.style.get(this.element,property);
		var parsedStyle = hui.animation._parseStyle(style);
		work.from = parsedStyle.value;
	} else {
		work.from = this.element[property];
	}
	if (css) {
		var parsed = hui.animation._parseStyle(to);
		work.to = parsed.value;
		work.unit = parsed.unit;
		if (!hui.browser.opacity && property=='opacity') {
			work.updater = hui.animation._ieOpacityUpdater;
		} else if (property=='transform') {
			work.updater = hui.browser.msie ? function() {} : hui.animation._transformUpater;
		} else if (parsed.type=='color') {
			work.updater = hui.animation._colorUpater;
		} else {
			work.updater = hui.animation._lengthUpater;
		}
	} else {
		work.to = to;
		work.unit = null;
		work.updater = hui.animation._propertyUpater;
	}
	work.start = Date.now();
	if (delegate && delegate.delay) {
		work.start+=delegate.delay;
	}
	work.end = work.start+duration;
	hui.animation.start();
};

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
};

hui.animation.Item.prototype._getIEOpacity = function(element) {
	var filter = hui.style.get(element,'filter').toLowerCase();
	var match;
	if (match = filter.match(/opacity=([0-9]+)/)) {
		return parseFloat(match[1])/100;
	} else {
		return 1;
	}
};

hui.animation.Item.prototype.getWork = function(property) {
	for (var i = this.work.length - 1; i >= 0; i--) {
		if (this.work[i].property===property) {
			return this.work[i];
		}
	};
	var work = {property:property};
	this.work[this.work.length] = work;
	return work;
};

/////////////////////////////// Loop ///////////////////////////////////

/** @constructor */
hui.animation.Loop = function(recipe) {
	this.recipe = recipe;
	this.position = -1;
	this.running = false;
};

hui.animation.Loop.prototype.next = function() {
	this.position++;
	if (this.position>=this.recipe.length) {
		this.position = 0;
	}
	var item = this.recipe[this.position];
	if (typeof(item)=='function') {
		item();
	} else if (item.element) {
		hui.animate(item.element,item.property,item.value,item.duration,{ease:item.ease});
	}
	var self = this;
	var time = item.duration || 0;
	if (item.wait!==undefined) {
		time = item.wait;
	}
	window.setTimeout(function() {self.next()},time);
};

hui.animation.Loop.prototype.start = function() {
	this.running=true;
	this.next();
};

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
		if(n<0.5){ return hui.ease.bounceIn(n*2) / 2; }
		return (hui.ease.bounceOut(n*2-1) / 2) + 0.5; // Decimal
	}
};

if (!Date.now) {
  Date.now = function now() {
    return new Date().getTime();
  };
}

/** @constructor
 * @param str The color like red or rgb(255, 0, 0) or #ff0000 or rgb(100%, 0%, 0%)
 */
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
            re: /^rgb\((\d{1,3})%,\s*(\d{1,3})%,\s*(\d{1,3})%\)$/ ,
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
            var channels = processor(bits);
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
};

hui.Color.prototype = {
  /** Get the color as rgb(255,0,0) */
  toRGB : function () {
        return 'rgb(' + this.r + ', ' + this.g + ', ' + this.b + ')';
    },
  isDefined : function() {
    return !(this.r===undefined || this.g===undefined || this.b===undefined);
  },
  /** Get the color as #ff0000 */
  toHex : function() {
    if (!this.isDefined()) {return null;}
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
};

hui.Color.table = {
  white : 'ffffff',
  black : '000000',
  red : 'ff0000',
  green : '00ff00',
  blue : '0000ff'
};

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
  };
};

hui.Color.hsv2rgb = function (Hdeg,S,V) {
  var H = Hdeg/360,R,G,B;     // convert from degrees to 0 to 1
  if (S===0) {       // HSV values = From 0 to 1
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
    if (i===0) {
      var_r=V ;
      var_g=var_3;
      var_b=var_1;
    }
    else if (i===1) {
      var_r=var_2;
      var_g=V;
      var_b=var_1;
    }
    else if (i==2) {
      var_r=var_1;
      var_g=V;
      var_b=var_3;
    }
    else if (i==3) {
      var_r=var_1;
      var_g=var_2;
      var_b=V;
    }
    else if (i==4) {
      var_r=var_3;
      var_g=var_1;
      var_b=V;
    }
    else {
      var_r=V;
      var_g=var_1;
      var_b=var_2;
    }
    R = Math.round(var_r*255);   //RGB results = From 0 to 255
    G = Math.round(var_g*255);
    B = Math.round(var_b*255);
  }
  return new Array(R,G,B);
};

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
  if (max === 0) {
    saturation = 0;
  } else {
    saturation = 1 - (min/max);
  }

  return [Math.round(hue), Math.round(saturation * 100), Math.round(value * 100)];
};

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
};

/*
  * $script.js v1.3
  * https://github.com/ded/script.js
  * Copyright: @ded & @fat - Dustin Diaz, Jacob Thornton 2011
  * Follow our software http://twitter.com/dedfat
  * License: MIT
  */
!function(win, doc, timeout) {
  var head = doc.getElementsByTagName('head')[0],
      list = {}, ids = {}, delay = {},
      scripts = {}, s = 'string', f = false,
      push = 'push', domContentLoaded = 'DOMContentLoaded', readyState = 'readyState',
      addEventListener = 'addEventListener', onreadystatechange = 'onreadystatechange',
      every = function(ar, fn) {
        for (var i = 0, j = ar.length; i < j; ++i) {
          if (!fn(ar[i])) {
            return f;
          }
        }
        return 1;
      };
      function each(ar, fn) {
        every(ar, function(el) {
          return !fn(el);
        });
      }

  if (!doc[readyState] && doc[addEventListener]) {
    doc[addEventListener](domContentLoaded, function fn() {
      doc.removeEventListener(domContentLoaded, fn, f);
      doc[readyState] = "complete";
    }, f);
    doc[readyState] = "loading";
  }

  var $script = function(paths, idOrDone, optDone) {
    paths = paths[push] ? paths : [paths];
    var idOrDoneIsDone = idOrDone && idOrDone.call,
        done = idOrDoneIsDone ? idOrDone : optDone,
        id = idOrDoneIsDone ? paths.join('') : idOrDone,
        queue = paths.length;
        function loopFn(item) {
          return item.call ? item() : list[item];
        }
        function callback() {
          if (!--queue) {
            list[id] = 1;
            if (done) {done();}
            for (var dset in delay) {
              every(dset.split('|'), loopFn) && !each(delay[dset], loopFn) && (delay[dset] = []);
            }
          }
        }
    timeout(function() {
      each(paths, function(path) {
        if (scripts[path]) {
          id && (ids[id] = 1);
          callback();
          return;
        }
        scripts[path] = 1;
        id && (ids[id] = 1);
        create($script.path ?
          $script.path + path + '.js' :
          path, callback);
      });
    }, 0);
    return $script;
  };

  function create(path, fn) {
    var el = doc.createElement("script"),
        loaded = f;
    el.onload = el.onerror = el[onreadystatechange] = function () {
      if ((el[readyState] && !(/^c|loade/.test(el[readyState]))) || loaded) {
        return;
      }
      el.onload = el[onreadystatechange] = null;
      loaded = 1;
      fn();
    };
    el.async = 1;
    el.src = path;
    head.insertBefore(el, head.firstChild);
  }

  $script.get = create;

  $script.ready = function(deps, ready, req) {
    deps = deps[push] ? deps : [deps];
    var missing = [];
    !each(deps, function(dep) {
      list[dep] || missing[push](dep);
    }) && every(deps, function(dep) {
      return list[dep];
    }) ? ready() : !function(key) {
      delay[key] = delay[key] || [];
      delay[key][push](ready);
      req && req(missing);
    }(deps.join('|'));
    return $script;
  };

  var old = win.$script;
  $script.noConflict = function () {
    win.$script = old;
    return this;
  };

  (typeof module !== 'undefined' && module.exports) ?
    (module.exports = $script) :
    (win.hui.require = $script);
}(this, document, setTimeout);


hui.parallax = {
  
  _listeners : [],
  
  _init : function() {
    if (this._listening) {
      return;
    }
    this._listening = true;
    hui.listen(window,'scroll',this._scroll.bind(this));
    hui.listen(window,'resize',this._resize.bind(this));
    hui.onReady(this._resize.bind(this));
  },
  _resize : function() {
    for (var i = this._listeners.length - 1; i >= 0; i--) {
      var l = this._listeners[i];
      if (l.$resize) {
        l.$resize(hui.window.getViewWidth(),hui.window.getViewHeight());
      }
    }
    this._scroll();
  },
  _scroll : function() {
    var pos = hui.window.getScrollTop(),
      viewHeight = hui.window.getViewHeight();
    for (var i = this._listeners.length - 1; i >= 0; i--) {
      var l = this._listeners[i];
      if (!l.$scroll) {
        continue;
      }
      if (l.debug && !l.debugElement) {
        l.debugElement = hui.build('div',{style:'position: absolute; border-top: 1px solid red; left: 0; right: 0;',parent:document.body});
      }
      
      if (l.element) {
        var top = hui.position.getTop(l.element);
        top+= l.element.clientHeight/2;
        var diff = top-pos;
        var scroll = ( diff / viewHeight);
        if (l.debugElement) {
          l.debugElement.style.top = top+'px';
          l.debugElement.innerHTML = '<span>'+scroll+'</span>';
        }
        l.$scroll( scroll );
        continue;
      }
      
      var x = (pos-l.min)/(l.max-l.min);
      var y = hui.between(0,x,1);
      
      if (l._latest!==y) {
        l.$scroll(y);
        l._latest=y;
      }
    }
  },
  
  listen : function(info) {
    this._listeners.push(info);
    this._init();
  }
};

/**
  The namespace of the HUI framework
  @namespace
 */
hui.ui = {
	domReady : false,
	context : '',
	language : 'en',

	objects : {},
	delegates : [],

	state : 'default',

	latestObjectIndex : 0,
	latestIndex : 500,
	latestPanelIndex : 1000,
	latestAlertIndex : 1500,
	latestTopIndex : 2000,
	toolTips : {},
	confirmOverlays : {},
	
	delayedUntilReady : [],
	
	texts : {
		request_error : {en:'An error occurred on the server',da:'Der skete en fejl på serveren'},
		'continue' : {en:'Continue',da:'Fortsæt'},
		reload_page : {en:'Reload page',da:'Indæs siden igen'},
		access_denied : {en:'Access denied, maybe you are nolonger logged in',da:'Adgang nægtet, du er måske ikke længere logget ind'}
	}
};

/**
 * Get a component by name
 * @param nameOrComponent {hui.ui.Component | String} Get a component by name, if the parameter is already a component it is returned
 * @return {hui.ui.Component} The component with the name or undefined
 */
hui.ui.get = function(nameOrComponent) {
	if (nameOrComponent) {
		if (nameOrComponent.element) {
			return nameOrComponent;
		}
		return hui.ui.objects[nameOrComponent];
	}
};


/**
 * Called when the DOM is ready and hui.ui is ready
 */
hui.ui.onReady = function(func) {
	if (hui.ui.domReady) {return func();}
	if (hui.browser.gecko && hui.string.endsWith(document.baseURI,'xml')) {
		window.setTimeout(func,1000);
		return;
	}
	hui.ui.delayedUntilReady.push(func);
};

hui.ui._frameLoaded = function(win) {
	hui.ui.callSuperDelegates(this,'frameLoaded',win);
};

/** @private */
hui.ui._resize = function() {
	hui.ui.reLayout();
	window.clearTimeout(this._delayedResize);
	if (!hui.ui._resizeFirst) {
		this._delayedResize = window.setTimeout(hui.ui._afterResize,500);
	}
};

hui.ui._afterResize = function() {
  hui.onDraw(function() {
  	hui.ui.callSuperDelegates(hui.ui,'$afterResize');    
  })
};

/**
 * Show a confirming overlay
 * <pre><strong>options:</strong> {
 *  element : «Element», // the element to show at
 *  widget : «Widget», // the widget to show at
 *  text : «String», // the text message
 *  okText : «String», // text of OK button
 *  cancelText «String», // text of cancel button
 *  $ok: «Function», // called when user clicks the OK button
 *  $cancel: «Function» // called when user clicks the Cancel button
 * }
 * </pre>
 * @param options {Object} The options
 */
hui.ui.confirmOverlay = function(options) {
	var node = options.element,
		overlay;
	if (!node) {
		node = document.body;
	}
	if (options.widget) {
		node = options.widget.getElement();
	}
	if (hui.ui.confirmOverlays[node]) {
		overlay = hui.ui.confirmOverlays[node];
		overlay.clear();
	} else {
		overlay = hui.ui.Overlay.create({modal:true});
		hui.ui.confirmOverlays[node] = overlay;
	}
	if (options.text) {
		overlay.addText(hui.ui.getTranslated(options.text));
	}
	var ok = hui.ui.Button.create({text:hui.ui.getTranslated(options.okText) || 'OK',highlighted:'true'});
	ok.click(function() {
		if (options.onOk) {
			options.onOk();
		}
		else if (options.$ok) {
			options.$ok();
		}
		overlay.hide();
	});
	overlay.add(ok);
	var cancel = hui.ui.Button.create({text:hui.ui.getTranslated(options.cancelText) || 'Cancel'});
	cancel.onClick(function() {
		if (options.onCancel) {
			options.onCancel();
		}
		else if (options.$cancel) {
			options.$cancel();
		}
		overlay.hide();
	});
	overlay.add(cancel);
	overlay.show({element:node});
};

/**
 * Unregisters a widget
 * @param widget {Widget} The widget to destroy 
 */
hui.ui.destroy = function(widget) {
  if (typeof(widget.destroy)=='function') {
    widget.destroy();
  }
	delete(hui.ui.objects[widget.name]);
};

hui.ui.destroyDescendants = function(widgetOrElement) {
	var desc = hui.ui.getDescendants(widgetOrElement);
	var objects = hui.ui.objects;
	for (var i=0; i < desc.length; i++) {
    hui.ui.destroy(desc[i]);
	}
};

/** Gets all ancestors of a widget
	@param {Widget} widget A widget
	@returns {Array} An array of all ancestors
*/
hui.ui.getAncestors = function(widget) {
	var desc = [];
	var e = widget.element;
	if (e) {
		var a = hui.get.ancestors(e);
		var o = [];
		for (var key in hui.ui.objects) {
			o.push(hui.ui.objects[key]);
		}
		for (var i=0; i < a.length; i++) {
			for (var j=0; j < o.length; j++) {
				if (o[j].element==a[i]) {
					desc.push(o[j]);
				}
			}
		}
	}
	return desc;
};

hui.ui.getDescendants = function(widgetOrElement) {
	var desc = [];
	if (widgetOrElement) {
		var e = widgetOrElement.getElement ? widgetOrElement.getElement() : widgetOrElement;
		if (e) {
			var d = e.getElementsByTagName('*');
			var o = [];
			for (var key in hui.ui.objects) {
				o.push(hui.ui.objects[key]);
			}
			for (var i=0; i < d.length; i++) {
				for (var j=0; j < o.length; j++) {
					if (d[i]==o[j].element) {
						desc.push(o[j]);
					}
				}
			}
		}
	}
	return desc;
};

hui.ui.getAncestor = function(widget,cls) {
	var a = hui.ui.getAncestors(widget);
	for (var i=0; i < a.length; i++) {
		if (hui.cls.has(a[i].getElement(),cls)) {
			return a[i];
		}
	}
	return null;
};



hui.ui.changeState = function(state) {
	if (hui.ui.state===state) {return;}
	var all = hui.ui.objects,
		key,obj;
	for (key in all) {
		obj = all[key];
		if (obj.options && obj.options.state) {
			if (obj.options.state==state) {
				obj.show();
			} else {
				obj.hide();
			}
		}
	}
	hui.ui.state=state;
	
	this.reLayout();
};

hui.ui.reLayout = function() {
	var widgets = hui.ui.getDescendants(document.body);
	for (var i=0; i < widgets.length; i++) {
		var obj = widgets[i];
		if (obj.$$layout) {
			obj.$$layout();
		}
	}
};



///////////////////////////////// Indexes /////////////////////////////

hui.ui.nextIndex = function() {
	hui.ui.latestIndex++;
	return 	hui.ui.latestIndex;
};

hui.ui.nextPanelIndex = function() {
	hui.ui.latestPanelIndex++;
	return 	hui.ui.latestPanelIndex;
};

hui.ui.nextAlertIndex = function() {
	hui.ui.latestAlertIndex++;
	return 	hui.ui.latestAlertIndex;
};

hui.ui.nextTopIndex = function() {
	hui.ui.latestTopIndex++;
	return 	hui.ui.latestTopIndex;
};



///////////////////////////////// Curtain /////////////////////////////

/**
 * Shows a "curtain" behind an element
 * @param options { widget:«widget», color:«cssColor | 'auto'», zIndex:«cssZindex» }
 */
hui.ui.showCurtain = function(options) {
	var widget = options.widget;
	if (!widget.curtain) {
		widget.curtain = hui.build('div',{'class':'hui_curtain',style:'z-index:none'});
		
		var body = hui.get.firstByClass(document.body,'hui_body');
		if (!body) {
			body=document.body;
		}
		body.appendChild(widget.curtain);
		hui.listen(widget.curtain,'click',function() {
			if (widget.$curtainWasClicked) {
				widget.$curtainWasClicked();
			}
		});
	}
	var curtain = widget.curtain;
	if (options.transparent) {
		curtain.style.background='none';
	}
	else if (options.color) {
		if (options.color=='auto') {
			var color = hui.style.get(document.body,'background-color');
			if (color=='transparent' || color=='rgba(0, 0, 0, 0)') {
				color='#fff';
			}
			curtain.style.backgroundColor=color;
		} else {
			curtain.style.backgroundColor=options.color;
		}
	}
	if (hui.browser.msie) {
		curtain.style.height=hui.document.getHeight()+'px';
	} else {
		curtain.style.position='fixed';
		curtain.style.top='0';
		curtain.style.left='0';
		curtain.style.bottom='0';
		curtain.style.right='0';
	}
	curtain.style.zIndex=options.zIndex;
	if (options.transparent) {
		curtain.style.display='block';		
	} else {
		hui.style.setOpacity(curtain,0);
		curtain.style.display='block';
		hui.animate(curtain,'opacity',0.7,1000,{ease:hui.ease.slowFastSlow});
	}
};

hui.ui.hideCurtain = function(widget) {
	if (widget.curtain) {
		hui.animate(widget.curtain,'opacity',0,200,{hideOnComplete:true});
	}
};



///////////////////////////// Localization ////////////////////////////

/**
 * Get a localized text, defaults to english or the key
 * @param {String} key The key of the text
 * @returns {String} The localized string
 */
hui.ui.getText = function(key) {
	var parts = this.texts[key];
	if (!parts) {return key;}
	if (parts[this.language]) {
		return parts[this.language];
	} else {
		return parts.en;
	}
};

hui.ui.getTranslated = function(value) {
	if (!hui.isDefined(value) || hui.isString(value) || typeof(value) == 'number') {
		return value;
	}
	if (value[hui.ui.language]) {
		return value[hui.ui.language];
	}
	if (value[null]) {
		return value[null];
	}
	for (var key in value) {
		return value[key];
	}
};



//////////////////////////////// Message //////////////////////////////

hui.ui.confirm = function(options) {
	if (!options.name) {
		options.name = 'huiConfirm';
	}
	var alert = hui.ui.get(options.name);
	var ok;
	if (!alert) {
		alert = hui.ui.Alert.create(options);
		var cancel = hui.ui.Button.create({name:name+'_cancel',text : options.cancel || 'Cancel',highlighted:options.highlighted==='cancel'});
		cancel.listen({$click:function(){
			alert.hide();
			if (options.onCancel) {
				options.onCancel();
			}
			hui.ui.callDelegates(alert,'cancel');
		}});
		alert.addButton(cancel);
	
		ok = hui.ui.Button.create({name:name+'_ok',text : options.ok || 'OK',highlighted:options.highlighted==='ok'});
		alert.addButton(ok);
	} else {
		alert.update(options);
		ok = hui.ui.get(name+'_ok');
		ok.setText(options.ok || 'OK');
		ok.setHighlighted(options.highlighted=='ok');
		ok.clearListeners();
		hui.ui.get(name+'_cancel').setText(options.ok || 'Cancel');
		hui.ui.get(name+'_cancel').setHighlighted(options.highlighted=='cancel');
		if (options.cancel) {hui.ui.get(name+'_cancel').setText(options.cancel);}
	}
	ok.listen({$click:function(){
		alert.hide();
		if (options.onOK) {
			options.onOK();
		}
		hui.ui.callDelegates(alert,'ok');
	}});
	alert.show();
};

hui.ui.alert = function(options) {
	if (!this.alertBox) {
		this.alertBox = hui.ui.Alert.create(options);
		this.alertBoxButton = hui.ui.Button.create({name:'huiAlertBoxButton',text : 'OK'});
		this.alertBoxButton.listen({
			$click$huiAlertBoxButton : function() {
				hui.ui.alertBox.hide();
				if (hui.ui.alertBoxCallBack) {
					hui.ui.alertBoxCallBack();
					hui.ui.alertBoxCallBack = null;
				}
			}
		});
		this.alertBox.addButton(this.alertBoxButton);
	} else {
		this.alertBox.update(options);
	}
	this.alertBoxCallBack = options.onOK;
	this.alertBoxButton.setText(options.button ? options.button : 'OK');
	this.alertBox.show();
};

hui.ui.showMessage = function(options) {
	if (typeof(options)=='string') {
		// TODO: Backwards compatibility
		options={text:options};
	}
	if (options.delay) {
		hui.ui.messageDelayTimer = window.setTimeout(function() {
			options.delay=null;
			hui.ui.showMessage(options);
		},options.delay);
		return;
	}
	window.clearTimeout(hui.ui.messageDelayTimer);
	if (!hui.ui.message) {
		hui.ui.message = hui.build('div',{'class':'hui_message',html:'<div><div></div></div>'});
		if (!hui.browser.msie) {
			hui.style.setOpacity(hui.ui.message,0);
		}
		document.body.appendChild(hui.ui.message);
	}
	var text = hui.ui.getTranslated(options.text) || '';
	var inner = hui.ui.message.getElementsByTagName('div')[1];
	if (options.icon) {
		hui.dom.clear(inner);
		inner.appendChild(hui.ui.createIcon(options.icon,24));
		hui.dom.addText(inner,text);
	}
	else if (options.busy) {
		inner.innerHTML='<span class="hui_message_busy"></span>';
		hui.dom.addText(inner,text);
	} else {
		hui.dom.setText(inner,text);
	}
	hui.ui.message.style.display = 'block';
	hui.ui.message.style.zIndex = hui.ui.nextTopIndex();
	hui.ui.message.style.marginLeft = (hui.ui.message.clientWidth/-2)+'px';
	hui.ui.message.style.marginTop = hui.window.getScrollTop()+'px';
	if (hui.browser.opacity) {
		hui.animate(hui.ui.message,'opacity',1,300);
	}
	window.clearTimeout(hui.ui.messageTimer);
	if (options.duration) {
		hui.ui.messageTimer = window.setTimeout(hui.ui.hideMessage,options.duration);
	}
};

hui.ui.msg = hui.ui.showMessage;

hui.ui.msg.success = function(options) {
	options = hui.override({icon:'common/success',duration:2000},options);
	hui.ui.msg(options);
};

hui.ui.msg.fail = function(options) {
	options = hui.override({icon:'common/warning',duration:3000},options);
	hui.ui.msg(options);
};

hui.ui.hideMessage = function() {
	window.clearTimeout(hui.ui.messageDelayTimer);
	if (hui.ui.message) {
		if (hui.browser.opacity) {
			hui.animate(hui.ui.message,'opacity',0,300,{hideOnComplete:true});
		} else {
			hui.ui.message.style.display='none';
		}
	}
};

hui.ui.showToolTip = function(options) {
	var key = options.key || 'common';
	var t = hui.ui.toolTips[key];
	if (!t) {
		t = hui.build('div',{'class':'hui_tooltip',style:'display:none;',html:'<div><div></div></div>',parent:document.body});
		hui.ui.toolTips[key] = t;
	}
	t.onclick = function() {hui.ui.hideToolTip(options);};
	var n = hui.get(options.element);
	var pos = hui.position.get(n);
	hui.dom.setText(t.getElementsByTagName('div')[1],options.text);
	if (t.style.display=='none' && hui.browser.opacity) {
		hui.style.setOpacity(t,0);
	}
	hui.style.set(t,{'display':'block',zIndex:hui.ui.nextTopIndex()});
	hui.style.set(t,{left:(pos.left-t.clientWidth+4)+'px',top:(pos.top+2-(t.clientHeight/2)+(n.clientHeight/2))+'px'});
	if (hui.browser.opacity) {
		hui.animate(t,'opacity',1,300);
	}
};

hui.ui.hideToolTip = function(options) {
	var key = options ? options.key || 'common' : 'common';
	var t = hui.ui.toolTips[key];
	if (t) {
		if (!hui.browser.msie) {
			hui.animate(t,'opacity',0,300,{hideOnComplete:true});
		} else {
			t.style.display = 'none';
		}
	}
};



/////////////////////////////// Utilities /////////////////////////////

/**
 * Get the element of a widget if not already an element
 * @param widgetOrElement {Widget | Element} The widget to get the element for
 * @returns {Element} The element or null
 */
hui.ui.getElement = function(widgetOrElement) {
	if (hui.dom.isElement(widgetOrElement)) {
		return widgetOrElement;
	} else if (widgetOrElement.getElement) {
		return widgetOrElement.getElement();
	}
	return null;
};

hui.ui.isWithin = function(e,element) {
	e = hui.event(e);
	var offset = hui.position.get(element),
		dims = { width : element.offsetWidth, height : element.offsetHeight },
		left = e.getLeft(),
		top = e.getTop();
	return left > offset.left && left < offset.left+dims.width && top > offset.top && top < offset.top+dims.height;
};

hui.ui.getIconUrl = function(icon,size) {
	return hui.ui.context+'/hui/icons/'+icon+size+'.png';
};

hui.ui.createIcon = function(icon,size,tag) {
	return hui.build(tag || 'span',{'class':'hui_icon hui_icon_'+size,style:'background-image: url('+hui.ui.getIconUrl(icon,size)+')'});
};

hui.ui.wrapInField = function(element) {
	var w = hui.build('div',{'class':'hui_field',html:
		'<span class="hui_field_top"><span><span></span></span></span>'+
		'<span class="hui_field_middle"><span class="hui_field_middle"><span class="hui_field_content"></span></span></span>'+
		'<span class="hui_field_bottom"><span><span></span></span></span>'
	});
	hui.get.firstByClass(w,'hui_field_content').appendChild(element);
	return w;
};

/**
 * Add focus class to an element
 * @param options {Object} {element : «Element», class : «String»}
 */
hui.ui.addFocusClass = function(options) {
	var ce = options.classElement || options.element, c = options['class'];
	hui.listen(options.element,'focus',function() {
		hui.cls.add(ce,c);
		if (options.widget) {
			hui.ui.setKeyboardTarget(options.widget);
		}
	});
	hui.listen(options.element,'blur',function() {
		hui.cls.remove(ce,c);
		if (options.widget) {
			hui.ui.setKeyboardTarget(null);
		}
	});
};

hui.ui.keyboardTarget = null; // The widget currently accepting keyboard input

hui.ui.setKeyboardTarget = function(widget) {
	hui.ui.keyboardTarget = widget;
};


/**
 * Make a widget draw attention to itself
 * @param widget {Widget} The widget to stress
 */
hui.ui.stress = function(widget) {
	var e = hui.ui.getElement(widget);
	hui.effect.wiggle({element:e,duration:1000});
};


//////////////////////////// Positioning /////////////////////////////

hui.ui.positionAtElement = function(element,target,options) {
	options = options || {};
	element = hui.get(element);
	target = hui.get(target);
	var origDisplay = hui.style.get(element,'display');
	if (origDisplay=='none') {
		hui.style.set(element,{'visibility':'hidden','display':'block'});
	}
	var left = hui.position.getLeft(target),
		top = hui.position.getTop(target);
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
	hui.style.set(element,{'left':left+'px','top':top+'px'});
	if (origDisplay=='none') {
		hui.style.set(element,{'visibility':'visible','display':'none'});
	}
};

//////////////////// Delegating ////////////////////

hui.ui.extend = function(obj,options) {
	if (options!==undefined) {
		if (obj.options) {
			obj.options = hui.override(obj.options,options);
		}
		obj.element = hui.get(options.element);
		obj.name = options.name;
	}
	if (!obj.name) {
		hui.ui.latestObjectIndex++;
		obj.name = 'unnamed'+hui.ui.latestObjectIndex;
	}
  hui.ui.registerComponent(obj);
	obj.delegates = [];
	obj.listen = function(delegate) {
		hui.array.add(this.delegates,delegate);
		return this;
	};
	obj.unListen = function(delegate) {
		hui.array.remove(this.delegates,delegate);
	};
	obj.clearListeners = function() {
		this.delegates = [];
	};
	obj.fire = function(method,value,event) {
		return hui.ui.callDelegates(this,method,value,event);
	};
	obj.fireValueChange = function() {
		obj.fire('valueChanged',obj.value);
		hui.ui.firePropertyChange(obj,'value',obj.value);
		hui.ui.callAncestors(obj,'childValueChanged',obj.value);
	};
	obj.fireProperty = function(key,value) {
		hui.ui.firePropertyChange(this,key,value);
	};
	obj.fireSizeChange = function() {
		hui.ui.callAncestors(obj,'$$childSizeChanged');
	};
	if (!obj.getElement) {
		obj.getElement = function() {
			return this.element;
		};
	}
	if (!obj.destroy) {
		obj.destroy = function() {
            if (this.element) {
                hui.dom.remove(this.element);
            }
		};
	}
	if (!obj.valueForProperty) {
		obj.valueForProperty = function(p) {return this[p];};
	}
	if (obj.nodes && obj.element) {
		obj.nodes = hui.collect(obj.nodes,obj.element);
	}
};

hui.ui.registerComponent = function(component) {
	if (hui.ui.objects[component.name]) {
		hui.log('Widget replaced: '+component.name,hui.ui.objects[component.name]);
	}
	hui.ui.objects[component.name] = component;  
};

/** Send a message to all ancestors of a widget */
hui.ui.callAncestors = function(obj,method,value,event) {
	if (typeof(value)=='undefined') value=obj;
	var d = hui.ui.getAncestors(obj);
	for (var i=0; i < d.length; i++) {
		if (d[i][method]) {
			d[i][method](value,event);
		}
	}
};

/** Send a message to all descendants of a widget */
hui.ui.callDescendants = function(obj,method,value,event) {
	if (typeof(value)=='undefined') {
		value=obj;
	}
	if (method[0] !== '$') {
		method = '$'+method;
	}
	var d = hui.ui.getDescendants(obj);
	for (var i=0; i < d.length; i++) {
		if (d[i][method]) {
			d[i][method](value,event);
		}
	}
};

/** Signal that a widget has changed visibility */
hui.ui.callVisible = function(widget) {
	hui.ui.callDescendants(widget,'$visibilityChanged');
};

/** Listen for global events */
hui.ui.listen = function(delegate) {
	if (hui.ui.domReady && delegate.$ready) {
		delegate.$ready();
	}
	hui.ui.delegates.push(delegate);
};

hui.ui.unListen = function(listener) {
	hui.array.remove(hui.ui.delegates,listener);
};

hui.ui.callDelegates = function(obj,method,value,event) {
	if (typeof(value)=='undefined') {
		value=obj;
	}
	var result;
	if (obj.delegates) {
		for (var i=0; i < obj.delegates.length; i++) {
			var delegate = obj.delegates[i],
				thisResult,
				x = '$'+method+'$'+obj.name;
			if (obj.name && delegate[x]) {
				thisResult = delegate[x](value,event);
			} else if (delegate['$'+method]) {
				thisResult = delegate['$'+method](value,event);
			}
			if (result===undefined && thisResult!==undefined && typeof(thisResult)!='undefined') {
				result = thisResult;
			}
		}
	}
	var superResult = hui.ui.callSuperDelegates(obj,method,value,event);
	if (result===undefined && superResult!==undefined) {
		result = superResult;
	}
	return result;
};

/**
 * Sends a message to parent frames
 */
hui.ui.tellContainers = function(event,value) {
	if (window.parent!=window) {
		try {
			window.parent.hui.ui._tellContainers(event,value);
		} catch (e) {
			//hui.log('Unable to callContainers')
		}
	}
};

hui.ui._tellContainers = function(event,value) {
	hui.ui.callSuperDelegates({},event,value);
	if (window.parent!=window) {
		try {
			window.parent.hui.ui._tellContainers(event,value);
		} catch (e) {
			//hui.log('Unable to callContainers')
		}
	}
};

hui.ui.callSuperDelegates = function(obj,method,value,event) {
	if (typeof(value)=='undefined') value=obj;
	var result;
	for (var i=0; i < hui.ui.delegates.length; i++) {
		var delegate = hui.ui.delegates[i],
            thisResult;
		if (obj.name && delegate['$'+method+'$'+obj.name]) {
			thisResult = delegate['$'+method+'$'+obj.name](value,event);
		} else if (delegate['$'+method]) {
			thisResult = delegate['$'+method](value,event);
		}
		if (result===undefined && thisResult!==undefined && typeof(thisResult)!='undefined') {
			result = thisResult;
		}
	}
	return result;
};

hui.ui.resolveImageUrl = function(widget,img,width,height) {
	for (var i=0; i < widget.delegates.length; i++) {
		if (widget.delegates[i].$resolveImageUrl) {
			return widget.delegates[i].$resolveImageUrl(img,width,height);
		}
	}
	for (var j=0; j < hui.ui.delegates.length; j++) {
		var delegate = hui.ui.delegates[j];
		if (delegate.$resolveImageUrl) {
			return delegate.$resolveImageUrl(img,width,height);
		}
	}
	return null;
};

/** Load som UI from an URL */
hui.ui.include = function(options) {
	hui.ui.request({
		url : options.url,
		$text : function(html) {
			var container = hui.build('div',{html:html,parent:document.body});
			hui.dom.runScripts(container);
			options.$success();
		}
	});
};



////////////////////////////// Bindings ///////////////////////////

hui.ui.firePropertyChange = function(obj,name,value) {
	hui.ui.callDelegates(obj,'propertyChanged',{property:name,value:value});
};

hui.ui.bind = function(expression,delegate) {
	if (hui.isString(expression) && expression.charAt(0)=='@') {
		var pair = expression.substring(1).split('.');
		var obj = hui.ui.get(pair[0]);
		if (!obj) {
			hui.log('Unable to bind to '+expression);
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

hui.ui.handleRequestError = function(widget) {
	hui.log('General request error received');
	var result = hui.ui.callSuperDelegates(widget || this,'requestError');
	if (!result) {
		hui.ui.confirmOverlay({
			element : document.body,
			text : hui.ui.getText('request_error'),
			okText : hui.ui.getText('reload_page'),
			cancelText : hui.ui.getText('continue'),
			onOk : function() {
				document.location.reload();
			}
		});
	}
};

hui.ui.handleForbidden = function(widget) {
	hui.log('General access denied received');
	var result = hui.ui.callSuperDelegates(widget || this,'accessDenied');
	if (!result) {
		hui.ui.confirmOverlay({
			element : document.body,
			text : hui.ui.getText('access_denied'),
			okText : hui.ui.getText('reload_page'),
			cancelText : hui.ui.getText('continue'),
			onOk : function() {
				document.location.reload();
			}
		});
	}
};

hui.ui.request = function(options) {
	options = hui.override({method:'post',parameters:{}},options);
	if (options.json) {
		for (var key in options.json) {
			options.parameters[key]=hui.string.toJSON(options.json[key]);
		}
	}
	var success = options.$success,
		obj = options.$object,
		text = options.$text,
		xml = options.$xml,
		failure = options.$failure,
		forbidden = options.$forbidden,
		message = options.message;
	options.$success = function(t) {
		if (message) {
			if (message.success) {
				hui.ui.showMessage({text:message.success,icon:'common/success',duration:message.duration || 2000});
			} else if (message.start) {
				hui.ui.hideMessage();
			}
		}
		var str,json;
		if (typeof(success)=='string') {
			if (!hui.request.isXMLResponse(t)) {
				str = t.responseText.replace(/^\s+|\s+$/g, '');
				if (str.length>0) {
					json = hui.string.fromJSON(t.responseText);
				} else {
					json = '';
				}
				hui.ui.callDelegates(json,'success$'+success);
			} else {
				hui.ui.callDelegates(t,'success$'+success);
			}
		} else if (xml && hui.request.isXMLResponse(t)) {
			xml(t.responseXML);
		} else if (obj) {
			str = t.responseText.replace(/^\s+|\s+$/g, '');
			if (str.length>0) {
				json = hui.string.fromJSON(t.responseText);
			} else {
				json = null;
			}
			obj(json);
		} else if (typeof(success)=='function') {
			success(t);
		} else if (text) {
			text(t.responseText);
		}
	};
	options.$failure = function(t) {
		if (typeof(failure)=='string') {
			hui.ui.callDelegates(t,'failure$'+failure);
		} else if (typeof(failure)=='function') {
			failure(t);
		} else {
			if (options.message && options.message.start) {
				hui.ui.hideMessage();
			}
			hui.ui.handleRequestError();
		}
	};
	options.$exception = options.$exception || function(e,t) {
		hui.log(e);
		hui.log(t);
		throw e;
	};
	options.$forbidden = function(t) {
		if (options.message && options.message.start) {
			hui.ui.hideMessage();
		}
		if (forbidden) {
			forbidden(t);
		} else {
			options.$failure(t);
			hui.ui.handleForbidden();
		}
	};
	if (options.message && options.message.start) {
		hui.ui.msg({text:options.message.start,busy:true,delay:options.message.delay});
	}
	hui.request(options);
};

hui.ui.parseItems = function(doc) {
	var root = doc.documentElement;
	var out = [];
	hui.ui.parseSubItems(root,out);
	return out;
};

hui.ui.parseSubItems = function(parent,array) {
	var children = parent.childNodes;
	for (var i=0; i < children.length; i++) {
		var node = children[i];
		if (node.nodeType==1 && node.nodeName=='title') {
			array.push({title:node.getAttribute('title'),type:'title'});
		} else if (node.nodeType==1 && node.nodeName=='item') {
			var sub = [];
			hui.ui.parseSubItems(node,sub);
			array.push({
				text : node.getAttribute('text'),
				title : node.getAttribute('title'),
				value : node.getAttribute('value'),
				icon : node.getAttribute('icon'),
				kind : node.getAttribute('kind'),
				badge : node.getAttribute('badge'),
				children : sub
			});
		}
	}
};

/**
 * Import some widgets by name
 * @param {Array} names Array of widgets to import
 * @param {Function} func The function to call when finished
 */
hui.ui.require = function(names,func) {
	for (var i = names.length - 1; i >= 0; i--){
		names[i] = hui.ui.context+'hui/js/'+names[i]+'.js';
	}
	hui.require(names,func);
};

if (window.define) {
	define('hui.ui',hui.ui);
}

hui.onReady(function() {
	hui.listen(window,'resize',hui.ui._resize);
	hui.ui.reLayout();
	hui.ui.domReady = true;
	if (window.parent && window.parent.hui && window.parent.hui.ui) {
		window.parent.hui.ui._frameLoaded(window);
	}
	for (var i=0; i < hui.ui.delayedUntilReady.length; i++) {
		hui.ui.delayedUntilReady[i]();
	}
	// Call super delegates after delayedUntilReady...
	hui.ui.callSuperDelegates(this,'ready');
});

/* EOF */

/**
 * An image slideshow viewer
 * <pre><strong>options:</strong> {
 *  element : «Element | ID»,
 *  name : «String»,
 *  perimeter : «Integer»,
 *  sizeSnap : «Integer»,
 *  margin : «Integer»,
 *  ease : «Function»,
 *  easeEnd : «Function»,
 *  easeAuto : «Function»,
 *  easeReturn : «Function»,
 *  transition : «Integer»,
 *  transitionEnd : «Integer»,
 *  transitionReturn : «Integer»,
 *  images : «Array»,
 *  listener : «Object»
 * }
 * </pre>
 * @constructor
 */
hui.ui.ImageViewer = function(options) {
	
	this.options = hui.override({
		maxWidth : 800,
		maxHeight : 600,
		perimeter : 100,
		sizeSnap : 100,
		margin : 0,
		ease : hui.ease.slowFastSlow,
		easeEnd : hui.ease.bounce,
		easeAuto : hui.ease.slowFastSlow,
		easeReturn : hui.ease.cubicInOut,
		transition : 400,
		transitionEnd : 1000,
		transitionReturn : 300,
		images : []
	},options);
	
	// Collect elements ...
	this.element = hui.get(options.element);

	this.box = this.options.box;
	
	// State ...
	this.dirty = false;
	this.width = 600;
	this.height = 460;
	this.index = 0;
	this.position = 0; // pixels
	this.playing = false;
	this.name = options.name;
	this.images = options.images || [];

	hui.ui.extend(this);
	
	// Behavior ...
	this.box.listen(this);
	this._attach();
	this._attachDrag();
	
	if (options.listener) {
		this.listen(options.listener);
	}
}

/**
 * Creates a new image viewer
 */
hui.ui.ImageViewer.create = function(options) {
	options = options || {};
	var element = options.element = hui.build('div',
		{'class':'hui_imageviewer',
		html:
		'<div class="hui_imageviewer_viewer"><div class="hui_imageviewer_inner_viewer"></div></div>'+
		'<div class="hui_imageviewer_text"></div>'+
		'<div class="hui_imageviewer_status"></div>'+
		'<div class="hui_imageviewer_controller"><div><div>'+
		'<a class="hui_imageviewer_previous"></a>'+
		'<a class="hui_imageviewer_play"></a>'+
		'<a class="hui_imageviewer_next"></a>'+
		'<a class="hui_imageviewer_close"></a>'+
		'</div></div></div>'});
	var box = options.box = hui.ui.Box.create({variant:'plain',absolute:true,modal:true,closable:true});
	box.add(element);
	box.addToDocument();
	return new hui.ui.ImageViewer(options);
}

hui.ui.ImageViewer.prototype = {

	nodes : {
		viewer : '.hui_imageviewer_viewer',
		innerViewer : '.hui_imageviewer_inner_viewer',

		status : '.hui_imageviewer_status',
		text : '.hui_imageviewer_text',

		previous : '.hui_imageviewer_previous',
		controller : '.hui_imageviewer_controller',
		next : '.hui_imageviewer_next',
		play : '.hui_imageviewer_play',
		close : '.hui_imageviewer_close'
	},

	_attach : function() {
		var self = this;
		this.nodes.next.onclick = function() {
			self.next(true);
		}
		this.nodes.previous.onclick = function() {
			self.previous(true);
		}
		this.nodes.play.onclick = function() {
			self.playOrPause();
		}
		this.nodes.close.onclick = this.hide.bind(this);

		this._timer = function() {
			self.next(false);
		}
		this._keyListener = function(e) {
			e = hui.event(e);
			if (e.escapeKey) {
				self.hide();
			} else if (!self.zoomed) {
				if (e.rightKey) {
					self.next(true);
				} else if (e.leftKey) {
					self.previous(true);
				} else if (e.returnKey) {
					self.playOrPause();
				}				
			}
		},
		hui.listen(this.nodes.viewer,'mousemove',this._onMouseMove.bind(this));
		hui.listen(this.nodes.controller,'mouseover',function() {
			self.overController = true;
		});
		hui.listen(this.nodes.controller,'mouseout',function() {
			self.overController = false;
		});
		hui.listen(this.nodes.viewer,'mouseout',function(e) {
			if (!hui.ui.isWithin(e,this.nodes.viewer)) {
				self._hideController();
			}
		}.bind(this));
	},
	_draw : function(pos) {
		if (hui.browser.webkit) {
			this.nodes.innerViewer.style.webkitTransform = 'translate3d(' + this.position + 'px,0,0)';			
		} else {
			this.nodes.innerViewer.style.marginLeft = this.position + 'px';
		}
	},
	_attachDrag : function() {
		var initial = 0;
		var left = 0;
		var scrl = 0;
		var viewer = this.nodes.viewer;
		var inner = this.nodes.innerViewer;
		var max = 0;
		hui.drag.register({
			touch : true,
			element : this.nodes.innerViewer,
			onBeforeMove : function(e) {
				initial = e.getLeft();
				scrl = this.position;
				max = (this.images.length-1) * this.width * -1;
			}.bind(this),
			onMove : function(e) {
				left = e.getLeft();
				var pos = (scrl - (initial - left));
				if (pos > 0) {
					pos = (Math.exp(pos * -0.013) -1) * -80;
				}
				if (pos < max) {
					pos = (Math.exp((pos - max) * 0.013) -1) * 80 + max;
				}
				this.position = pos;
				this._draw();
			}.bind(this),
			onAfterMove : function() {
				var func = (initial - left) < 0 ? Math.floor : Math.ceil;
				this.index = func(this.position * -1 / this.width);
				var num = this.images.length - 1;
				if (this.index==this.images.length) {
					this.index = 0;
				} else if (this.index < 0) {
					this.index = this.images.length - 1;
				} else {
					num = 1;
				}
				
				this._goToImage(true,num,false,true);
			}.bind(this),
			onNotMoved : this._zoom.bind(this)
		})
	},
	_onMouseMove : function() {
		window.clearTimeout(this.ctrlHider);
		if (this._shouldShowController()) {
			this.ctrlHider = window.setTimeout(this._hideController.bind(this),2000);
			if (!hui.browser.opacity) {
				this.nodes.controller.style.display='block';
			} else {
				hui.effect.fadeIn({element:this.nodes.controller,duration:200});
			}
		}
	},
	_hideController : function() {
		if (!this.overController) {
			if (!hui.browser.opacity) {
				this.nodes.controller.style.display='none';
			} else {
				hui.effect.fadeOut({element:this.nodes.controller,duration:500});
			}
		}
	},
	_getLargestSize : function(canvas,image) {
		return hui.fit(image,canvas,{upscale:false});
	},
	_calculateSize : function() {
		var snap = this.options.sizeSnap;
		var newWidth = hui.window.getViewWidth() - this.options.perimeter;
		newWidth = Math.floor(newWidth / snap) * snap;
		newWidth = Math.min(newWidth, this.options.maxWidth);
		var newHeight = hui.window.getViewHeight() - this.options.perimeter;
		newHeight = Math.floor(newHeight / snap) * snap;
		newHeight = Math.min(newHeight, this.options.maxHeight);
		var maxWidth = 0;
		var maxHeight = 0;
		for (var i = 0; i < this.images.length; i++) {
			var dims = this._getLargestSize({
				width: newWidth,
				height: newHeight
			}, this.images[i]);
			maxWidth = Math.max(maxWidth, dims.width);
			maxHeight = Math.max(maxHeight, dims.height);
		};
		newHeight = Math.floor(Math.min(newHeight, maxHeight));
		newWidth = Math.floor(Math.min(newWidth, maxWidth));

		if (newWidth != this.width || newHeight != this.height) {
			this.width = newWidth;
			this.height = newHeight;
			this.dirty = true;
		}

	},
	_updateUI : function() {
		if (this.dirty) {
			this.nodes.innerViewer.innerHTML='';
			for (var i=0; i < this.images.length; i++) {
				var element = hui.build('div',{'class':'hui_imageviewer_image'});
				hui.style.set(element,{width: (this.width + this.options.margin) + 'px',height : (this.height-1)+'px' });
				this.nodes.innerViewer.appendChild(element);
			};
			this.nodes.controller.style.display = this._shouldShowController() ? 'block' : 'none';
			this.dirty = false;
			this._preload();
		}
	},
	_shouldShowController : function() {
		return this.images.length > 1;
	},
	_goToImage : function(animate,num,user,drag) {
		var initial = this.position;
		var target = this.position = this.index * (this.width + this.options.margin) * -1;
		if (animate) {
			var duration, ease;
			if (drag) {
				duration = 200 * num;
				ease = hui.ease.fastSlow;
				ease = hui.ease.quadOut;
			}
			else if (num > 1) {
				duration = Math.min(num * this.options.transitionReturn, 2000)
				ease = this.options.easeReturn;
			} else {
				var end = this.index == 0 || this.index == this.images.length - 1;
				ease = (end ? this.options.easeEnd : this.options.ease);
				if (!user) {
					ease = this.options.easeAuto;
				}
				duration = (end ? this.options.transitionEnd : this.options.transition);
			}
			hui.animate({
				node : this.nodes.innerViewer, 
				css : {marginLeft : target + 'px'}, 
				duration : duration,
				ease : ease
				,$render : function(node,v) {
					this.position = initial + (target - initial) * v;
					this._draw();
				}.bind(this)
			});
		} else {
			this._draw();
		}
		this._drawText();
	},
	
	_drawText : function() {
		var text = this.images[this.index].text;
		if (text) {
			this.nodes.text.innerHTML = text;
			this.nodes.text.style.display = 'block';
		} else {
			this.nodes.text.innerHTML = '';
			this.nodes.text.style.display = 'none';
		}		
	},
	
	// Show / hide ...

	/** Show the image viewer starting at the image with a certain id. Will not show if image is not found
	 * @param {Integer} id The id if the image to start with
	 */
	showById: function(id) {
		for (var i=0; i < this.images.length; i++) {
			if (this.images[i].id==id) {
				this.show(i);
				break;
			}
		};
	},
	/** Show the image viewer
	 * @param {Integer} index? Optional index to start from (zero-based)
	 */
	show: function(index) {
		this.index = index || 0;
		this._calculateSize();
		this._updateUI();
		var margin = this.options.margin;
		hui.style.set(this.element, {
			width: (this.width + margin) + 'px',
			height: (this.height + margin * 2 - 1) + 'px'
		});
		hui.style.set(this.nodes.viewer, {
			width: (this.width + margin) + 'px',
			height: (this.height - 1) + 'px'
		});
		hui.style.set(this.nodes.innerViewer, {
			width: ((this.width + margin) * this.images.length) + 'px',
			height: (this.height - 1) + 'px'
		});
		hui.style.set(this.nodes.controller, {
			marginLeft: ((this.width - 160) / 2 + margin * 0.5) + 'px',
			display: 'none'
		});
		this.box.show();
		this._goToImage(false,0,false);
		hui.listen(document,'keydown',this._keyListener);
		this.visible = true;
		this._setHash(true);
	},
	_setHash : function(visible) {
		return; // Disabled
		if (!this._listening) {
			this._listening = true;
			if (!hui.browser.msie6 && !hui.browser.msie7) {
				hui.listen(window,'hashchange',this._onHashChange.bind(this));
			}
		}
		if (visible) {
			document.location='#imageviewer';
		} else {
			hui.location.clearHash();
		}
	},
	_onHashChange : function() {
		if (this._changing) return;
		this._changing = true;
		if (hui.location.hasHash('imageviewer') && !this.visible) {
			this.show();
		} else if (!hui.location.hasHash('imageviewer') && this.visible) {
			this.hide();
		}
		this._changing = false;
	},
	/** Hide the image viewer */
	hide: function() {
		this._hide();
	},
	_hide : function() {
		this.pause();
		this.box.hide();
		this._endZoom();
		hui.unListen(document,'keydown',this._keyListener);
		this.visible = false;
		this._setHash(false);	
	},


	// Listeners ...

	/** @private */
	$boxCurtainWasClicked : function() {
		this.hide();
	},
	/** @private */
	$boxWasClosed : function() {
		this.hide();
	},
	
	
	// Data handling ...
	
	/** Clear all images in the stack */
	clearImages : function() {
		this.images = [];
		this.dirty = true;
	},
	/**
	 * Add multiple images to the stack
	 * @param {Array} images An array of image objects
	 */
	addImages : function(images) {
		for (var i=0; i < images.length; i++) {
			this.addImage(images[i]);
		};
	},
	/**
	 * Add an image to the stack
	 * @param {Object} img An image object representing an image
	 */
	addImage : function(img) {
		this.images.push(img);
		this.dirty = true;
	},
	
	
	// Playback...
	
	/** Start playing slideshow */
	play : function() {
		if (!this.interval) {
			this.interval = window.setInterval(this._timer,6000);
		}
		this.next(false);
		this.playing=true;
		this.nodes.play.className='hui_imageviewer_pause';
	},
	/** Pauseslideshow */
	pause : function() {
		window.clearInterval(this.interval);
		this.interval = null;
		this.nodes.play.className='hui_imageviewer_play';
		this.playing = false;
	},
	/** Start or pause slideshow */
	playOrPause : function() {
		if (this.playing) {
			this.pause();
		} else {
			this.play();
		}
	},
	_resetPlay : function() {
		if (this.playing) {
			window.clearInterval(this.interval);
			this.interval = window.setInterval(this._timer,6000);
		}
	},
	/** Go to the previous image
	 * @param {Boolean} user If it is initiated by the user
	 */
	previous : function(user) {
		var num = 1;
		this.index--;
		if (this.index < 0) {
			this.index = this.images.length - 1;
			num = this.images.length - 1;
		}
		this._goToImage(true,num,user);
		this._resetPlay();
	},
	/** Go to the next image
	 * @param {Boolean} user If it is initiated by the user
 	 */
	next : function(user) {
		var num = 1;
		this.index++;
		if (this.index==this.images.length) {
			this.index = 0;
			num = this.images.length - 1;
		}
		this._goToImage(true,num,user);
		this._resetPlay();
	},
	
	
	
	
	
	
	// Preloading ...
	
	_preload : function() {
		var guiLoader = new hui.Preloader();
		guiLoader.addImages(hui.ui.context+'/hui/gfx/imageviewer_controls.png');
		var self = this;
		guiLoader.setDelegate({allImagesDidLoad:function() {self._preloadImages()}});
		guiLoader.load();
	},
	_preloadImages : function() {
		var loader = new hui.Preloader();
		loader.setDelegate(this);
		for (var i=0; i < this.images.length; i++) {
			var url = hui.ui.resolveImageUrl(this,this.images[i],this.width,this.height);
			if (url!==null) {
				loader.addImages(url);
			}
		};
		this.nodes.status.innerHTML = '0%';
		this.nodes.status.style.display = '';
		loader.load(this.index);
	},
	/** @private */
	allImagesDidLoad : function() {
		this.nodes.status.style.display = 'none';
	},
	/** @private */
	imageDidLoad : function(loaded,total,index) {
		this.nodes.status.innerHTML = Math.round(loaded/total*100)+'%';
		var url = hui.ui.resolveImageUrl(this,this.images[index],this.width,this.height);
		url = url.replace(/&amp;/g,'&');
		this.nodes.innerViewer.childNodes[index].style.backgroundImage="url('"+url+"')";
		hui.cls.set(this.nodes.innerViewer.childNodes[index],'hui_imageviewer_image_abort',false);
		hui.cls.set(this.nodes.innerViewer.childNodes[index],'hui_imageviewer_image_error',false);
	},
	/** @private */
	imageDidGiveError : function(loaded,total,index) {
		hui.cls.set(this.nodes.innerViewer.childNodes[index],'hui_imageviewer_image_error',true);
	},
	/** @private */
	imageDidAbort : function(loaded,total,index) {
		hui.cls.set(this.nodes.innerViewer.childNodes[index],'hui_imageviewer_image_abort',true);
	},
	
	
	
	
	// Zooming ...
	
	zoomed : false,

	_zoom : function(e) {
		var img = this.images[this.index];
		if (img.width <= this.width && img.height <= this.height) {
			return; // Don't zoom if small
		}
		if (!this.zoomer) {
			this.zoomer = hui.build('div',{
				'class' : 'hui_imageviewer_zoomer',
				'style' : 'width:'+this.nodes.viewer.clientWidth+'px;height:'+this.nodes.viewer.clientHeight+'px'
			});
			this.element.insertBefore(this.zoomer,hui.dom.firstChild(this.element));
			hui.listen(this.zoomer,'mousemove',this._onZoomMove.bind(this));
			hui.listen(this.zoomer,'click',this._endZoom.bind(this));
		}
		this._hideController();
		this.pause();
		var size = this._getLargestSize({width:2000,height:2000},img);
		var url = hui.ui.resolveImageUrl(this,img,size.width,size.height);
		var top = Math.max(0, Math.round((this.nodes.viewer.clientHeight - size.height) / 2));
		this.zoomer.innerHTML = '<div style="width:'+size.width+'px;height:'+size.height+'px; margin: 0 auto;"><img src="'+url+'" style="margin-top: '+ top + 'px" /></div>';
		this.zoomer.style.display = 'block';
		this.zoomInfo = {width:size.width,height:size.height};
		this._onZoomMove(e);
		this.zoomed = true;
	},
	_onZoomMove : function(e) {
		if (!this.zoomInfo) {
			return;
		}
		var offset = hui.position.get(this.zoomer);
		e = new hui.Event(e);
		var x = (e.getLeft() - offset.left) / this.zoomer.clientWidth * (this.zoomInfo.width - this.zoomer.clientWidth);
		var y = (e.getTop() - offset.top) / this.zoomer.clientHeight * (this.zoomInfo.height - this.zoomer.clientHeight);

		this.zoomer.scrollLeft = x;
		this.zoomer.scrollTop = y;
	},
	_endZoom : function() {
		if (this.zoomer) {
			this.zoomer.style.display='none';
			this.zoomed = false;			
		}
	}
	
}

if (window.define) {
	define('hui.ui.ImageViewer',hui.ui.ImageViewer);
}

/* EOF */

/**
 * @constructor
 * @param {Object} options The options : {modal:false}
 */
hui.ui.Box = function(options) {
	this.options = hui.override({},options);
	this.name = options.name;
	this.element = hui.get(options.element);
	this.visible = !this.options.absolute;
	hui.ui.extend(this);
	if (this.nodes.close) {
		hui.listen(this.nodes.close,'click',this._close.bind(this));
	}
};

/**
 * Creates a new box widget
 * @param {Object} options The options : {width:0,padding:0,absolute:false,closable:false}
 */
hui.ui.Box.create = function(options) {
	options = options || {};
  var variant = options.variant || 'standard';
  var complex = variant !== 'plain';
  var html = (options.closable ? '<a class="hui_box_close hui_box_close_' + variant + '" href="#"></a>' : '');
  if (complex) {
    html += '<div class="hui_box_top"><div><div></div></div></div>'+
      '<div class="hui_box_middle"><div class="hui_box_middle">';
  }
  if (options.title) {
    html+='<div class="hui_box_header"><strong class="hui_box_title">'+hui.string.escape(hui.ui.getTranslated(options.title))+'</strong></div>';
  }
  html += '<div class="hui_box_body" style="'+
			(options.padding ? 'padding: '+options.padding+'px;' : '')+
			(options.width ? 'width: '+options.width+'px;' : '')+
  '"></div>';
  if (complex) {
    html += '</div></div>'+
      '<div class="hui_box_bottom"><div><div></div></div></div>';
  }

	options.element = hui.build('div',{
		'class' : 'hui_box hui_box_' + variant,
		html : html,
		style : options.width ? options.width+'px' : null
	});
  if (options.absolute) {
    hui.cls.add(options.element,'hui_box_absolute');
  }
  if (variant) {
    hui.cls.add(options.element,'hui_box_' + variant);
  } 
	return new hui.ui.Box(options);
};

hui.ui.Box.prototype = {
	nodes : {
    	body : '.hui_box_body',
    	close : '.hui_box_close'
	},
	_close : function(e) {
		hui.stop(e);
		this.hide();
		this.fire('boxWasClosed'); // Deprecated
		this.fire('close');
	},
	shake : function() {
		hui.effect.shake({element:this.element});
	},

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
    var body = this.nodes.body;
		if (widget.getElement) {
			body.appendChild(widget.getElement());
		} else {
			body.appendChild(widget);
		}
	},
	/**
	 * Shows the box
	 */
	show : function() {
		var e = this.element;
		if (this.options.modal) {
			var index = hui.ui.nextPanelIndex();
			e.style.zIndex = index+1;
			hui.ui.showCurtain({widget:this,zIndex:index});
		}
		if (this.options.absolute) {
			hui.style.set(e,{ display : 'block', visibility : 'hidden' });
			var w = e.clientWidth;
			var top = (hui.window.getViewHeight() - e.clientHeight) / 2 + hui.window.getScrollTop();
			hui.style.set(e,{
				marginLeft : (w/-2)+'px',
				top : top+'px',
				display : 'block',
				visibility : 'visible'
			});
		} else {
			e.style.display = 'block';
		}
		this.visible = true;
		hui.ui.callVisible(this);
	},
	/** If the box is visible */
	isVisible : function() {
		return this.visible;
	},
	/** @private */
	$$layout : function() {
		if (this.options.absolute && this.visible) {
			var e = this.element;
			var w = e.clientWidth;
			var top = (hui.window.getViewHeight()-e.clientHeight)/2+hui.window.getScrollTop();
			hui.style.set(e,{'marginLeft':(w/-2)+'px',top:top+'px'});
		}
	},
	/**
	 * Hides the box
	 */
	hide : function() {
		hui.ui.hideCurtain(this);
		this.element.style.display='none';
		this.visible = false;
		hui.ui.callVisible(this);
	},
	/** @private */
	$curtainWasClicked : function() {
		this.fire('boxCurtainWasClicked');
		if (this.options.curtainCloses) {
			this._close();
		}
	}
};

/** @constructor */
hui.ui.SearchField = function(options) {
	this.options = hui.override({expandedWidth:null},options);
	this.element = hui.get(options.element);
	this.name = options.name;
	this.field = hui.get.firstByTag(this.element,'input');
	this.value = this.field.value;
	this.adaptive = hui.cls.has(this.element,'hui_searchfield_adaptive');
	this.initialWidth = null;
	hui.ui.extend(this);
	this._addBehavior();

	if (this.value!=='') {
		this._updateClass()
	}
}

hui.ui.SearchField.create = function(options) {
	options = options || {};
	
	options.element = hui.build('span',{
		'class' : options.adaptive ? 'hui_searchfield hui_searchfield_adaptive' : 'hui_searchfield',
		html : '<em class="hui_searchfield_placeholder"></em><a href="javascript:void(0);" class="hui_searchfield_reset"></a><span><span><input type="text"/></span></span>'
	});
	return new hui.ui.SearchField(options);
}

hui.ui.SearchField.prototype = {
	_addBehavior : function() {
		var self = this;
		hui.listen(this.field,'keyup',this._onKeyUp.bind(this));
		var reset = hui.get.firstByTag(this.element,'a');
		reset.tabIndex=-1;
		if (!hui.browser.ipad) {
			var focus = function() {self.field.focus();self.field.select()};
			hui.listen(this.element,'mousedown',focus);
			hui.listen(this.element,'mouseup',focus);
			hui.listen(hui.get.firstByTag(this.element,'em'),'mousedown',focus);
		} else {
			var focus = function() {self.field.focus();};
			hui.listen(hui.get.firstByTag(this.element,'em'),'click',focus);
		}
		hui.listen(reset,'mousedown',function(e) {
			hui.stop(e);
			self.reset();
			focus()
		});
		hui.listen(this.field,'focus',this._onFocus.bind(this));
		hui.listen(this.field,'blur',this._onBlur.bind(this));
	},
	_onFocus : function() {
		hui.ui.setKeyboardTarget(this);
		this.focused = true;
		this._updateClass();
		if (this.options.expandedWidth > 0) {
			if (this.initialWidth==null) {
				this.initialWidth = parseInt(hui.style.get(this.element,'width'));
			}
			hui.animate(this.element,'width',this.options.expandedWidth+'px',500,{ease:hui.ease.slowFastSlow});
		}
	},
	_onBlur : function() {
		hui.ui.setKeyboardTarget(null);
		this.focused = false;
		this._updateClass();
		if (this.initialWidth!==null) {
			hui.animate(this.element,'width',this.initialWidth+'px',500,{ease:hui.ease.slowFastSlow,delay:100});
		}
	},
	_onKeyUp : function(e) {
		this._fieldChanged();
		if (e.keyCode===hui.KEY_RETURN) {
			this.fire('submit');
		}
	},
	focus : function() {
		this.field.focus();
	},
	setValue : function(value) {
		this.field.value = value===undefined || value===null ? '' : value;
		this._fieldChanged();
	},
	getValue : function() {
		return this.field.value;
	},
	isEmpty : function() {
		return this.field.value=='';
	},
	isBlank : function() {
		return hui.isBlank(this.field.value);
	},
	reset : function() {
		this.field.value='';
		this._fieldChanged();
	},
	/** @private */
	_updateClass : function() {
		var className = 'hui_searchfield';
		if (this.adaptive) {
			className+=' hui_searchfield_adaptive';
		}
		if (this.focused && this.value!='') {
			className+=' hui_searchfield_focus_dirty';
		} else if (this.focused) {
			className+=' hui_searchfield_focus';
		} else if (this.value!='') {
			className+=' hui_searchfield_dirty';
		}
		this.element.className=className;
	},
	_fieldChanged : function() {
		if (this.field.value!=this.value) {
			this.value = this.field.value;
			this._updateClass();
			this.fireValueChange();
		}
	}
}

if (window.define) {
	define('hui.ui.SearchField',hui.ui.SearchField);
}
/* EOF */

/**
 * @constructor
 */
hui.ui.Overlay = function(options) {
	this.options = options;
	this.element = hui.get(options.element);
	this.content = hui.get.byClass(this.element,'hui_inner_overlay')[1];
	this.name = options.name;
	this.icons = {};
	this.visible = false;
	hui.ui.extend(this);
	this._addBehavior();
}

/**
 * Creates a new overlay
 */
hui.ui.Overlay.create = function(options) {
	options = options || {};
	var e = options.element = hui.build('div',{className:'hui_overlay'+(options.variant ? ' hui_overlay_'+options.variant : ''),style:'display:none',html:'<div class="hui_inner_overlay"><div class="hui_inner_overlay"></div></div>'});
	document.body.appendChild(e);
	return new hui.ui.Overlay(options);
}

hui.ui.Overlay.prototype = {
	_addBehavior : function() {
		var self = this;
/*		this.hider = function(e) {
			if (self.boundElement) {
				if (hui.ui.isWithin(e,self.boundElement) || hui.ui.isWithin(e,self.element)) return;
				// TODO: should be unreg'ed but it fails
				//self.boundElement.stopObserving(self.hider);
				hui.cls.remove(self.boundElement,'hui_overlay_bound');
				self.boundElement = null;
				self.hide();
			}
		}
		hui.listen(this.element,'mouseout',this.hider);*/
	},
	addIcon : function(key,icon) {
		var self = this;
		var element = hui.build('div',{className:'hui_overlay_icon'});
		element.style.backgroundImage='url('+hui.ui.getIconUrl(icon,32)+')';
		hui.listen(element,'click',function(e) {
			self._iconWasClicked(key,e);
		});
		this.icons[key]=element;
		this.content.appendChild(element);
	},
	addText : function(text) {
		this.content.appendChild(hui.build('span',{'class':'hui_overlay_text',text:text}));
	},
	add : function(widget) {
		this.content.appendChild(widget.getElement());
	},
	hideIcons : function(keys) {
		for (var i=0; i < keys.length; i++) {
			this.icons[keys[i]].style.display='none';
		};
	},
	showIcons : function(keys) {
		for (var i=0; i < keys.length; i++) {
			this.icons[keys[i]].style.display='';
		};
	},
	_iconWasClicked : function(key,e) {
		hui.ui.callDelegates(this,'iconWasClicked',key,e);
	},
	showAtElement : function(element,options) {
		options = options || {};
		hui.ui.positionAtElement(this.element,element,options);
		if (options.autoHide) {
			// important to do even if visible, sine element may have changed
			this._autoHide(element);
		}
		if (this.visible) {
			return;
		}
		if (hui.browser.msie) {
			this.element.style.display = 'block';
		} else {
			hui.style.set(this.element,{display : 'block',opacity : 0});
			hui.animate(this.element,'opacity',1,150);
		}
		var zIndex = options.zIndex === undefined ? options.zIndex : hui.ui.nextAlertIndex();
		if (this.options.modal) {
			this.element.style.zIndex = hui.ui.nextAlertIndex();
			hui.ui.showCurtain({ widget : this, zIndex : zIndex });
		} else {
			this.element.style.zIndex = zIndex;
		}
		this.visible = true;
	},
	_autoHide : function(element) {
		hui.cls.add(element,'hui_overlay_bound');
		hui.unListen(document.body,'mousemove',this._hider);
		this._hider = function(e) {
			if (!hui.ui.isWithin(e,element) && !hui.ui.isWithin(e,this.element)) {
				try {
					hui.unListen(document.body,'mousemove',this._hider);
					hui.cls.remove(element,'hui_overlay_bound');
					this.hide();
				} catch (e) {
					hui.log('unable to stop listening: document='+document);
				}
			}
		}.bind(this)
		hui.listen(document.body,'mousemove',this._hider);
	},
	show : function(options) {
		options = options || {};
		if (!this.visible) {
			hui.style.set(this.element,{'display':'block',visibility:'hidden'});
		}
		if (options.element) {
			hui.position.place({
				source : {element:this.element,vertical:0,horizontal:.5},
				target : {element:options.element,vertical:.5,horizontal:.5},
				insideViewPort : true,
				viewPartMargin : 9
			});
		}
		if (options.autoHide && options.element) {
			this._autoHide(options.element);
		}
		if (this.visible) return;
		hui.effect.bounceIn({element:this.element});
		this.visible = true;
		if (this.options.modal) {
			var zIndex = hui.ui.nextAlertIndex();
			this.element.style.zIndex=zIndex+1;
			hui.ui.showCurtain({widget:this,zIndex:zIndex,color:'auto'});
		}
	},
	/** private */
	$curtainWasClicked : function() {
		this.hide();
	},
	hide : function() {
		hui.ui.hideCurtain(this);
		this.element.style.display='none';
		this.visible = false;
	},
	clear : function() {
		hui.ui.destroyDescendants(this.content);
		this.content.innerHTML='';
	}
};

/* EOF */


/**
 * A push button
 * <pre><strong>options:</strong> {
 *  element : «Element | ID»,
 *  name : «String»,
 *  data : «Object»,
 *  confirm : {text : «String», okText : «String», cancelText : «String»},
 *  submit : «Boolean»
 * }
 *
 * <strong>Events:</strong>
 * $click(button) - When the button is clicked (and confirmed)
 * </pre>
 * @param options {Object} The options
 * @constructor
 */
hui.ui.Button = function(options) {
	this.options = options;
	this.name = options.name;
	this.element = hui.get(options.element);
	this.enabled = !hui.cls.has(this.element,'hui_button_disabled');
	hui.ui.extend(this);
	this._attach();
  // TODO: Deprecated!
	if (options.listener) {
		this.listen(options.listener);
	}
	if (options.listen) {
		this.listen(options.listen);
	}
};

/**
 * Creates a new button
 * <pre><strong>options:</strong> {
 *  text : «String»,
 *  title : «String», // deprecated
 *  highlighted : «true | <strong>false</strong>»,
 *  enabled : «<strong>true</strong> | false»,
 *  icon : «String»,
 *
 *  name : «String»,
 *  data : «Object»,
 *  confirm : {text : «String», okText : «String», cancelText : «String»},
 *  submit : «Boolean»,
 *
 *  listener : «Object»
 * }
 * </pre>
 */
hui.ui.Button.create = function(options) {
	options = hui.override({text:'',highlighted:false,enabled:true},options);
	var className = 'hui_button'+(options.highlighted ? ' hui_button_highlighted' : '');
	if (options.variant) {
		className+=' hui_button_'+options.variant;
	}
	if (options.small && options.variant) {
		className+=' hui_button_small_'+options.variant;
	}
	if (options.small) {
		className+=' hui_button_small'+(options.highlighted ? ' hui_button_small_highlighted' : '');
	}
	if (!options.enabled) {
		className+=' hui_button_disabled';
	}
	var text = options.text ? hui.ui.getTranslated(options.text) : null;
	if (options.title) { // Deprecated
		text = hui.ui.getTranslated(options.title);
	}
	var element = options.element = hui.build('a',{'class':className,href:'javascript://'});
	var inner = hui.build('span',{parent:hui.build('span',{parent:element})});
	if (options.icon) {
		var icon = hui.build('em',{parent:inner,'class':'hui_button_icon',style:'background-image:url('+hui.ui.getIconUrl(options.icon,16)+')'});
		if (!text) {
			hui.cls.add(icon,'hui_button_icon_notext');
		}
	}
	if (text) {
		hui.dom.addText(inner,text);
	}
	return new hui.ui.Button(options);
};

hui.ui.Button.prototype = {
	_attach : function() {
		var self = this;
		hui.listen(this.element,'mousedown',function(e) {
			hui.stop(e);
		});
		hui.listen(this.element,'click',function(e) {
			hui.stop(e);
			self._onClick(e);
		});
	},
	_onClick : function(e) {
		if (this.enabled) {
			var alt = false;
			if (e) {
				alt = hui.event(e).altKey;
			}
			if (this.options.confirm && !alt) {
				hui.ui.confirmOverlay({
					widget : this,
					text : this.options.confirm.text,
					okText : this.options.confirm.okText,
					cancelText : this.options.confirm.cancelText,
					onOk : this._fireClick.bind(this)
				});
			} else {
				this._fireClick();
			}
		} else {
			this.element.blur();
		}
	},
	_fireClick : function() {
		this.fire('click',this);
		if (this.options.submit) {
			var form = hui.ui.getAncestor(this,'hui_formula');
			if (form) {
				form.submit();
			} else {
				hui.log('No form found to submit');
			}
		}
	},
	/** Registers a function as a click listener or issues a click
	 * @param func? {Function} The function to run when clicked, leave out to issue a click
	 */
	click : function(func) {
		if (func) {
			this.listen({$click:func});
			return this;
		} else {
			this._onClick();
		}
	},
	/** Focus the button */
	focus : function() {
		this.element.focus();
	},
	/** Registers a function as a click handler
	 * @param func {Function} The fundtion to invoke when clicked click
	 */
	onClick : function(func) {
		this.listen({$click:func});
	},
	/** Enables or disables the button
	 * @param enabled {Boolean} If the button should be enabled
	 */
	setEnabled : function(enabled) {
		this.enabled = enabled;
		this._updateUI();
	},
	/** Enables the button */
	enable : function() {
		this.setEnabled(true);
	},
	/** Disables the button */
	disable : function() {
		this.setEnabled(false);
	},
	/** Sets whether the button is highlighted
	 * @param highlighted {Boolean} If the button should be highlighted
	 */
	setHighlighted : function(highlighted) {
		hui.cls.set(this.element,'hui_button_highlighted',highlighted);
	},
	_updateUI : function() {
		hui.cls.set(this.element,'hui_button_disabled',!this.enabled);
	},
	/** Sets the button text
	 * @param
	 */
	setText : function(text) {
		hui.dom.setText(this.element.getElementsByTagName('span')[1], hui.ui.getTranslated(text));
	},
	/**
	 * Get the data object for the button
	 * @returns {Object} The data object
	 */
	getData : function() {
		return this.options.data;
	}
};

////////////////////////////////// Buttons /////////////////////////////

/** @constructor */
hui.ui.Buttons = function(options) {
	this.name = options.name;
	this.element = hui.get(options.element);
	this.body = hui.get.firstByClass(this.element,'hui_buttons_body');
	hui.ui.extend(this);
};

hui.ui.Buttons.create = function(options) {
	options = hui.override({top:0},options);
	var e = options.element = hui.build('div',{'class':'hui_buttons'});
	if (options.align==='right') {
		hui.cls.add(e,'hui_buttons_right');
	}
	if (options.align==='center') {
		hui.cls.add(e,'hui_buttons_center');
	}
	if (options.top > 0) {
		e.style.paddingTop=options.top+'px';
	}
	hui.build('div',{'class':'hui_buttons_body',parent:e});
	return new hui.ui.Buttons(options);
};

hui.ui.Buttons.prototype = {
	add : function(widget) {
		this.body.appendChild(widget.element);
		return this;
	}
};

/* EOF */

