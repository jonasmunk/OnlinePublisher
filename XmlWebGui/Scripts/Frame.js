function getIframeDoc(name) {
    var IFrameObj = frames[name];
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
}