var partToolbar = {
	pageId : null,
	partId : null,
	sectionId : null,
	editorFrame : null,
	partForm : null,
	section : null,
	
	$ready : function() {
		this.editorFrame = window.parent.Frame;
		if (!this.editorFrame) {
			this.editorFrame = window.parent.frames[1];
			if (!this.editorFrame) {
				hui.log('No editor frame found!');
				return;
			}
		}
		var doc = hui.frame.getDocument(this.editorFrame);
		if (!doc) {
			hui.log('no document found!');
			hui.log(this.editorFrame);
			return;
		}
		this.partForm = doc.forms['PartForm'];
		this.section = doc.getElementById('selectedSection');
		
		marginLeft.setValue(this.partForm.left.value);
		marginRight.setValue(this.partForm.right.value);
		marginBottom.setValue(this.partForm.bottom.value);
		marginTop.setValue(this.partForm.top.value);
		sectionWidth.setValue(this.partForm.width.value);
		sectionFloat.setValue(this.partForm['float'].value);
	},
	syncSize : function() {
		var ctrl = this.getMainController().partController;
		if (ctrl && ctrl.syncSize) {
			ctrl.syncSize();
		}
	},
	cancel : function() {
		hui.frame.getDocument(this.editorFrame).location = 'Editor.php?section=';
	},
	save : function() {
		this.partForm.submit();
	},
	deletePart : function() {
		hui.frame.getDocument(this.editorFrame).location = 'data/DeleteSection.php?section='+this.sectionId;
	},
	preview : function() {
		this.getMainController().preview();
	},
	getMainController : function() {
		return this.editorFrame.partController;
		var win = this.editorFrame.partController;
		if (!win) {
			hui.log('Window not found');
		}
		return hui.frame.getWindow(this.editorFrame).partController;
	},
	$valueChanged$marginLeft : function(value) {
		this.partForm.left.value=value;
		this.animatePadding('padding-left','paddingLeft',value);
	},
	$valueChanged$marginRight : function(value) {
		this.partForm.right.value=value;
		this.animatePadding('padding-right','paddingRight',value);
	},
	$valueChanged$marginBottom : function(value) {
		this.partForm.bottom.value=value;
		this.animatePadding('padding-bottom','paddingBottom',value);
	},
	$valueChanged$marginTop : function(value) {
		this.partForm.top.value=value;
		this.animatePadding('padding-top','paddingTop',value);
	},
	animatePadding : function(style,prop,value) {
		if (value) {
			hui.animate(this.section,style,value,200,{ease:hui.ease.slowFastSlow,onComplete : function() {
				this.syncSize();
			}.bind(this)})
		} else {
			this.section.style[prop]=value;
			this.syncSize();
		}
		
	},
	$valueChanged$sectionWidth : function(value) {
		if (parseInt(value)===0) {
			value='';
		}
		this.partForm.width.value=value;
		if (value==='') {
			this.section.style.width='';
				this.syncSize();
		} else {
			hui.animate(this.section,'width',value,200,{ease:hui.ease.slowFastSlow,onComplete : function() {
				this.syncSize();
			}.bind(this)})
		}
	},
	$valueChanged$sectionFloat : function(value) {
		this.partForm['float'].value=value;
	}
};

hui.ui.listen(partToolbar);