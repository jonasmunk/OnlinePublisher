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
 * Checks if an object is a number
 * @param {Object} obj The object to check
 */
hui.isNumber = function(obj) {
	return typeof(obj)==='number';
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
    if (hui.isNumber(str)) {
      str = str+'';
    }
		else if (!hui.isString(str)) {return '';}
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