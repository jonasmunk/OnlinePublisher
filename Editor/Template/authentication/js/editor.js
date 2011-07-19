var templateController = {
	
	$ready : function() {
		
	},
	edit : function() {
		if (!this.win) {
			this.win = hui.ui.Window.create({title:'Adgangskontrol',width:250,padding:5});
			var form = this.form = hui.ui.Formula.create({name:'templateForm'});
			this.win.add(form);
			var group = form.buildGroup({labels:'above'},[
				{type:'TextField',options:{key:'title',label:'Titel:'}}
			]);
			var buttons = group.createButtons();
			buttons.add(hui.ui.Button.create({text:'Opdater',highlighted:true,submit:true}));
		}
		this.win.show();
		this.form.focus();
		this._load();
	},
	_load : function() {
		var base = hui.firstByClass(document.body,'authentication');
		var h1 = hui.firstByTag(base,'h1');
		var title = hui.dom.getText(h1);
		this.form.setValues({
			title:title
		});
	},
	$submit$templateForm : function() {
		var values = this.form.getValues();
		values.id = op.page.id;
		var self = this;
		hui.ui.request({
			url : '../../../Template/authentication/Save.php',
			parameters : values,
			onSuccess : function() {
				var base = hui.firstByClass(document.body,'authentication');
				var h1 = hui.firstByTag(base,'h1');
				if (h1) {
					hui.dom.setText(h1,values.title);
				}
				self.win.hide();
			}
		});
	}
};

hui.ui.listen(templateController);