/**
* @fileoverview General purpose XML functions and classes
*/

/**
 * Creates a new In2iXMLRequest object
 * @class A class for making cross browser AJAX requests
 */
function In2iXMLRequest() {
	this.identifier = null;
}

/**
 * Creates a cross browser XMLHttpRequest object
 * @return {XMLHttpRequest} An XMLHttpRequest object, null if it could not be created
 */
In2iXMLRequest.createXMLHttpRequest = function() {
	try {
		if (window.XMLHttpRequest) {
			var req = new XMLHttpRequest();
			
			// some versions of Moz do not support the readyState property
			// and the onreadystate event so we patch it!
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
		if (window.ActiveXObject) {
			return new ActiveXObject(getXmlHttpPrefix() + ".XmlHttp");
		}
	}
	catch (ex) {}
	// fell through
	//throw new Error("Your browser does not support XmlHttp objects");
	return null;
	
	/************ Old (may be faster??) **********
	var req = null;
    // branch for native XMLHttpRequest object
    if (window.XMLHttpRequest) {
        req = new XMLHttpRequest();
    // branch for IE/Windows ActiveX version
    } else if (window.ActiveXObject) {
        req = new ActiveXObject("Microsoft.XMLHTTP");
	}
	return req;
	***********************************/
}

/**
 * Initiates a request using the GET protocol
 * @param {String} url The url of the request
 * @param {function} delegate The function that should be called when then request i finished.
 *        It is called with the request object and optional identifier
 * @param {String} identifier An ID used to identify the request
 *        (If multiple requests are made on the same time)
 * @return {void}
 */
In2iXMLRequest.prototype.get = function(url,delegate,identifier) {
    // branch for native XMLHttpRequest object
	var req;
    if (req = In2iXMLRequest.createXMLHttpRequest()) {
        req.onreadystatechange = function() {
            // only if req shows "loaded"
            if (req.readyState == 4) {
                // only if "OK"
                if (req.status == 200) {
                		delegate(req,identifier);
                 } else {
                    //alert("There was a problem retrieving the XML data:\n" +
                    //    req.statusText);
                 }
            } else {
            	// Still not loaded
            }
        };
        req.open("GET", url, true);
        req.send(null);
    // branch for IE/Windows ActiveX version
    } else {
    	alert('Could not create object');
    }
};



////////////////////////////////////////////////////////////////
////                      Rogue stuff                       ////
////////////////////////////////////////////////////////////////

/**
 * Finds available Microsoft DomDocument ActiveX object prefix
 * @return {String} The prefix of available Microsoft DomDocuemnt ActiveX object prefix
 * @throws {Exception} If it could not find an installed XML parser
 */
function getDomDocumentPrefix() {
	if (getDomDocumentPrefix.prefix)
		return getDomDocumentPrefix.prefix;
	
	var prefixes = ["MSXML2", "Microsoft", "MSXML", "MSXML3"];
	var o;
	for (var i = 0; i < prefixes.length; i++) {
		try {
			// try to create the objects
			o = new ActiveXObject(prefixes[i] + ".DomDocument");
			return getDomDocumentPrefix.prefix = prefixes[i];
		}
		catch (ex) {};
	}
	
	throw new Error("Could not find an installed XML parser");
}

/**
 * Finds available Microsoft XmlHttp ActiveX object prefix
 * @return {String} The prefix of available Microsoft XmlHttp ActiveX object prefix
 * @throws {Exception} If it could not find an installed XML parser
 */
function getXmlHttpPrefix() {
	if (getXmlHttpPrefix.prefix)
		return getXmlHttpPrefix.prefix;
	
	var prefixes = ["MSXML2", "Microsoft", "MSXML", "MSXML3"];
	var o;
	for (var i = 0; i < prefixes.length; i++) {
		try {
			// try to create the objects
			o = new ActiveXObject(prefixes[i] + ".XmlHttp");
			return getXmlHttpPrefix.prefix = prefixes[i];
		}
		catch (ex) {};
	}
	
	throw new Error("Could not find an installed XML parser");
}


//////////////////////////
// Start the Real stuff //
//////////////////////////



// XmlDocument factory
function XmlDocument() {}

/**
 * Creates a new XMLDocument
 * @return {XMLDocument} An XMLDocument
 */
XmlDocument.create = function () {
	try {
		// DOM2
		if (document.implementation && document.implementation.createDocument) {
			var doc = document.implementation.createDocument("", "", null);
			
			// some versions of Moz do not support the readyState property
			// and the onreadystate event so we patch it!
			if (doc.readyState == null) {
				doc.readyState = 1;
				doc.addEventListener("load", function () {
					doc.readyState = 4;
					if (typeof doc.onreadystatechange == "function")
						doc.onreadystatechange();
				}, false);
			}
			
			return doc;
		}
		if (window.ActiveXObject)
			return new ActiveXObject(getDomDocumentPrefix() + ".DomDocument");
	}
	catch (ex) {}
	throw new Error("Your browser does not support XmlDocument objects");
};


/**
 * Create the loadXML method and xml getter for Mozilla
 * TODO: (jonas) Find out what this does!!!
 */
if (window.DOMParser && window.XMLSerializer && window.Node && Node.prototype && Node.prototype.__defineGetter__) {

	// XMLDocument did not extend the Document interface in some versions
	// of Mozilla. Extend both!
	/**
	 * @ignore
	 */
	XMLDocument.prototype.loadXML = Document.prototype.loadXML = function (s) {
		
		// parse the string to a new doc	
		var doc2 = (new DOMParser()).parseFromString(s, "text/xml");
		
		// remove all initial children
		while (this.hasChildNodes())
			this.removeChild(this.lastChild);
			
		// insert and import nodes
		for (var i = 0; i < doc2.childNodes.length; i++) {
			this.appendChild(this.importNode(doc2.childNodes[i], true));
		}
	};
	
	
	/*
	 * xml getter
	 *
	 * This serializes the DOM tree to an XML String
	 *
	 * Usage: var sXml = oNode.xml
	 *
	 */
	// XMLDocument did not extend the Document interface in some versions
	// of Mozilla. Extend both!
	
	/**
	 * @ignore
	 */
	XMLDocument.prototype.__defineGetter__("xml", function () {
		return (new XMLSerializer()).serializeToString(this);
	});
	
	/**
	 * @ignore
	 */
	Document.prototype.__defineGetter__("xml", function () {
		return (new XMLSerializer()).serializeToString(this);
	});
}