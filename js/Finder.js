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
		var win = this.window = hui.ui.Window.create({title:this.options.title,icon:'common/search',width:600,height:400});
        win.listen({
            $userClosedWindow : function() {
                this.fire('cancel');
            }.bind(this)
        })
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

		var layout = hui.ui.Layout.create();
		this.window.add(layout);

		var left = hui.ui.Overflow.create({dynamic:true});
		layout.addToLeft(left);
		
		var list = this.list = hui.ui.List.create();
		
		this.list.listen({
			$open : function(row) {
				
			},
			
			$select : this._selectionChanged.bind(this)
		})
		
        var showBar = this.options.search || this.options.gallery;
		
		if (showBar) {
			var bar = hui.ui.Bar.create({variant:'layout'});
			layout.addToCenter(bar);
            if (this.options.search) {
    			var search = hui.ui.SearchField.create({expandedWidth:200});
    			search.listen({
    				$valueChanged : function() {
    					list.resetState();
    				}
    			})
    			bar.addToRight(search);
            }
		}
		var right = hui.ui.Overflow.create({dynamic:true});
		layout.addToCenter(right);
		right.add(this.list);
		
        
		this.selection = hui.ui.Selection.create({value : this.options.selection.value});
		this.selection.listen({
			$select : function() {
				list.resetState();
			}
		})
		var selectionSource = new hui.ui.Source({url : this.options.selection.url});
		this.selection.addItems({source:selectionSource})
		left.add(this.selection);
		
		var parameters = [];
		if (this.options.list.url) {
			parameters = [
				{key:'windowSize',value:10},
				{key:'windowPage',value:'@'+list.name+'.window.page'},
				{key:'direction',value:'@'+list.name+'.sort.direction'},
				{key:'sort',value:'@'+list.name+'.sort.key'}
			];
		}
		if (this.options.selection.parameter) {
			parameters.push({key:this.options.selection.parameter || 'text',value:'@'+this.selection.name+'.value'})
		}
		if (this.options.selection.kindParameter) {
			parameters.push({key:this.options.selection.kindParameter || 'text',value:'@'+this.selection.name+'.kind'})
		}
		
		if (this.options.search) {
			parameters.push({key:this.options.search.parameter || 'text',value:'@'+search.name+'.value'})
		}
		if (this.options.list.pageParameter) {
			parameters.push({key:this.options.list.pageParameter,value:'@'+list.name+'.window.page'})
		}
		
		var listSource = this.options.list.source;
		if (listSource) {
			for (var i=0; i < parameters.length; i++) {
				listSource.addParameter(parameters[i]);
			};
		}
		if (this.options.list.url) {
			listSource = new hui.ui.Source({
				url : this.options.list.url,
				parameters : parameters
			});
		}
		this.list.setSource(listSource);
		
        if (this.options.gallery) {
            var viewChanger = hui.ui.Segmented.create({
                value : 'gallery',
                items : [{icon:'view/list',value:'list'},{icon:'view/gallery',value:'gallery'}]
            })
            viewChanger.listen({
                $valueChanged : this.changeView.bind(this)
            })                
            bar.add(viewChanger);
            var gallerySource = new hui.ui.Source({
				url : this.options.gallery.url,
				parameters : parameters
		    });
            var gallery = this.gallery = hui.ui.Gallery.create({
                source : gallerySource
            })
            this.list.hide();
    		right.add(gallery);
            gallery.listen({
                $select : function(value) {
                    this.fire('select',gallery.getFirstSelection());
                }.bind(this)
            });
            gallerySource.refresh();
        }
        if (this.options.upload && hui.ui.Upload.HTML5.support().supported) {
            var uploadButton = hui.ui.Button.create({text:'Add...',small:true});
            uploadButton.listen({
                $click : this._showUpload.bind(this)
            })
            bar.add(uploadButton);
            
        }
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
    }
}

