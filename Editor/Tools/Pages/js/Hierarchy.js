var lastUserChange = new Date().getTime();
var lastAjaxRequest = new Date().getTime();

var selectDelegate = {
    valueDidChange : function(event,obj) {
        var link = "";
        if (obj.getValue()=="allpages") {
            link="PagesFrame.php";
        }
		parent.frames["Right"].location.href = link;
		HierarchyHandler.changeSelection("");
		lastUserChange = new Date().getTime();
    }
};
Selection.setDelegate(selectDelegate);

var hierDelegate = {
	itemInfo : null,
    itemWasSelected : function(event,obj) {
		Selection.setValue("");
		lastUserChange = new Date().getTime();
    },
	contextMenuWillShow : function(item,event) {
		this.itemInfo = item.info;
		if (item.info.type == 'page') {
			in2iMenuHandler.showMenu(ContextMenuPage, null, 'vertical', event, true);
		} else if (item.info.type == 'hierarchy') {
			in2iMenuHandler.showMenu(ContextMenuHierarchy, null, 'vertical', event, true);
		} else if (item.info.type) {
			in2iMenuHandler.showMenu(ContextMenu, null, 'vertical', event, true);
		} else {
			return true;
		}
		return false;
	},
	pageProperties : function() {
		parent.frames["Right"].location.href = 'EditPage.php?id='+this.itemInfo.pageId;
	},
	editPage : function() {
		parent.location.href = '../../Template/Edit.php?id='+this.itemInfo.pageId;
	},
	hierarchyProperties : function() {
		parent.frames["Right"].location.href = 'HierarchyProperties.php?id='+this.itemInfo.id;
	},
	editHierarchy : function() {
		parent.frames["Right"].location.href = 'EditHierarchy.php?id='+this.itemInfo.id;
	},
	editItem : function() {
		parent.frames["Right"].location.href = 'EditHierarchyItem.php?id='+this.itemInfo.itemId;
	},
	moveItem : function(dir) {
		window.location.href = 'MoveHierarchyItem.php?id='+this.itemInfo.itemId+'&dir='+dir+'&return=Hierarchy.php&dontUpdateHierarchy=true';
	},
	showSubItems : function(dir) {
		if (this.itemInfo.type=='hierarchy') {
			parent.frames["Right"].location.href = 'HierarchyFrame.php?id='+this.itemInfo.id;
		} else {
			parent.frames["Right"].location.href = 'HierarchyItem.php?id='+this.itemInfo.itemId;
		}
	}
};
HierarchyHandler.delegate = hierDelegate;

var responder = {
	onSuccess : function(t) {
		if (lastAjaxRequest) {
			if (lastAjaxRequest>lastUserChange) {
				var text = t.responseText;
				if (text.length>0) {
					HierarchyHandler.changeSelection(text);
					Selection.setValue(text);
				}
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
startInterval();