// global flag
var isIE = false;

// global request and XML document objects
var req;

function loadXMLDoc(url,delegate) {
    // branch for native XMLHttpRequest object
    if (window.XMLHttpRequest) {
        req = new XMLHttpRequest();
        req.onreadystatechange = delegate;
        req.open("GET", url, true);
        req.send(null);
    // branch for IE/Windows ActiveX version
    } else if (window.ActiveXObject) {
        isIE = true;
        req = new ActiveXObject("Microsoft.XMLHTTP");
        if (req) {
            req.onreadystatechange = delegate;
            req.open("GET", url, true);
            req.send();
        }
    }
}

// handle onreadystatechange event of req object
function processReqChange() {
    // only if req shows "loaded"
    if (req.readyState == 4) {
        // only if "OK"
        if (req.status == 200) {
            doSomething(req);
         } else {
            alert("There was a problem retrieving the XML data:\n" +
                req.statusText);
         }
    }
}

function doSomething(req) {
   var xml = req.responseXML;
   var pre = '<div class="NewsBlock">';
	var title = document.forms.NewsForm.title.value;
	if (title!=null && title.length>0) {
		pre+='<div class="NewsBlockTitle">'+title+'</div>';
	}
	var objects = xml.getElementsByTagName('object');
	for (i=0;i<objects.length;i++) {
		pre+='<div class="NewsItem">';
		var title = objects[i].getElementsByTagName('title')[0].firstChild.nodeValue;
		var note = '';
		try {
			note = objects[i].getElementsByTagName('note')[0].firstChild.nodeValue;
		}
		catch (ignore) {}
		pre+='<div class="NewsTitle">'+title+'</div>';
		pre+='<div class="NewsNote">'+note+'</div>';
		pre+='</div>';
	}
	pre += '</div>';
	document.getElementById('NewsPreview').innerHTML=pre;
}