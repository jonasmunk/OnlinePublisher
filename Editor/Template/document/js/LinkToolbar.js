var controller = {
	id : null,
	editorFrame : null,
	$ready : function() {
		this.editorFrame = window.parent.Frame.EditorFrame;
		if (this.id===0) {
			var win = this.editorFrame.getWindow();
			text.setValue(win.controller.selectedText);
		}
	},
	_getParameters : function() {
		var p = {};
		p.text = text.getValue();
		p.alternative = alternative.getValue();
		if (page.getValue()) {
			p.type = 'page';
			p.value = page.getValue();
		} else if (file.getValue()) {
			p.type = 'file';
			p.value = file.getValue();
		} else if (url.getValue()) {
			p.type = 'url';
			p.value = url.getValue();
		} else if (email.getValue()) {
			p.type = 'email';
			p.value = email.getValue();
		}
		return p;
	},
	
	
	$click$create : function() {
		var p = this._getParameters();
		hui.ui.request({url:'Links/SaveLink.php',parameters:p,onSuccess:function() {
			this.editorFrame.reload();
			document.location='Toolbar.php';
		}.bind(this)});
	},
	
	
	$click$update : function() {
		var p = this._getParameters();
		p.id = this.id;
		hui.ui.request({url:'Links/SaveLink.php',parameters:p,onSuccess:function() {
			this.editorFrame.reload();
			document.location='Toolbar.php';
		}.bind(this)});
	},
	$click$delete : function() {
		hui.ui.request({url:'Links/DeleteLink.php',parameters:{id:this.id},onSuccess:function() {
			this.editorFrame.reload();
			document.location='Toolbar.php';
		}.bind(this)});
	},
	
	
	$valueChanged$page : function() {
		file.reset();
		url.reset();
		email.reset();
	},
	$valueChanged$file : function() {
		page.reset();
		url.reset();
		email.reset();
	},
	$valueChanged$url : function() {
		page.reset();
		file.reset();
		email.reset();
	},
	$valueChanged$email : function() {
		page.reset();
		file.reset();
		url.reset();
	}
};

hui.ui.listen(controller);