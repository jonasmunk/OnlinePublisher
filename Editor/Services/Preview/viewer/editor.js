op.Editor = {
	$ready : function() {
		var ctrl = this.getToolbarController();
		if (ctrl) { // May not be loaded yet
			ctrl.pageDidLoad(op.page.id);
		}
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
		var ctrl = this.getToolbarController();
		if (ctrl) {
			ctrl.pageDidChange();
		}
	},
	
	editProperties : function() {
		if (!this.propertiesWindow) {
			var win = this.propertiesWindow = hui.ui.Window.create({width:300,title:'Info',icon:'common/info',padding:10,variant:'dark'});
			var form = this.propertiesFormula = hui.ui.Formula.create();
			var group = form.buildGroup({above:true},[
				{type:'TextField',options:{label:'Titel:',key:'title'}},
				{type:'TextField',options:{label:'Beskrivelse:',key:'description',multiline:true}},
				{type:'TextField',options:{label:'Nøgelord:',key:'keywords'}},
				{type:'TextField',options:{label:'Sti:',key:'path'}},
				{type:'DropDown',options:{
					label:'Sprog:',
					key:'language',
					items:[{value:'',title:'Intet'},{value:'DA',title:'Dansk'},{value:'EN',title:'Engelsk'},{value:'DE',title:'Tysk'}]
				}}
			]);
			var buttons = group.createButtons();
			var more = hui.ui.Button.create({text:'Mere...'});
			more.click(this.moreProperties.bind(this));
			buttons.add(more);

			var update = hui.ui.Button.create({text:'Opdater',highlighted:true});
			update.click(this.saveProperties.bind(this));
			buttons.add(update);
			win.add(form);
		}
		hui.ui.request({
			url:'data/LoadPageProperties.php',
			parameters:{id:op.page.id},
			message : {start:'Henter sidens info...',delay:300},
			onJSON:function(obj) {
				this.propertiesFormula.setValues(obj);
				this.propertiesWindow.show();
			}.bind(this)
		})
	},
	saveProperties : function() {
		var values = this.propertiesFormula.getValues();
		values.id = op.page.id;
		hui.ui.request({
			url:'data/SavePageProperties.php',
			parameters:values,
			message : {start:'Gemmer sidens info...',delay:300},
			onSuccess:function() {
				this.propertiesFormula.reset();
				this.propertiesWindow.hide();
			}.bind(this)
		});
	},
	moreProperties : function() {
		if (!window.parent) {
			hui.log('The window has no parent! '+window.location);
			return;
		}
		window.parent.location='../../../Tools/Sites/?pageInfo='+op.page.id;
	}
}

hui.ui.listen(op.Editor);