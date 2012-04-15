hui.ui.listen({
	usageId : null,
	meterId : null,
	
	$ready : function() {
		if (window.parent!=window) {
			window.parent.baseController.changeSelection('tool:Waterusage');
		}
	},
	
	$valueChanged$search : function(value) {
		list.resetState();
	},
	
	$click$cancelUsage : function() {
		this.usageId = null;
		usageFormula.reset();
		usageWindow.hide();
	},
	
	$click$newUsage : function() {
		this.usageId = null;
		usageWindow.show();
		usageFormula.reset();
		usageFormula.setValues({date:new Date()});
		deleteUsage.setEnabled(false);
		saveUsage.setEnabled(true);
		usageFormula.focus();
	},
	
	$click$saveUsage : function() {
		var data = usageFormula.getValues();
		data.id = this.usageId;
		hui.ui.request({
			url : 'SaveUsage.php',
			json : {data:data},
			message : {start:'Gemmer aflæsning...',success:'Aflæsningen er gemt'},
			onSuccess : 'usageUpdated',
			onFailure:function() {
				hui.ui.showMessage({text:'Der skete desværre en fejl',icon:'common/warning',duration:4000});
			}
		});
	},
	$success$usageUpdated : function() {
		list.refresh();
		filterSource.refresh();
		this.usageId = null;
		usageFormula.reset();
		usageWindow.hide();
	},
	
	$select$selector : function() {
		hui.ui.changeState('list');
	},
	
	$valueChanged$search : function() {
		hui.ui.changeState('list');
	},
	
	$open$list : function(obj) {
		if (obj.kind=='waterusage') {
			this._editUsage(obj.id);
		}
		else if (obj.kind=='watermeter') {
			this._editMeter(obj.id);
		}
	},
	$clickIcon$list : function(info) {
		if (info.data.action=='meterInfo') {
			this._editMeter(info.data.id);
		} else if (info.data.action=='usageInfo') {
			this._editUsage(info.data.id);
		} else if (info.data.action=='usageStatus') {
			this._editStatus(info);
		}
	},
	
	
	// Status ...

	_statusId : null,
	_editStatus : function(info) {
		this._statusId = info.data.id;
		statusPanel.position(info.node);
		statusPanel.show();
	},
	
	$click$cancelStatus : function() {
		statusPanel.hide();
	},
	$click$rejectStatus : function() {
		this._updateStatus(-1);
	},
	$click$acceptStatus : function() {
		this._updateStatus(1);
	},
	_updateStatus : function(status) {
		hui.ui.request({
			url : 'data/UpdateStatus.php',
			parameters : {id:this._statusId,status:status},
			onSuccess : function() {
				list.refresh();
				filterSource.refresh();
			}.bind(this)
		})
		statusPanel.hide();
		this._statusId = null;
	},
	

	// Usage ...

	_editUsage : function(id) {
		usageFormula.reset();
		deleteUsage.setEnabled(false);
		saveUsage.setEnabled(false);
		hui.ui.request({
			message : {start : 'Åbner aflæsning...',delay:300},
			json : {data:{id:id}},
			url : 'LoadUsage.php',
			onSuccess : 'loadUsage'
		});

	},
	$success$loadUsage : function(data) {
		this.usageId = data.id;
		usageFormula.setValues(data);
		usageWindow.show();
		saveUsage.setEnabled(true);
		deleteUsage.setEnabled(true);
		usageFormula.focus();
	},
	$click$deleteUsage : function() {
		hui.ui.request({
			json : {data:{id:this.usageId}},
			url : '../../Services/Model/DeleteObject.php',
			message : {start:'Sletter aflæsning...',success:'Aflæsningen er slettet'},
			onSuccess:'deleteUsage'
		});
	},
	$success$deleteUsage : function() {
		usageFormula.reset();
		usageWindow.hide();
		list.refresh();
		filterSource.refresh();
	},
	$file$uploadDidCompleteQueue : function() {
		list.refresh();
		filterSource.refresh();
	},
	
	// Export
	
	$click$export : function() {
		var iframe = hui.build('iframe',{style:'position:absolute;left:-1000px;top:-1000px;',parent:document.body});
		iframe.src='Export.php';
		hui.ui.showMessage({text:'Filen vil nu blive downloadet...',busy:true,duration:3000});
		exportPanel.hide();
	},
	$click$exportIcon : function() {
		exportPanel.toggle();
	},
	
	// Meter
	
	
	$click$newMeter : function() {
		this.meterId = null;
		meterWindow.show();
		meterFormula.reset();
		createMeter.setEnabled(true);
		meterFormula.focus();
	},
	$submit$meterFormula : function() {
		createMeter.setEnabled(false);
		var data = meterFormula.getValues();
		data.id = this.meterId;
		hui.ui.request({
			url:'SaveMeter.php',
			json:{data:data},
			message:{start:'Gemmer vandmåler',success:'Vandmåleren er gemt'},
			onSuccess:function() {
				list.refresh();
				meterFormula.reset();
				meterWindow.hide();
			}
		});
	},
	_editMeter : function(id) {
		hui.ui.request({
			json:{data:{id:id}},
			url:'LoadSummary.php',
			message:{start:'Åbner måler...',delay:300},
			onJSON:function(data) {
				this.meterId = id;
				subUsageList.clear();
				summaryFormula.reset();
				hui.ui.changeState('meter');
				summaryFormula.setValues(data);
				subUsageList.setUrl('ListSubUsage.php?meterId='+id);
			}.bind(this)
		});
	},
	$click$closeMeter : function() {
		this._resetSubUsage();
		hui.ui.changeState('list');
	},
	$submit$summaryFormula : function() {
		var values = summaryFormula.getValues();
		values.watermeterId = this.meterId;
		saveMeter.setEnabled(false);
		hui.ui.request({
			json : {data:values},
			url : 'data/SaveSummary.php',
			message : {start:'Gemmer information',success:'Informationen er gemt'},
			onFailure : function() {
				hui.ui.showMessage({text:'Der skete desværre en fejl',icon:'common/warning',duration:4000});
			},
			onSuccess : function() {
				saveMeter.setEnabled(true);
			}
		});
	},
	$click$deleteMeter : function() {
		deleteMeter.setEnabled(false);
		hui.ui.request({
			json:{data:{id:this.meterId}},
			url:'DeleteMeter.php',
			message : {start:'Sletter måler...',success:'Måleren er slettet'},
			onSuccess:function() {
				list.refresh();
				filterSource.refresh();
				this.meterId = null;
				this._resetSubUsage();
				hui.ui.changeState('list');
				deleteMeter.setEnabled(true);
			}.bind(this)
		});
	},
	$click$cancelMeter : function() {
		this.meterId = null;
		meterFormula.reset();
		meterWindow.hide();
	},
	
	// Sub usage support
	
	subUsageId : null,
	
	$click$addSubUsage  : function() {
		this.subUsageId = null;
		subUsageFormula.reset();
		subUsageFormula.setValues({date:new Date()});
		subUsageWindow.show();
		deleteSubUsage.disable();
		subUsageFormula.focus();
	},
	$click$cancelSubUsage  : function() {
		this._resetSubUsage();
	},
	$submit$subUsageFormula : function() {
		var values = subUsageFormula.getValues();
		values.id = this.subUsageId;
		values.meterId = this.meterId;
		hui.ui.request({
			json : {data:values},
			url : 'SaveSubUsage.php',
			message : {start:'Gemmer aflæsning...',success:'Informationen er gemt'},
			onSuccess : function() {
				this._resetSubUsage();
				subUsageList.refresh();
			}.bind(this),
			onFailure:function() {
				hui.ui.showMessage({text:'Der skete desværre en fejl',icon:'common/warning',duration:4000});
			}
		});
	},
	_resetSubUsage : function() {
		this.subUsageId = null;
		subUsageFormula.reset();
		subUsageWindow.hide();
	},
	$open$subUsageList : function(obj) {
		hui.ui.request({
			json : {data:{id:obj.id}},
			url : '../../Services/Model/LoadObject.php',
			message : {start:'Åbner aflæsning...',delay:300},
			onJSON : function(data) {
				hui.ui.hideMessage();
				this.subUsageId = data.id;
				subUsageFormula.setValues(data);
				subUsageWindow.show();
				deleteSubUsage.enable();
				subUsageFormula.focus();
			}.bind(this)
		});
	},
	$click$deleteSubUsage : function() {
		hui.ui.request({
			json : {data:{id:this.subUsageId}},
			url : '../../Services/Model/DeleteObject.php',
			message : {start:'Sletter aflæsning...',success:'Aflæsningen er slettet'},
			onSuccess : function() {
				subUsageList.refresh();
				this._resetSubUsage();
			}.bind(this)
		});		
	},
	
	// Import
	
	$click$import : function() {
		importWindow.show();
	},
	$uploadDidCompleteQueue$metersUpload : function() {
		hui.ui.showMessage({text:'Importen er fuldført',icon:'common/success',duration:2000});
		list.refresh();
		filterSource.refresh();
	},
	$uploadDidCompleteQueue$usagesUpload : function() {
		hui.ui.showMessage({text:'Importen er fuldført',icon:'common/success',duration:2000});
		list.refresh();
		subUsageList.refresh();
		filterSource.refresh();
	}
});