hui.ui.listen({
	$ready : function() {
		if (window.parent!=window) {
			window.parent.baseController.changeSelection('service:start');
		}
	},
	$clickIcon$newsList : function(info) {
		window.open(info.data.url);
	},
	$clickIcon$taskList : function(info) {
		if (info.data.action=='edit') {
			document.location='../../Template/Edit.php?id='+info.data.id;
		}
		if (info.data.action=='view') {
			document.location='../../Services/Preview/?id='+info.data.id;
		}
	},

	$clickIcon : function(info) {
		if (info.key=="expand") {
			info.tile.toggleFullScreen();
		}
	},
	$clickIcon$developmentTile : function(info) {
		if (info.key=="expand") {
			info.tile.toggleFullScreen();
			developmentPages.expand();
		}
		else if (info.key=="next") {
			developmentPages.next();
		} else if (info.key=="previous") {
			developmentPages.previous();
		}
	},
	$click$userSettings : function() {
		settingsPanel.position(userSettings);
		settingsPanel.show();
	},
	$click$saveSettings : function() {
		settingsPanel.hide();
	},
	$submit$feedbackForm : function() {
		var values = feedbackForm.getValues();
		hui.ui.showMessage({text:'Sender besked...',busy:true});
		sendFeedback.disable();
		hui.ui.request({
			url : 'data/SendFeedback.php',
			parameters : values,
			onFailure : function() {
				hui.ui.showMessage({text:'Det lykkedes desværre ikke at sende beskeden',icon:'common/warning',duration:3000})
				sendFeedback.enable();
			},
			onSuccess : function() {
				feedbackForm.reset();
				hui.ui.hideMessage();
				sendFeedback.enable();
				feedbackPages.next();
			}
		})
	}
})