hui.ui.listen({
	siteId : null,
	
	$ready : function() {
		hui.ui.tellContainers('changeSelection','tool:Central');
	},
	$open$list : function(obj) {
		this.loadSite(obj.id);
	},

	loadSite : function(id) {
		hui.ui.request({
			message : {start : 'Åbner site...',delay:300},
			url : '../../Services/Model/LoadObject.php',
			parameters : {id:id},
			onJSON : function(data) {
				this.siteId = id;
				siteFormula.setValues(data);
				siteWindow.show();
				saveSite.enable();
				deleteSite.enable();
				siteFormula.focus();
			}.bind(this)
		});
	},
	$submit$siteFormula : function(form) {
		data = form.getValues();
		data.id = this.siteId;
		hui.ui.request({
			message : {start : 'Gemmer site...',delay:300},
			url : 'data/SaveSite.php',
			json : {data:data},
			onSuccess : function() {
				list.refresh();
			}
		});
		this._resetSiteWindow();
	},
	$click$newSite : function() {
		siteFormula.reset();
		saveSite.enable();
		deleteSite.disable();
		siteWindow.show();
		siteFormula.focus();
	},
	$click$cancelSite : function() {
		this._resetSiteWindow();
	},
	_resetSiteWindow : function() {
		this.siteId = null;
		saveSite.disable();
		deleteSite.disable();
		siteFormula.reset();
		siteWindow.hide();
	},
	
	$click$deleteSite : function() {
		hui.ui.request({
			message : { start : 'Sletter site...', delay : 300 },
			parameters : {id : this.siteId},
			url : '../../Services/Model/DeleteObject.php',
			onSuccess : function() {
				list.refresh();
			}.bind(this)
		});
		this._resetSiteWindow();
	},
	
	$click$refresh : function() {
		hui.ui.request({
			url : 'data/Refresh.php',
			message : { start : 'Opdaterer...', delay : 300 },
			onSuccess : function() {
				list.refresh();
			}
		})
	}
});