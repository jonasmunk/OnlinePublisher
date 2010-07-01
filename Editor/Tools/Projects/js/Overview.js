var lastUserChange = new Date().getTime();
var lastAjaxRequest = new Date().getTime();

var selectDelegate = {
    valueDidChange : function(event,obj) {
        var link = "";
		switch (obj.getValue()) {
			case "overview" : link="Overview.php"; break;
			case "milestones" : link="Milestones.php"; break;
		}
		parent.frames["Right"].location.href = link;
		HierarchyHandler.changeSelection("");
		lastUserChange = new Date().getTime();
    }
};
Selection.setDelegate(selectDelegate);

var hierDelegate = {
    itemWasSelected : function(event,obj) {
		Selection.setValue("");
		lastUserChange = new Date().getTime();
    }
};
HierarchyHandler.delegate = hierDelegate;

var responder = {
	onSuccess : function(t) {
		if (lastAjaxRequest>lastUserChange) {
			var text = t.responseText;
			if (text.length>0) {
				HierarchyHandler.changeSelection(text);
				Selection.setValue(text);
			}
		}
	}
};

function startInterval() {
	var request = new N2i.Request(responder);
	interval = window.setInterval(
		function() {
			lastAjaxRequest = new Date().getTime();
			request.request("HierarchySelectionRefresh.php");
		}
		,5000
	);
}
//startInterval();