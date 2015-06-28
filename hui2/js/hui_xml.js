hui.xml = {
/*	transform : function(xml,xsl) {
		if (window.ActiveXObject) {
			return xml.transformNode(xsl);
		} else if (document.implementation && document.implementation.createDocument) {
			try {
			  	var pro = new XSLTProcessor();
                pro.setParameter(null,'dev','true');
                pro.setParameter(null,'profile','true');
                pro.setParameter(null,'version','true');
                pro.setParameter(null,'pathVersion','true');
                pro.setParameter(null,'context','true');
                pro.setParameter(null,'language','true');
			  	pro.importStylesheet(xsl);
//		'<xsl:variable name="profile">'.$profile.'</xsl:variable>'.
//		'<xsl:variable name="version">'.SystemInfo::getDate().'</xsl:variable>'.
//		'<xsl:variable name="pathVersion">'.$pathVersion.'</xsl:variable>'.
//		'<xsl:variable name="context">'.$context.'</xsl:variable>'.
//		'<xsl:variable name="language">'.InternalSession::getLanguage().'</xsl:variable>';)
				var ownerDocument = document;//.implementation.createDocument("", "test", null); 
			    return pro.transformToFragment(xml,ownerDocument);				
			} catch (e) {
				hui.log('Transform exception...');
				hui.log(e);
				throw e;
			}
		} else {
			hui.log('No XSLT!');
		}
	},*/
	parse : function(xml) {
		var doc;
		try {
		if (window.DOMParser) {
  			var parser = new DOMParser();
  			doc = parser.parseFromString(xml,"text/xml");
			var errors = doc.getElementsByTagName('parsererror');
			if (errors.length>0 && errors[0].textContent) {
				hui.log(errors[0].textContent);
				return null;
			}
  		} else {
  			doc = new ActiveXObject("Microsoft.XMLDOM");
			doc.async = false;
  			doc.loadXML(xml); 
  		}
		} catch (e) {
			return null;
		}
		return doc;
	},
	serialize : function(node) {
  		try {
      		return (new XMLSerializer()).serializeToString(node);
  		} catch (e) {
     		try {
        		return node.xml;
     		}
     		catch (ex) {}
     	}
		return null;
   	}
};