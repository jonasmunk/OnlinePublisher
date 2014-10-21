hui.ui.listen({
	$click$close : function() {
		window.parent.location='../Preview/';
	},
	$clickButton$list : function(info) {
		var id = info.row.id;
		selector.setValue(id);
		this._viewVersion(id);
	},
	$click$closeViewer : function() {
		viewerFrame.clear();
		hui.ui.changeState('list');
		selector.setValue('list');
		reconstruct.disable();
	},
	$select$selector : function(item) {
		if (item.value=='list') {
			viewerFrame.clear();
			hui.ui.changeState('list');
			reconstruct.disable();
		} else {
			this._viewVersion(item.value);
		}
	},
	
	_viewVersion : function(id) {		
		viewerFrame.setUrl('../Preview/viewer/?history='+id);
		hui.ui.changeState('viewer');
		reconstruct.enable();
	},
	$click$reconstruct : function() {
		var item = selector.getValue();
		hui.ui.request({
			url : 'data/Reconstruct.php',
			parameters : {id : item.value},
			onJSON : function(obj) {
				if (obj.success) {
					window.parent.location='../../Template/Edit.php';
				} else {
					hui.ui.showMessage({text:'Det lykkedes ikke at genskabe siden',icon : 'common/warning',duration:3000});
				}
			}
		})
	},
	
	
	///////////// Message ////////////
	
	messageId : null,
	
	$clickIcon$list : function(info) {
		this.messageId = info.row.id;
		hui.ui.request({
			message : {start:'Henter beskrivelse',delay:300},
			url : 'data/LoadMessage.php',
			parameters : {id: this.messageId},
			onJSON : function(obj) {
				messageFormula.setValues(obj);
				messagePanel.position(info.node);
				messagePanel.show();
				messageFormula.focus();
			}
		});
	},
	$click$cancelMessage : function() {
		this.messageId = null;
		messageFormula.reset();
		messagePanel.hide();
	},
	$submit$messageFormula : function(form) {
		var values = form.getValues();
		hui.ui.request({
			message : {start:'Gemmer beskrivelse',delay:300},
			url : 'data/UpdateMessage.php',
			parameters : {message : values.message, id: this.messageId},
			$success : function() {
				list.refresh();
			}
		})
		this.messageId = null;
		messageFormula.reset();
		messagePanel.hide();
	}
})