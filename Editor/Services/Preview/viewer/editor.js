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
	$partChanged : function() {
		this.signalChange();
	},
	signalChange : function() {
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
			$object : function(obj) {
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
			$success : function() {
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
				$object : function(parameters) {
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
		var win = this.designWindow = hui.ui.Window.create({width:300,title:'Design',icon:'common/info',padding:10});
		var form = this.designFormula = hui.ui.Formula.create();
		form.listen({
			$submit : function() {
				var values = form.getValues();
				hui.ui.request({
					url : 'data/SaveDesignParameters.php',
					parameters : {id:op.page.id,parameters:hui.string.toJSON(values)},
					$success : function() {
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
			if (parm.type=='text') {
				var field = hui.ui.TextField.create({key:parm.key,label:parm.label,value:parm.value});
				this.designGroup.add(field);
			}
			else if (parm.type=='color') {
				var field = hui.ui.ColorInput.create({key:parm.key,value:parm.value});
				this.designGroup.add(field,parm.label);
            }
			else if (parm.type=='selection') {
				parm.options.unshift({});
				var field = hui.ui.DropDown.create({key:parm.key,label:parm.label,value:parm.value,items:parm.options});
				this.designGroup.add(field);
			}
			else if (parm.type=='image') {
				var field = hui.ui.DropDown.create({
                    key : parm.key,
                    label : parm.label,
                    value : parm.value,
                    url : '../../Model/Items.php?type=image&includeEmpty=true'
                });
				this.designGroup.add(field);
			}
		};
	}
}

hui.ui.listen(op.Editor);

hui.ui.listen({
   $ready : function() {
       var editable = document.querySelectorAll('*[data-editable]');
       var self = this;
       hui.each(editable,function(node) {
           //hui.cls.add(node,'editor_editable');
           hui.listen(node,'click',function(e) {
               e = hui.event(e);
               if (e.altKey) {
                   self._edit(node);
               }
           })
       })
   },
   _edit : function(node) {
     // TODO only one per parameter
     new op.Editor.ParameterController(node);
   }
});

op.Editor.ParameterController = function(node) {
  this.node = node;
  this.original = node.innerHTML;
  this.id = null;
  var data = hui.string.fromJSON(node.getAttribute('data-editable'));
  this.name = data.name;
  this._load();
}

op.Editor.ParameterController.prototype = {
    _load : function(node) {
        hui.ui.request({
            url : 'data/LoadParameter.php',
            parameters : {name:this.name},
            $object : this._go.bind(this)
        })
    },
    _go : function(parameter) {
        this.id = parameter.id;
        var widgets = this._build();
        widgets.input.setValue(this.node.innerHTML);
        widgets.input.listen({$valueChanged:function(str) {
            this.node.innerHTML = str;
        }.bind(this)})
        widgets.window.setTitle(parameter.name + parameter.id);
        widgets.window.listen({
            $userClosedWindow : this._cancel.bind(this)
        })
        widgets.window.show();
    },
    _cancel : function() {
        this.node.innerHTML = this.original;
        hui.ui.destroy(this.window);
    },
    _save : function() {
        var html = this.node.innerHTML;
        var self = this;
        hui.ui.request({
            url : 'data/SaveParameter.php',
            parameters : {name:this.name,id:this.id,value:html},
            $success : function() {
                self.original = html;
                hui.ui.msg.success({text:'Saved'})
            }
        })
    },
    _delete : function() {
        hui.ui.request({
            url : 'data/DeleteParameter.php',
            parameters : {id:this.id},
            $success : this._cancel.bind(this)
        })
    },
  _build : function() {
      var win = this.window = hui.ui.Window.create({width:400});
      var input = hui.ui.CodeInput.create({height:300});
      var bar = hui.ui.Toolbar.create({align : 'left'});
      bar.add(hui.ui.Toolbar.Icon.create({
        icon : 'common/ok',
        title : 'Save',
        listener : {$click : this._save.bind(this)}
      }));
      bar.add(hui.ui.Toolbar.Icon.create({
        icon : 'common/delete',
        title : 'Delete',
        listener : {$click : this._delete.bind(this)}
      }));
      win.add(bar);
      win.add(input);
      return {window:win,input:input};
  }
}