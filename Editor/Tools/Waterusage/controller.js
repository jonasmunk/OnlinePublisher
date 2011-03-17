ui.listen({
	usageId : null,
	meterId : null,
	
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
		deleteUsage.setEnabled(false);
		saveUsage.setEnabled(true);
		usageFormula.focus();
	},
	
	$click$saveUsage : function() {
		var data = usageFormula.getValues();
		data.id = this.usageId;
		ui.request({url:'SaveUsage.php',onSuccess:'usageUpdated',json:{data:data}});
	},
	$success$usageUpdated : function() {
		list.refresh();
		this.usageId = null;
		usageFormula.reset();
		usageWindow.hide();
	},
	
	$listRowWasOpened$list : function(obj) {
		if (obj.kind=='waterusage') {
			this._editUsage(obj.id);
		}
		else if (obj.kind=='watermeter') {
			this._editMeter(obj.id);
		}
	},
	_editUsage : function(id) {
		usageFormula.reset();
		deleteUsage.setEnabled(false);
		saveUsage.setEnabled(false);
		ui.request({json:{data:{id:id}},url:'../../Services/Model/LoadObject.php',onSuccess:'loadUsage'});

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
		ui.request({json:{data:{id:this.usageId}},url:'../../Services/Model/DeleteObject.php',onSuccess:'deleteUsage'});
	},
	$success$deleteUsage : function() {
		usageFormula.reset();
		usageWindow.hide();
		list.refresh();
	},
	$uploadDidCompleteQueue : function() {
		list.refresh();
	},
	
	// Export
	
	$click$export : function() {
		var iframe = n2i.build('iframe',{style:'position:absolute;left:-1000px;top:-1000px;',parent:document.body});
		iframe.onload = function() {
			alert('hey!');
		}
		iframe.src='Export.php';
	},
	
	// Meter
	
	$click$newMeter : function() {
		this.meterId = null;
		meterWindow.show();
		meterFormula.reset();
		deleteMeter.setEnabled(false);
		saveMeter.setEnabled(true);
		meterFormula.focus();
	},
	$submit$meterFormula : function() {
		saveMeter.setEnabled(false);
		var data = meterFormula.getValues();
		data.id = this.meterId;
		ui.showMessage({text:'Gemmer vandmåler',busy:true});
		ui.request({url:'SaveMeter.php',json:{data:data},onSuccess:function() {
			list.refresh();
			ui.showMessage({text:'Vandmåleren er gemt',icon:'common/success',duration:2000});
			meterFormula.reset();
			meterWindow.hide();
		}});
	},
	_editMeter : function(id) {
		ui.request({json:{data:{id:id}},url:'LoadSummary.php',onJSON:function(data) {
			this.meterId = id;
			ui.changeState('meter');
			summaryFormula.setValues(data);
		}.bind(this)});
		return;
		
		meterFormula.reset();
		deleteMeter.setEnabled(false);
		saveMeter.setEnabled(false);
		ui.request({json:{data:{id:id}},url:'../../Services/Model/LoadObject.php',onJSON:function(data) {
			this.meterId = data.id;
			
			meterFormula.setValues(data);
			meterWindow.show();
			saveMeter.setEnabled(true);
			deleteMeter.setEnabled(true);
			meterFormula.focus();
		}.bind(this)});

	},
	$submit$summaryFormula : function() {
		var values = summaryFormula.getValues();
		values.watermeterId = this.meterId;
		ui.showMessage({text:'Gemmer information',busy:true});
		ui.request({json:{data:values},url:'SaveSummary.php',onSuccess:function() {
			ui.showMessage({text:'Informationen er gemt',icon:'common/success',duration:2000});
		},onFailure:function() {
			ui.showMessage({text:'Der skete desværre en fejl',icon:'common/warning',duration:4000});
		}});
	},
	$click$deleteMeter : function() {
		ui.request({json:{data:{id:this.meterId}},url:'../../Services/Model/DeleteObject.php',onSuccess:function() {
			list.refresh();
			this.meterId = null;
			meterFormula.reset();
			meterWindow.hide();
		}.bind(this)});
	},
	$click$cancelMeter : function() {
		this.meterId = null;
		meterFormula.reset();
		meterWindow.hide();
	}
});