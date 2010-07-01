function XWGIframe(id) {
	this.id=id;
}

XWGIframe.prototype.getSource = function() {
	return this.getDocument().location;
};

XWGIframe.prototype.setSource = function(url) {
	this.getDocument().location.href=url;
};


XWGIframe.prototype.getFrame = function() {
	return document.getElementById(this.id);
};

XWGIframe.prototype.getDocument = function() {
    var IFrameObj = document.getElementById(this.id);
    if (IFrameObj.contentDocument) {
        // For NS6
        return IFrameObj.contentDocument;
    } else if (IFrameObj.contentWindow) {
        // For IE5.5 and IE6
        return IFrameObj.contentWindow.document;
    } else if (IFrameObj.document) {
        // For IE5
        return IFrameObj.document;
    }
};
