////////////////////////// Finder ///////////////////////////

/**
 * A "finder" for finding objects
 * @constructor
 */
hui.ui.Finder = function(options) {
  this.options = hui.override({title:'Finder',selection:{},list:{}},options);
  this.name = options.name;
  this.uploader = null; // hui.ui.Upload
  hui.ui.extend(this);
  if (options.listener) {
    this.listen(options.listener);
  }
}

/**
 * Creates a new finder
 * <pre><strong>options:</strong> {
 *  title : «String»,
 *  selection : {
 *      value : «String»,
 *      url : «String»,
 *      parameter : «String»,
 *      kindParameter : «String»
 *  },
 *  list : {
 *      url : «String»
 *  },
 *  search : {
 *      parameter : «String»
 *  },
 *  listener : {$select : function(object) {}}
 * }
 * </pre>
 */
hui.ui.Finder.create = function(options) {
  return new hui.ui.Finder(options);
}

hui.ui.Finder.prototype = {
  /** Shows the finder */
  show : function() {
    if (!this.window) {
      this._build();
    } else {
      // Refresh if re-openede
      // TODO refresh more
      this.list.refresh();
    }
    this.window.show();
  },
  hide : function() {
    if (this.window) {
      this.window.hide();
    }
  },
  clear : function() {
    this.list.clearSelection();
  },
  _build : function() {
    var win = this.window = hui.ui.Window.create({
      title : this.options.title,
      icon : 'common/search',
      width : 600,
      height : 400
    });
    win.listen({
      $userClosedWindow : function() {
        this.fire('cancel');
      }.bind(this)
    });
    if (this.options.url) {
      win.setBusy(true);
      hui.ui.request({
        url : this.options.url,
        $object : function(config) {
          hui.override(this.options,config);
          if (config.title) {
            win.setTitle(config.title);
          }
          win.setBusy(false);
          this._buildBody();
        }.bind(this)
      })
      return;
    }
    this._buildBody();
  },
  _buildBody : function() {
    var opts = this.options;
    var layout = hui.ui.Structure.create();
    this.window.add(layout);

    var left = hui.ui.Overflow.create({dynamic:true});
    layout.addLeft(left);

    var list = this.list = hui.ui.List.create();

    this.list.listen({
      $select : this._selectionChanged.bind(this)
    });

    var showBar = opts.search || opts.gallery;

    if (showBar) {
      var bar = hui.ui.Bar.create({
        variant: 'layout'
      });
      layout.addCenter(bar);
      if (opts.search) {
        var search = hui.ui.SearchField.create({
          expandedWidth: 200
        });
        search.listen({
          $valueChanged: function() {
            list.resetState();
          }
        })
        bar.addToRight(search);
      }
    }
    var right = hui.ui.Overflow.create({dynamic:true});
    layout.addCenter(right);
    right.add(this.list);


    this.selection = hui.ui.Selection.create({value : opts.selection.value});
    this.selection.listen({
      $select : function() {
        list.resetState();
      }
    })
    var selectionSource = new hui.ui.Source({url : opts.selection.url});
    this.selection.addItems({source:selectionSource})
    left.add(this.selection);

    var parameters = [];
    if (opts.list.url) {
      parameters = [
        {key:'windowSize',value:10},
        {key:'windowPage',value:'@'+list.name+'.window.page'},
        {key:'direction',value:'@'+list.name+'.sort.direction'},
        {key:'sort',value:'@'+list.name+'.sort.key'}
      ];
    }
    if (opts.selection.parameter) {
      parameters.push({
        key:opts.selection.parameter || 'text',value:'@'+this.selection.name+'.value'
      });
    }
    if (opts.selection.kindParameter) {
      parameters.push({
        key:opts.selection.kindParameter || 'text',value:'@'+this.selection.name+'.kind'
      });
    }

    if (opts.search) {
      parameters.push({key:opts.search.parameter || 'text',value:'@'+search.name+'.value'})
    }
    if (opts.list.pageParameter) {
      parameters.push({key:opts.list.pageParameter,value:'@'+list.name+'.window.page'})
    }

    var listSource = opts.list.source;
    if (listSource) {
      for (var i=0; i < parameters.length; i++) {
        listSource.addParameter(parameters[i]);
      };
    }
    if (opts.list.url) {
      listSource = new hui.ui.Source({
        url : opts.list.url,
        parameters : parameters
      });
    }
    this.list.setSource(listSource);

    if (opts.gallery) {
      var viewChanger = hui.ui.Segmented.create({
        value: 'gallery',
        items: [{
          icon: 'view/list',
          value: 'list'
        }, {
          icon: 'view/gallery',
          value: 'gallery'
        }]
      })
      viewChanger.listen({
        $valueChanged: this.changeView.bind(this)
      })
      bar.add(viewChanger);
      var gallerySource = new hui.ui.Source({
        url: opts.gallery.url,
        parameters: parameters
      });
      var gallery = this.gallery = hui.ui.Gallery.create({
        source: gallerySource
      })
      this.list.hide();
      right.add(gallery);
      gallery.listen({
        $select: function(value) {
          this.fire('select', gallery.getFirstSelection());
        }.bind(this)
      });
      gallerySource.refresh();
    }
    if (opts.upload && hui.ui.Upload.HTML5.support().supported) {
      var uploadButton = hui.ui.Button.create({
        text: 'Add...',
        small: true
      });
      uploadButton.listen({
        $click: this._showUpload.bind(this)
      })
      bar.add(uploadButton);
    }
    if (opts.creation) {
      bar.add(hui.ui.Button.create({
        text: opts.creation.button || 'Add...',
        small: true,
        listen : {
          $click:  this._showCreation.bind(this)
        }
      }));
    };
    selectionSource.refresh();
    hui.ui.reLayout();
  },
  changeView : function(value) {
    if (value=='gallery') {
      this.list.hide();
      this.gallery.show();
    } else {
      this.list.show();
      this.gallery.hide();
    }
  },

  _selectionChanged : function() {
    var row = this.list.getFirstSelection();
    if (row!=null) {
      this.fire('select',row);
    }
  },

  _showUpload : function(button) {
    if (!this.uploadPanel) {
      var options = this.options.upload;
      var panel = this.uploadPanel = hui.ui.BoundPanel.create({padding:5,width:300,modal:true});
      this.uploader = hui.ui.Upload.create({
        url : options.url,
        placeholder : options.placeholder,
        chooseButton : {en:'Choose file...',da:'Vælg fil...'}
      });
      this.uploader.listen({
        $uploadDidComplete : function(file) {
          this._uploadSuccess(hui.string.fromJSON(file.request.responseText));
        }.bind(this)
      })
      panel.add(this.uploader);
    }
    this.uploadPanel.show({target:button});
  },
  _uploadSuccess : function(obj) {
    this.uploadPanel.hide();
    this.fire('select',obj);
  },
  _showCreation : function(button) {
    if (!this._createPanel) {
      var form = this._createForm = hui.ui.Formula.create({listen:{$submit:this._create.bind(this)}});
      form.buildGroup({above:true},this.options.creation.formula);
      var panel = this._createPanel = hui.ui.BoundPanel.create({padding:5,width:300,modal:true});
      panel.add(form);
      var buttons = hui.ui.Buttons.create();
      buttons.add(hui.ui.Button.create({
        text:'Cancel',
        listen: { $click : function() { 
          form.reset();
          panel.hide(); 
        } }
      }));
      buttons.add(hui.ui.Button.create({text:'Create',highlighted:true,submit:true}));
      form.add(buttons);
    }
    this._createPanel.show({target:button});
    this._createForm.focus();
  },
  _create : function(form) {
    var values = this._createForm.getValues();
    this._createForm.reset();
    this._createPanel.hide();
    this.window.setBusy(true);
    var self = this;
    hui.ui.request({
      url : this.options.creation.url,
      parameters : values,
      $object : function(obj) {
        hui.log('Created',obj)
        self.fire('select',obj);
      },
      $failure : function() {
        
      },
      $finally : function() {
        self.window.setBusy(false);
      }
    })
  }
};

window.define && define('hui.ui.Finder',hui.ui.Finder);