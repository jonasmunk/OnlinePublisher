hui.ui.listen({
	
	frameId : null,
	
	$click$newFrame : function() {
		this.frameId = null;
		frameWindow.show();
		frameFormula.reset();
		newsList.setObjects();
		this._clearNews();
		deleteFrame.setEnabled(false);
		frameFormula.focus();
	},
	$listRowWasOpened$list : function(row) {
		this.loadFrame(row.id);
	},
	loadFrame : function(id) {
		frameFormula.reset();
		deleteFrame.setEnabled(false);
		saveFrame.setEnabled(false);
		this._clearNews();
		hui.ui.request({
			parameters : {id:id},
			url : 'data/LoadFrame.php',
			message : {start:'Åbner ramme...',delay:300},
			onJSON : function(data) {
				frameFormula.setValues(data.frame);
				searchFormula.setValues({
					enabled : data.frame.searchEnabled,
					pageId : data.frame.searchPageId
				})
				userFormula.setValues({
					enabled : data.frame.userStatusEnabled,
					pageId : data.frame.loginPageId
				})
				topLinks.setValue(data.topLinks);
				bottomLinks.setValue(data.bottomLinks);
				frameWindow.show();
				deleteFrame.setEnabled(data.canRemove);
				saveFrame.setEnabled(true);
				newsList.setObjects(data.newsBlocks);
				this.frameId = data.frame.id;
				frameFormula.focus();
			}.bind(this)
		});
	},
	$click$saveFrame : function() {
		this._saveFrame();
	},
	$click$cancelFrame : function() {
		this.frameId = null;
		frameFormula.reset();
		frameWindow.hide();
	},
	$submit$frameFormula : function() {
		this._saveFrame();
	},
	_saveFrame : function() {
		var values = frameFormula.getValues();
		if (hui.isBlank(values.title) || values.hierarchyId==null) {
			hui.ui.showMessage({text:'Titel og hierarki skal udfyldes',icon:'common/warning',duration:2000});
			frameFormula.focus();
			return;
		}
		hui.ui.request({
			json : {
				id : this.frameId,
				frame : values,
				search : searchFormula.getValues(),
				user : userFormula.getValues(),
				topLinks : topLinks.getValue(),
				bottomLinks : bottomLinks.getValue(),
				newsBlocks : newsList.getRows()
			},
			url : 'data/SaveFrame.php',
			message : {start:'Gemmer ramme...',delay:300},
			onSuccess : function() {
				listSource.refresh();
			}.bind(this)
		});
		this.frameId = null;
		frameFormula.reset();
		frameWindow.hide();
		this._clearNews();
	},
	$click$deleteFrame : function() {
		deleteFrame.setEnabled(false);
		hui.ui.request({
			parameters : { id : this.frameId },
			url : 'data/DeleteFrame.php',
			message : {start:'Sletter ramme...',delay:300},
			onSuccess : function() {
				listSource.refresh();
			}.bind(this)
		});
		this.frameId = null;
		frameFormula.reset();
		frameWindow.hide();
		this._clearNews();
	},
	
	///////////// News //////////
	
	_newsList : [],
	_editedNews : null,
	
	$click$addNewsBlock : function() {
		this._editedNews = null;
		newsFormula.reset()
		newsFormula.setValues({
			sortdir : 'ascending',
			sortby : 'startdate',
			maxitems : 0,
			timetype : 'always',
			timecount : 0
		});
		newsWindow.show();
		newsFormula.focus();
		deleteNews.disable();
	},
	$submit$newsFormula :function() {
		var values = newsFormula.getValues();
		var rows = newsList.getRows();
		if (this._editedNews) {
			for (var i=0; i < rows.length; i++) {
				if (rows[i]==this._editedNews) {
					hui.override(rows[i],values);
				}
			};
		} else {
			rows.push(values);
		}
		newsList.setObjects(rows);
		newsWindow.hide();
	},
	$listRowWasOpened$newsList : function(row) {
		this._editedNews = row;
		newsFormula.setValues(row);
		newsWindow.show();
		deleteNews.enable();
	},
	$click$deleteNews : function() {
		var rows = newsList.getRows();
		hui.removeFromArray(rows,this._editedNews);
		newsList.setObjects(rows);
		newsFormula.reset();
		newsWindow.hide();
		this._editedNews = null;
	},
	_clearNews : function() {
		newsFormula.reset();
		newsWindow.hide();
		this._editedNews = null;				
	},
	$click$cancelNews : function() {
		this._clearNews();
	}
});