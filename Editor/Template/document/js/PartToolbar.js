var partToolbar = {
	pageId : null,
	partId : null,
	sectionId : null,
	editorFrame : null,
	partForm : null,
	section : null,
	
	$interfaceIsReady : function() {
		this.editorFrame = window.parent.Frame.EditorFrame;
		var doc = this.editorFrame.getDocument();
		this.partForm = doc.forms['PartForm'];
		this.section = $(doc.getElementById('selectedSectionTD'));
		
		marginLeft.setValue(this.partForm.left.value);
		marginRight.setValue(this.partForm.right.value);
		marginBottom.setValue(this.partForm.bottom.value);
		marginTop.setValue(this.partForm.top.value);
		sectionWidth.setValue(this.partForm.width.value);
		sectionFloat.setValue(this.partForm['float'].value);
	},
	cancel : function() {
		this.editorFrame.setUrl('Editor.php?section=');
	},
	save : function() {
		this.partForm.submit();
	},
	deletePart : function() {
		this.editorFrame.setUrl('DeleteSection.php?section='+this.sectionId);
	},
	preview : function() {
		this.getMainController().preview();
	},
	getMainController : function() {
		return this.editorFrame.getWindow().partController;
	},
	$valueChanged$marginLeft : function(value) {
		this.partForm.left.value=value;
		this.section.style.paddingLeft=value;
	},
	$valueChanged$marginRight : function(value) {
		this.partForm.right.value=value;
		this.section.style.paddingRight=value;
	},
	$valueChanged$marginBottom : function(value) {
		this.partForm.bottom.value=value;
		this.section.style.paddingBottom=value;
	},
	$valueChanged$marginTop : function(value) {
		this.partForm.top.value=value;
		this.section.style.paddingTop=value;
	},
	$valueChanged$sectionWidth : function(value) {
		this.partForm.width.value=value;
	},
	$valueChanged$sectionFloat : function(value) {
		this.partForm['float'].value=value;
	}
};

ui.get().listen(partToolbar);