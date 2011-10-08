var controller = {
	pageId : null,
	
	$ready : function() {
		if (window.parent!=window) {
			window.parent.baseController.changeSelection('service:preview');
		}
		this._refreshBase();
	},
	pageDidLoad : function(id) {
		this.pageId = id;
		this._updateState();
		this._refreshBase();
		this.$click$cancelNote();
		reviewPanel.hide();
	},
	_refreshBase : function() {
		try {
			window.parent.baseController.refresh();
		} catch (e) {}
	},
	_updateState : function() {
		hui.ui.request({
			url : 'data/LoadPageStatus.php',
			parameters : {id:this.pageId},
			onJSON : function(obj) {
				publish.setEnabled(obj.changed);
				var overlays = {none:null,accepted:'success',rejected:'stop'};
				review.setOverlay(overlays[obj.review]);
			}
		});
	},
	pageDidChange : function() {
		publish.setEnabled(true);
	},
	
	$click$close : function() {
		this.getFrame().location='../../Tools/Sites/';
	},
	$click$edit : function() {
		var frame = window.frames[0];
		if (frame.templateController!==undefined) {
			frame.templateController.edit();
		} else {
			this.getFrame().location='../../Template/Edit.php';
		}
	},
	$click$properties : function() {
		var frame = window.frames[0];
		frame.op.Editor.editProperties();
	},
	$click$view : function() {
		window.parent.location='ViewPublished.php';
	},
	$click$publish : function() {
		hui.ui.request({
			url : 'viewer/data/PublishPage.php',
			parameters : {id:this.pageId},
			onSuccess : function(obj) {
				publish.setEnabled(false);
			}
		});
	},
	getFrame : function() {
		return window.parent.frames[0];
	},
	$click$viewHistory : function() {
		window.frames[0].location = '../PageHistory/';
	},
	
	///////////// Notes //////////////
	
	$click$addNote : function() {
		notePanel.show();
		noteFormula.focus();
	},
	$submit$noteFormula : function(form) {
		var values = form.getValues();
		hui.ui.request({
			message : {start:'Gemmer note...',delay:300},
			url : 'data/CreateNote.php',
			parameters : {pageId : this.pageId, text : values.text, kind : values.kind},
			onSuccess : function() {
				hui.ui.showMessage({text:'Noten er gemt',icon:'common/success',duration:2000});
				this._refreshBase();
			}.bind(this)
		});
		noteFormula.reset();
		notePanel.hide();
	},
	$click$cancelNote : function() {
		noteFormula.reset();
		notePanel.hide();
	},
	
	//////////// Review //////////////
	
	$click$review : function() {
		reviewPanel.show();
		reviewList.setUrl('data/ListReviews.php?pageId='+this.pageId);
	},
	$click$reviewReject : function() {
		this._sendReview(false);
	},
	$click$reviewAccept : function() {
		this._sendReview(true);
	},
	_sendReview : function(accepted) {
		hui.ui.request({
			url : 'data/Review.php',
			parameters : {pageId : this.pageId, accepted : accepted},
			onSuccess : function() {
				hui.ui.showMessage({text:'Revisionen er gemt!',icon:'common/success',duration:2000});
				reviewPanel.hide();
				this._updateState();
			}.bind(this)
		});
	}
};

hui.ui.listen(controller);