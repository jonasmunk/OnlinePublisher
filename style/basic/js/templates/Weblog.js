
op.WeblogTemplate = {
	groups : [],
	ignite : function() {
		new ui.Button({element:'weblog_new'}).listen({
			$click : this.showNewEntry.bind(this)
		})
		In2iGui.listen(this);
	},
	showNewEntry : function() {
		if (!this.newBox) {
			var box = this.newBox = In2iGui.Box.create({modal:true,width:600,absolute:true,padding:10,title:'Nyt indlæg',closable:true});
			var form = this.newForm = In2iGui.Formula.create();
			var group = form.buildGroup({above:true},[
				{type:'Text',options:{label:'Titel',key:'title'}},
				{type:'DateTime',options:{label:'Dato',name:'newEntryDate',key:'date',allowNull:false}},
				{type:'Text',options:{label:'Text',key:'text',lines:8}},
				{type:'Checkboxes',options:{label:'Grupper',items:this.groups,key:'group[]'}}
			]);
			var buttons = group.createButtons();
			var cancel = In2iGui.Button.create({title:'Annuller',name:'cancelNewEntry'});
			var create = In2iGui.Button.create({title:'Opret',highlighted:true,name:'createNewEntry'});
			buttons.add(cancel);
			buttons.add(create);
			box.add(form);
			box.addToDocument();
		}
		In2iGui.get('newEntryDate').setValue(new Date());
		this.newBox.show();
		this.newForm.focus();
	},
	$click$cancelNewEntry : function() {
		this.newBox.hide();
	},
	$click$createNewEntry : function() {
		var parms = {ajax:true,action:'createEntry','group[]':[]};
		n2i.override(parms,this.newForm.getValues());
		parms.date = parseInt(parms.date.getTime()/1000);
		if (parms.title.blank()) {
			alert('Der skal skrives en titel');
			return;
		} else if (parms['group[]'].length<1) {
			alert('Der skal vælges mindst een gruppe');
			return;
		}
		new Ajax.Request(op.page.pagePath, {
			method: 'post',
			parameters:parms,
			onSuccess: function(transport) {
		    	document.location.reload();
			}
		});
	},
	edit : function(id) {
		var parms = {ajax:true,action:'loadEntry',entryId:id};
		new Ajax.Request(op.page.pagePath, {
			method: 'post',
			parameters:parms,
			onSuccess: function(t) {
				var data = t.responseText.evalJSON();
				this.editEntry(data);
			}.bind(this),
			onException: function(t,e) {
				n2i.log(e);
			}.bind(this)
		});
	},
	deleteEntry : function(id) {
		if (!confirm('Er du sikker på at du vil slette indlægget?')) {
			return;
		}
		var parms = {ajax:true,action:'deleteEntry',entryId:id};
		new Ajax.Request(op.page.pagePath, {
			method: 'post',
			parameters:parms,
			onSuccess: function(t) {
		    	document.location.reload();
			}.bind(this),
			onException: function(t,e) {
				n2i.log(e);
			}.bind(this)
		});
	},
	editEntry : function(obj) {
		if (!this.editBox) {
			var box = this.editBox = In2iGui.Box.create({modal:true,width:600,absolute:true,padding:10,title:'Rediger indlæg',closable:true});
			var form = this.editForm = In2iGui.Formula.create();
			var group = form.buildGroup({above:true},[
				{type:'Text',options:{label:'Titel',key:'title'}},
				{type:'DateTime',options:{label:'Dato',name:'editEntryDate',key:'date',allowNull:false}},
				{type:'Text',options:{label:'Text',key:'text',lines:8}},
				{type:'Checkboxes',options:{label:'Grupper',items:this.groups,key:'group[]',name:'editEntryGroups'}}
			]);
			var buttons = group.createButtons();
			var cancel = In2iGui.Button.create({title:'Annuller',name:'cancelEditEntry'});
			var create = In2iGui.Button.create({title:'Opdater',highlighted:true,name:'updateEditEntry'});
			buttons.add(cancel);
			buttons.add(create);
			box.add(form);
			box.addToDocument();
		}
		this.activeEntry = obj;
		this.editBox.show();
		this.editForm.setValues(obj);
		In2iGui.get('editEntryGroups').setValue(obj.groups);
		this.editForm.focus();
	},
	$click$cancelEditEntry : function() {
		this.editBox.hide();
	},
	$click$updateEditEntry : function() {
		var parms = {ajax:true,action:'updateEntry','group[]':[],entryId:this.activeEntry.id};
		n2i.override(parms,this.editForm.getValues());
		parms.date = parseInt(parms.date.getTime()/1000);
		if (parms.title.blank()) {
			alert('Der skal skrives en titel');
			return;
		} else if (parms['group[]'].length<1) {
			alert('Der skal vælges mindst een gruppe');
			return;
		}
		new Ajax.Request(op.page.pagePath, {
			method: 'post',
			parameters:parms,
			onSuccess: function(transport) {
				this.editBox.hide();
		    	window.setTimeout(function() {document.location.reload()},500);
			}.bind(this)
		});
	}
}

In2iGui.onDomReady(function() {
	op.WeblogTemplate.ignite();
});