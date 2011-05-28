
op.WeblogTemplate = {
	groups : [],
	ignite : function() {
		new hui.ui.Button({element:'weblog_new'}).listen({
			$click : this.showNewEntry.bind(this)
		})
		hui.ui.listen(this);
	},
	showNewEntry : function() {
		if (!this.newBox) {
			var box = this.newBox = hui.ui.Box.create({modal:true,width:600,absolute:true,padding:10,title:'Nyt indlæg',closable:true});
			var form = this.newForm = hui.ui.Formula.create();
			var group = form.buildGroup({above:true},[
				{type:'Text',options:{label:'Titel',key:'title'}},
				{type:'DateTime',options:{label:'Dato',name:'newEntryDate',key:'date',allowNull:false}},
				{type:'Text',options:{label:'Text',key:'text',lines:8}},
				{type:'Checkboxes',options:{label:'Grupper',items:this.groups,key:'group[]'}}
			]);
			var buttons = group.createButtons();
			var cancel = hui.ui.Button.create({title:'Annuller',name:'cancelNewEntry'});
			var create = hui.ui.Button.create({title:'Opret',highlighted:true,name:'createNewEntry'});
			buttons.add(cancel);
			buttons.add(create);
			box.add(form);
			box.addToDocument();
		}
		hui.ui.get('newEntryDate').setValue(new Date());
		this.newBox.show();
		this.newForm.focus();
	},
	$click$cancelNewEntry : function() {
		this.newBox.hide();
	},
	$click$createNewEntry : function() {
		var parms = {ajax:true,action:'createEntry','group[]':[]};
		hui.override(parms,this.newForm.getValues());
		parms.date = parseInt(parms.date.getTime()/1000);
		if (hui.isBlank(parms.title)) {
			hui.ui.showMessage({text:'Der skal skrives en titel',duration:2000});
			this.editForm.focus();
			return;
		} else if (parms['group[]'].length<1) {
			hui.ui.showMessage({text:'Der skal vælges mindst een gruppe',duration:2000});
			return;
		}
		hui.ui.request({
			url: op.page.pagePath,
			parameters:parms,
			onSuccess: function(transport) {
		    	document.location.reload();
			}
		});
	},
	edit : function(id) {
		var parms = {ajax:true,action:'loadEntry',entryId:id};
		hui.request({
			url : op.page.pagePath,
			method : 'post',
			parameters : parms,
			onSuccess : function(t) {
				var data = hui.fromJSON(t.responseText);
				this.editEntry(data);
			}.bind(this),
			onException : function(t,e) {
				hui.log(e);
			}.bind(this)
		});
	},
	deleteEntry : function(id,element) {
		hui.ui.confirmOverlay({
			element:element,
			text:'Er du sikker?',
			okText:'Ja, slet',
			cancelText:'Nej',
			onOk:function() {
				var parms = {ajax:true,action:'deleteEntry',entryId:id};
				hui.ui.request({
					url : op.page.pagePath,
					parameters:parms,
					onSuccess: function(t) {
				    	document.location.reload();
					}
				});
			}
		});
	},
	editEntry : function(obj) {
		if (!this.editBox) {
			var box = this.editBox = hui.ui.Box.create({modal:true,width:600,absolute:true,padding:10,title:'Rediger indlæg',closable:true});
			var form = this.editForm = hui.ui.Formula.create();
			var group = form.buildGroup({above:true},[
				{type:'Text',options:{label:'Titel',key:'title'}},
				{type:'DateTime',options:{label:'Dato',name:'editEntryDate',key:'date',allowNull:false}},
				{type:'Text',options:{label:'Text',key:'text',lines:8}},
				{type:'Checkboxes',options:{label:'Grupper',items:this.groups,key:'group[]',name:'editEntryGroups'}}
			]);
			var buttons = group.createButtons();
			var cancel = hui.ui.Button.create({title:'Annuller',name:'cancelEditEntry'});
			var create = hui.ui.Button.create({title:'Opdater',highlighted:true,name:'updateEditEntry'});
			buttons.add(cancel);
			buttons.add(create);
			box.add(form);
			box.addToDocument();
		}
		this.activeEntry = obj;
		this.editForm.setValues(obj);
		hui.ui.get('editEntryGroups').setValue(obj.groups);
		this.editBox.show();
		this.editForm.focus();
	},
	$click$cancelEditEntry : function() {
		this.editBox.hide();
	},
	$click$updateEditEntry : function() {
		var parms = {ajax:true,action:'updateEntry','group[]':[],entryId:this.activeEntry.id};
		hui.override(parms,this.editForm.getValues());
		parms.date = parseInt(parms.date.getTime()/1000);
		if (hui.isBlank(parms.title)) {
			hui.ui.showMessage({text:'Der skal skrives en titel',duration:2000});
			this.editForm.focus();
			return;
		} else if (parms['group[]'].length<1) {
			hui.ui.showMessage({text:'Der skal vælges mindst een gruppe',duration:2000});
			return;
		}
		hui.ui.request({
			url : op.page.pagePath,
			parameters : parms,
			onSuccess : function(transport) {
				this.editBox.hide();
		    	window.setTimeout(function() {document.location.reload()},500);
			}.bind(this)
		});
	}
}

hui.ui.onReady(function() {
	op.WeblogTemplate.ignite();
});