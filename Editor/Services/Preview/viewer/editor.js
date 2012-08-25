op.Editor = {
	language : 'en',
	$ready : function() {
		hui.ui.tellContainers('pageLoaded',op.page.id);
		if (hui.location.hasHash('edit')) {
			if (templateController!==undefined) {
				templateController.edit();
			}
		}
	},
	getToolbarController : function() {
		try {
			return window.parent.controller;
		} catch (e) {
			hui.log('Unable to find toolbar controller');
		}
	},
	$partWasMoved : function(info) {
		var data = hui.string.fromJSON(info.dragged.getAttribute('data'));
		var p = {
			sectionId : data.id,
			rowIndex : info.rowIndex,
			columnIndex : info.columnIndex,
			sectionIndex : info.partIndex
		}
		hui.ui.request({
			url : op.context+'Editor/Template/document/live/MoveSection.php',
			parameters : p,
			onSuccess : function() {
				info.onSuccess();
				this._signalChange();
			}.bind(this)
		})
	},
	$partChanged : function() {
		this._signalChange();
	},
	_signalChange : function() {
		hui.ui.tellContainers('pageChanged',op.page.id);
	},
	
	editProperties : function(language) {
		this.language = language;
		if (!this.propertiesWindow) {
			var originalLanguage = hui.ui.language;
			hui.ui.language = language;
			var win = this.propertiesWindow = hui.ui.Window.create({width:300,title:'Info',icon:'common/info',padding:10});
			var form = this.propertiesFormula = hui.ui.Formula.create();
			var group = form.buildGroup({above:true},[
				{type:'TextField',label:{en:'Title:',da:'Titel:'},options:{key:'title'}},
				{type:'TextField',label:{en:'Description:',da:'Beskrivelse:'},options:{key:'description',multiline:true}},
				{type:'TextField',label:{en:'Keywords:',da:'Nøgelord:'},options:{key:'keywords'}},
				{type:'TextField',label:{en:'Path:',da:'Sti:'},options:{key:'path'}},
				{type:'DropDown',label:{en:'Language:',da:'Sprog:'},options:{
					key : 'language',
					url : '../../Model/LanguageItems.php'
				}}
			]);
			var buttons = group.createButtons();
			var more = hui.ui.Button.create({text:{en:'More...',da:'Mere...'}});
			more.click(this.moreProperties.bind(this));
			buttons.add(more);

			var update = hui.ui.Button.create({text:{en:'Update',da:'Opdater'},highlighted:true});
			update.click(this.saveProperties.bind(this));
			buttons.add(update);
			win.add(form);
			hui.ui.language = originalLanguage;
		}
		hui.ui.request({
			url : 'data/LoadPageProperties.php',
			parameters : {id:op.page.id},
			message : {start:language=='da' ? 'Henter info...' : 'Loading info...',delay:300},
			onJSON : function(obj) {
				this.propertiesFormula.setValues(obj);
				this.propertiesWindow.show();
			}.bind(this)
		})
	},
	saveProperties : function() {
		var values = this.propertiesFormula.getValues();
		values.id = op.page.id;
		this.propertiesFormula.reset();
		this.propertiesWindow.hide();
		hui.ui.request({
			url : 'data/SavePageProperties.php',
			parameters : values,
			message : {start:this.language=='da' ? 'Gemmer info...' : 'Saving info...',delay:300},
			onSuccess : function() {
				hui.ui.showMessage({text:this.language=='da' ? 'Informationen er gemt' : 'The information is saved',icon:'common/success',duration:2000});
			}.bind(this)
		});
	},
	moreProperties : function() {
		if (!window.parent) {
			hui.log('The window has no parent! '+window.location);
			return;
		}
		window.parent.location='../../../Tools/Sites/?pageInfo='+op.page.id;
	},
	
	
	editDesign : function() {
		if (!this.designWindow) {
			hui.ui.request({
				url : 'data/LoadDesignInfo.php',
				parameters : {id:op.page.id},
				message : {start:'Henter design info...',delay:300},
				onJSON : function(parameters) {
					if (parameters.length>0) {
						this._buildDesignForm(parameters);
						this.designWindow.show();
					} else {
						hui.ui.showMessage({text:'Dette design har ingen indstillinger',duration:3000})
					}
				}.bind(this)
			})
		} else {
			this.designWindow.show();
		}
	},
	
	_buildDesignForm : function(parameters) {
		var win = this.designWindow = hui.ui.Window.create({width:300,title:'Design',icon:'common/info',padding:10,variant:'dark'});
		var form = this.designFormula = hui.ui.Formula.create();
		form.listen({
			$submit : function() {
				var values = form.getValues();
				hui.ui.request({
					url : 'data/SaveDesignParameters.php',
					parameters : {id:op.page.id,parameters:hui.string.toJSON(values)},
					onSuccess : function() {
						hui.ui.showMessage({text:'Indstillingerne er gemt', duration:3000});
						document.location.reload();
					}
				})
			}
		})
		this.designGroup = this.designFormula.createGroup();
		
		var group = this.designFormula.createGroup();
		var buttons = group.createButtons();
		var btn = hui.ui.Button.create({text:'Opdater',submit:true});
		buttons.add(btn);
		
		win.add(form);
		
		for (var i=0; i < parameters.length; i++) {
			var parm = parameters[i];
			if (parm.type=='text' || parm.type=='color') {
				var field = hui.ui.TextField.create({key:parm.key,label:parm.label,value:parm.value});
				this.designGroup.add(field);
			}
			if (parm.type=='selection') {
				parm.options.unshift({});
				var field = hui.ui.DropDown.create({key:parm.key,label:parm.label,value:parm.value,items:parm.options});
				this.designGroup.add(field);
			}
			if (parm.type=='image') {
				var field = hui.ui.DropDown.create({key:parm.key,label:parm.label,value:parm.value,url:'../../Model/Items.php?type=image&includeEmpty=true'});
				this.designGroup.add(field);
			}
		};
	}
}

hui.ui.listen(op.Editor);