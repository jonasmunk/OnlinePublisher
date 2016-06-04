/**
 * A component for uploading files 
 * <pre><strong>options:</strong> {
 * url:'',
 * parameters:{}}
 *
 * Events:
 * uploadDidCompleteQueue - when all files are done
 * uploadDidStartQueue - when the upload starts
 * uploadDidComplete(file) - when a single file is successfull
 * uploadDidFail(file) - when a single file fails
 * </pre>
 * @constructor
 */
hui.ui.Upload = function(options) {
	this.options = hui.override({
		url : '',
		parameters : {},
		multiple : false,
		maxSize : "20480",
		types : "*.*",
		fieldName : 'file',
		chooseButton : 'Choose files...'
	},options);
	this.element = hui.get(options.element);
	this.itemContainer = hui.get.firstByClass(this.element,'hui_upload_items');
	this.status = hui.get.firstByClass(this.element,'hui_upload_status');
	this.placeholder = hui.get.firstByClass(this.element,'hui_upload_placeholder');
	this.name = options.name;
	this.items = [];
	this.busy = false;
	this._chooseImplementation();
	hui.ui.extend(this);
	this._addBehavior();
}

hui.ui.Upload.implementations = ['HTML5','Frame','Flash'];

hui.ui.Upload.nameIndex = 0;

/** Creates a new upload widget */
hui.ui.Upload.create = function(options) {
	options = options || {};
	options.element = hui.build('div',{
		'class':'hui_upload',
		html : '<div class="hui_upload_items"></div>'+
		'<div class="hui_upload_status"></div>'+
		(options.placeholder ? '<div class="hui_upload_placeholder"><span class="hui_upload_icon"></span>'+
			(options.placeholder.title ? '<h2>'+hui.string.escape(hui.ui.getTranslated(options.placeholder.title))+'</h2>' : '')+
			(options.placeholder.text ? '<p>'+hui.string.escape(hui.ui.getTranslated(options.placeholder.text))+'</p>' : '')+
		'</div>' : '')
	});
	return new hui.ui.Upload(options);
}

hui.ui.Upload.prototype = {
	
	/////////////// Public parts /////////////

	/**
	 * Change a parameter
	 */
	setParameter : function(name,value) {
		this.options.parameters[name] = value;
		if (this.impl.setParameter) {
			this.impl.setParameter(name,value);			
		}
	},
	
	clear : function() {
		for (var i=0; i < this.items.length; i++) {
			if (this.items[i]) {
				this.items[i].destroy();
			}
		};
		this.items = [];
		this.itemContainer.style.display='none';
		this.status.style.display='none';
		if (this.placeholder) {
			this.placeholder.style.display='block';
		}
	},
	addDropTarget : function(options) {
		if (options.element) {
			hui.drag.listen({
				element : options.element,
				hoverClass : options.hoverClass,
				$dropFiles : function(files) {
					if (options.$drop) {
						options.$drop();
					}
					this._transferFiles(files);
				}.bind(this)
			});
		}
	},
	uploadFiles : function(files) {
		this._transferFiles(files);
	},

	//////////////// Private parts ////////////////
	
	_chooseImplementation : function() {
		var impls = hui.ui.Upload.implementations;
		if (this.options.implementation) {
			impls.splice(0,0,this.options.implementation);
		}
		
		for (var i=0; i < impls.length; i++) {
			var impl = hui.ui.Upload[impls[i]];
			var support = impl.support();
			if (support.supported) {
				if (!this.options.multiple) {
					this.impl = new impl(this);
					hui.log('Selected impl (single): '+impls[i]);
					break;
				} else if (this.options.multiple && support.multiple) {
					this.impl = new impl(this);
					hui.log('Selected impl (multiple): '+impls[i]);
					break;
				}
			}
		};
		if (!this.impl) {
			hui.log('No implementation found, using frame');
			this.impl = new hui.ui.Upload.Frame(this);
		}
	},
	_addBehavior : function() {
		if (!this.impl.initialize) {
			alert(this.impl)
			return;
		}
		hui.ui.onReady(function() {
			this.impl.initialize();
			hui.drag.listen({
				element : this.element,
				hoverClass : 'hui_upload_drop',
				$dropFiles : this._transferFiles.bind(this)
			});
		}.bind(this));
	},
	
	//////////////////////////// Dropping ///////////////////////

/*	_onDrop : function(e) {
		hui.log('Drop!')
		hui.stop(e);
			hui.log(e)
		if (e.dataTransfer) {
			var files = e.dataTransfer.files;
			if (files && files.length>0) {
				this._transferFiles(files);
			} else {
				hui.log('No files...');
				hui.log(e.dataTransfer.types)
				if (hui.array.contains(e.dataTransfer.types,'image/tiff')) {
					hui.log(e.dataTransfer.getData('image/tiff'))
				}
				hui.log(e.dataTransfer.getData('text/plain'))
				hui.log(e.dataTransfer.getData('text/html'))
				hui.log(e.dataTransfer.getData('url'))
			}
		} else {
			hui.log(e)
		}
	},*/
	_transferFiles : function(files) {
		if (files.length>0) {
			if (!this.options.multiple) {
				this._transferFile(files[0]);
			} else {
				for (var i=0; i < files.length; i++) {
					var file = files[i];
					this._transferFile(file);
				};
			}
		}
	},
	_transferFile : function(file) {
		hui.log(file)
		var item = this.$_addItem({name:file.name,size:file.size});
		hui.request({
			method : 'post',
			file : file,
			url : this.options.url,
			parameters : this.options.parameters,
			$progress : function(current,total) {
				item.updateProgress(current,total);
			},
			$load : function() {
				hui.log('transferFile: load');
			},
			$abort : function() {
				this.$_itemFail(item);
				item.setError('Afbrudt')
			}.bind(this),
			$success : function(t) {
				hui.log('transferFile: success');
				item.data.request = t;
				this.$_itemSuccess(item);
			}.bind(this),
			$failure : function() {
				hui.log('transferFile: fail');
				this.$_itemFail(item);
			}.bind(this)
		})
	},

	/////////////////////// Implementation ///////////////////////////
	
	/** @private */
	$_addItem : function(info) {
		if (!this.busy) {
			this.fire('uploadDidStartQueue');
			this.status.style.display='block';
			this._setWidgetEnabled(false);
			this.busy = true;
		}
		return this._addItem(info);
	},
	/** @private */
	$_itemSuccess : function(item) {
		var first = hui.get.firstByClass(this.itemContainer,'hui_upload_item_success');
		item.setProgress(1);
		item.setSuccess();
		this.fire('uploadDidComplete',item.getInfo());
		this._checkQueue();
		var move = first!=null || this.items.length>1;
		move = move && item.element.nextSibling!=null;
		
		if (move && (first==null || first!=item.element.nextSibling)) {
			var parent = item.element.parentNode;
			var height = item.element.clientHeight;
			hui.animate({node:item.element,css:{height:'0px'},ease:hui.ease.slowFastSlow,duration:500,onComplete:function() {
				parent.removeChild(item.element);
				if (first) { 
					parent.insertBefore(item.element,first);
				} else {
					parent.appendChild(item.element);
				}
				hui.animate({node:item.element,css:{height:height+'px'},ease:hui.ease.slowFastSlow,duration:200});
			}});
		}

		
	},
	/** @private */
	$_itemFail : function(item) {
		item.setError('Upload af filen fejlede!');
		this.fire('uploadDidFail',item.getInfo());
		this._checkQueue();
	},
	
	/*
	_updateStatus : function() {
		
		if (this.items.length==0) {
			this.status.style.display='none';
		} else {
			hui.dom.setText(this.status,'Status: '+Math.round(s.successful_uploads/this.items.length*100)+'%');
			this.status.style.display='block';
		}
	},*/
	
	/** @private */
	$_getButtonContainer : function() {
		var buttonContainer = hui.build('span',{'class':'hui_upload_button'});
		if (this.options.widget) {
			var w = hui.ui.get(this.options.widget);
			w.element.parentNode.insertBefore(buttonContainer,w.element);
			w.element.parentNode.removeChild(w.element);
			buttonContainer.appendChild(w.element);
		} else {
			buttonContainer.innerHTML='<a href="javascript:void(0);" class="hui_button"><span><span>'+hui.string.escape(hui.ui.getTranslated(this.options.chooseButton))+'</span></span></a>';
			this.element.appendChild(buttonContainer);
		}
		return buttonContainer;
	},
	
	_setWidgetEnabled : function(enabled) {
		if (this.options.widget) {
			var w = hui.ui.get(this.options.widget);
			if (w && w.setEnabled) {
				w.setEnabled(enabled);
			}
		}
	},
	
	_checkQueue : function() {
		for (var i=0; i < this.items.length; i++) {
			if (!this.items[i].isFinished()) {
				return;
			}
		};
		this.busy = false;
		this._setWidgetEnabled(true);
		this.fire('uploadDidCompleteQueue');
	},
	
		
	//////////////////// Events //////////////
		
	/** @private */
	_addItem : function(file) {
		var index = file.index;
		if (index===undefined) {
			index = this.items.length;
			file.index = index;
		}
		var rearrange = index>4;
		var item = new hui.ui.Upload.Item(file,rearrange);
		this.items[index] = item;
		var first = hui.get.firstByClass(this.itemContainer,'hui_upload_item_success');
		if (first) {
			this.itemContainer.insertBefore(item.element,first);
		} else {
			this.itemContainer.appendChild(item.element);
		}
		this.itemContainer.style.display='block';
		if (this.placeholder) {
			this.placeholder.style.display='none';
		}
		return item;
	}
}




/////////////////// Item ///////////////////

/**
 * @class
 * @constructor
 */
hui.ui.Upload.Item = function(info,rearrange) {
	this.data = info;
	this.rearrange = rearrange;
	this.element = hui.build('div',{className:'hui_upload_item'});
	this.element.appendChild(hui.ui.createIcon('file/generic',32));
	this.content = hui.build('div',{className:'hui_upload_item_content',parent:this.element});
	this.progress = hui.ui.ProgressBar.create({small:true});
	this.content.appendChild(this.progress.getElement());
	var text = hui.build('p',{parent:this.content});
	this.info = hui.build('strong',{parent:text});
	this.status = hui.build('em',{parent:text});
	if (info.name) {
		hui.dom.setText(this.info,info.name);
	}
	this.finished = false;
	this.error = false;
}

hui.ui.Upload.Item.prototype = {
	getInfo : function() {
		return this.data;
	},
	isFinished : function() {
		return this.finished;
	},
	setError : function(error) {
		this._setStatus(error || hui.ui.getTranslated({en:'Error',da:'Fejl'}));
		hui.cls.add(this.element,'hui_upload_item_error');
		this.progress.hide();
		this.progress.setValue(0);
		this.finished = true;
	},
	setSuccess : function(status) {
		this._setStatus(hui.ui.getTranslated({en:'Complete',da:'Færdig'}));
		this.progress.setValue(1);
		this.finished = true;
		hui.cls.add(this.element,'hui_upload_item_success');
	},
	updateProgress : function(complete,total) {
		this.setProgress(complete/total);
		return this;
	},
	setProgress : function(value) {
		this._setStatus(hui.ui.getTranslated({en:'Transfering',da:'Overfører'}));
		this.progress.setValue(Math.min(0.9999,value));
		return this;
	},
	setWaiting : function() {
		this._setStatus('Venter');
		this.progress.setWaiting();
		return this;
	},
	hide : function() {
		this.element.hide();
	},
	destroy : function() {
		hui.dom.remove(this.element);
	},
	_setStatus : function(text) {
		if (this._status!==text) {
			hui.dom.setText(this.status,text);
			this._status = text;
		}
	}
}

//// Util ////

hui.ui.Upload._nameIndex = 0;

hui.ui.Upload._buildForm = function(widget) {
	var options = widget.options;

	hui.ui.Upload._nameIndex++;
	var frameName = 'hui_upload_'+hui.ui.Upload._nameIndex;
    hui.log('Frame: name='+frameName);

	var form = hui.build('form');
	form.setAttribute('action',options.url || '');
	form.setAttribute('method','post');
	form.setAttribute('enctype','multipart/form-data');
	form.setAttribute('encoding','multipart/form-data');
	form.setAttribute('target',frameName);
	if (options.parameters) {
		for (var key in options.parameters) {
			var hidden = hui.build('input',{'type':'hidden','name':key});
			hidden.value = options.parameters[key];
			form.appendChild(hidden);
		}
	}
	return form;
}











/////////////////////// Frame //////////////////////////

/**
 * @class
 * @constructor
 */
hui.ui.Upload.Frame = function(parent) {
	this.parent = parent;
}

hui.ui.Upload.Frame.support = function() {
	return {supported:true,multiple:false};
}

hui.ui.Upload.Frame.prototype = {
	
	initialize : function() {
		var options = this.parent.options;
		
		var form = this.form = hui.ui.Upload._buildForm(this.parent);
		var frameName = form.getAttribute('target');
		
		var iframe = this.iframe = hui.build(
            'iframe',{
                name : frameName, 
                id : frameName, 
                src : hui.ui.context+'/hui/html/blank.html', 
                style : 'display:none'
            });
		this.parent.element.appendChild(iframe);
        var self = this;
		hui.listen(iframe,'load',function() {self._uploadComplete()});
		
		this.fileInput = hui.build('input',{'type':'file','name':options.fieldName});
		hui.listen(this.fileInput,'change',this._onSubmit.bind(this));
		
		form.appendChild(this.fileInput);
		var span = hui.build('span',{'class':'hui_upload_button_input'});
		span.appendChild(form);
		var c = this.parent.$_getButtonContainer();		
		c.insertBefore(span,c.firstChild);
	},
	setParameter : function(name,value) {
		var existing = this.form.getElementsByTagName('input');
		for (var i=0; i < existing.length; i++) {
			if (existing[i].name==name) {
				existing[i].value = value;
				return;
			}
		};
		hui.build('input',{'type':'hidden','name':name,'value':value,parent:this.form});
	},
	
	_rebuildParameters : function() {
		// IE: set value of parms again since they disappear
		if (hui.browser.msie) {
			hui.each(this.parent.options.parameters,function(key,value) {
				this.form[key].value = value;
			}.bind(this));
		}
	},
	_rebuildFileInput : function() {
		var options = this.parent.options;
		var old = this.fileInput;
		this.fileInput = hui.build('input',{'type':'file','name':options.fieldName});
		hui.listen(this.fileInput,'change',this._onSubmit.bind(this));
		hui.dom.replaceNode(old,this.fileInput);
		hui.log('Frame: input replaced');
	},
	_getFileName : function() {
		return this.fileInput.value.split('\\').pop();
	},
	_onSubmit : function() {
		this.form.style.display='none';
		this.uploading = true;
		this._rebuildParameters();
		this.form.submit();
		this.item = this.parent.$_addItem({name:this._getFileName()});
		this.item.setWaiting();
		this._rebuildFileInput();
		hui.log('Frame: Upload started:'+this.uploading);
	},
	
	_uploadComplete : function() {
        hui.log('complete:'+this.uploading+' / '+this.parent.name);
		if (!this.uploading) {
			return;
		}
		this.uploading = false;
		var success = this._isSuccessResponse();
		hui.log('Frame: Upload complete: success='+success);
		var item = this.item;
		if (item) {
			if (success) {
				this.parent.$_itemSuccess(item);
				hui.log('Frame: Upload succeeded');
			} else {
				this.parent.$_itemFail(item);
				hui.log('Frame: Upload failed!');
			}
		}
		this.iframe.src = hui.ui.context+'/hui/html/blank.html';
		this.form.style.display = 'block';
		this.form.reset();
	},
	_isSuccessResponse : function() {
		var doc = hui.frame.getDocument(this.iframe);
		return doc.body.innerHTML.indexOf('SUCCESS')!==-1;
	}
}












/////////////////////// Flash //////////////////////////

/**
 * @class
 * @constructor
 */
hui.ui.Upload.Flash = function(parent) {
	this.parent = parent;
	
	this.items = [];
}

hui.ui.Upload.Flash.support = function() {
	return {supported:hui.ui.Flash.getMajorVersion()>=10 && window.SWFUpload!==undefined,multiple:true};
}

hui.ui.Upload.Flash.prototype = {
	initialize : function() {
		var options = this.parent.options;
		
		hui.log('Creating flash verison');
		var url = this._getAbsoluteUrl(options.url);
		var javaSession = hui.cookie.get('JSESSIONID');
		if (javaSession) {
			url+=';jsessionid='+javaSession;
		}
		var phpSession = hui.cookie.get('PHPSESSID');
		if (phpSession) {
			url+='?PHPSESSID='+phpSession;
		}
		var buttonContainer = hui.build('span',{'class':'hui_upload_button'});
		var placeholder = hui.build('span',{'class':'hui_upload_button_object',parent:buttonContainer});
		if (options.widget) {
			var w = hui.ui.get(options.widget);
			w.element.parentNode.insertBefore(buttonContainer,w.element);
			w.element.parentNode.removeChild(w.element);
			buttonContainer.appendChild(w.element);
		} else {
			buttonContainer.innerHTL='<a href="javascript:void(0);" class="hui_button"><span><span>'+options.chooseButton+'</span></span></a>';
			this.parent.element.appendChild(buttonContainer);
		}
		
		this.loader = new SWFUpload({
			upload_url : url,
			flash_url : hui.ui.context+"/hui/lib/swfupload/swfupload.swf",
			file_size_limit : options.maxSize,
			file_queue_limit : options.maxItems,
			file_post_name : options.fieldName,
			file_upload_limit : options.maxItems,
			file_types : options.types,
			debug : !true,
			post_params : options.parameters,
			button_placeholder_id : 'x',
			button_placeholder : placeholder,
			button_width : '100%',
			button_height : 30,

			swfupload_loaded_handler : this._onFlashLoaded.bind(this),
			file_queued_handler : this._onFileQueued.bind(this),
			file_queue_error_handler : this._onFileQueueError.bind(this),
			file_dialog_complete_handler : this._onFileDialogComplete.bind(this),
			upload_start_handler : this._onUploadStart.bind(this),
			upload_progress_handler : this._onUploadProgress.bind(this),
			upload_error_handler : this._onUploadError.bind(this),
			upload_success_handler : this._onUploadSuccess.bind(this),
			upload_complete_handler : this._onUploadComplete.bind(this)
		});
	},
	setParameter : function(key,value) {
		hui.log('Flash: Warning: cannot change parameters');
	},
	_getAbsoluteUrl : function(relative) {
		var loc = new String(document.location);
		var url = loc.slice(0,loc.lastIndexOf('/'));
		while (relative.indexOf('../')===0) {
			relative=relative.substring(3);
			url = url.slice(0,url.lastIndexOf('/'));
		}
		url += '/'+relative;
		return url;
	},
	
	////// Flash listeners /////
	
	_onFlashLoaded : function() {
		hui.log('Flash loaded');
	},
	_onFileQueued : function(file) {
		var item = this.parent.$_addItem({name:file.name,size:file.size});
		item.setWaiting();
		this.items.push(item);
	},
	_onFileQueueError : function(file, error, message) {
		hui.log('Flash: fileQueueError file:'+hui.string.toJSON(file)+', error:'+error+', message:'+message);
		if (file!==null) {
			var item = this.parent.$_addItem({name:file.name,size:file.size});
			this.items.push(item);
			this.parent.$_itemFail(item);
			item.setError(hui.ui.Upload.Flash.errors[error]);
		} else {
			hui.ui.showMessage({text:hui.ui.Upload.Flash.errors[error],duration:4000});
		}
	},
	_onFileDialogComplete : function() {
		hui.log('Flash: fileDialogComplete');
		this.loader.startUpload();
	},
	_onUploadStart : function() {

	},
	_onUploadProgress : function(file,complete,total) {
		var item = this.items[file.index];
		item.updateProgress(complete,total);
	},
	_onUploadError : function(file, error, message) {
		hui.log('Flash: uploadError file:'+file+', error:'+error+', message:'+message);
		if (file) {
			var item = this.items[file.index];
			this.parent.$_itemFail(item);
			item.setError(hui.ui.Upload.Flash.errors[error]);
		}
	},
	/** @private */
	_onUploadSuccess : function(file,data) {
		var item = this.items[file.index];
		item.updateProgress(file.size,file.size);
		this.parent.$_itemSuccess(item);
	},
	/** @private */
	_onUploadComplete : function(file) {
		this.loader.startUpload();		
	}
}

!(function() {
	var e = hui.ui.Upload.Flash.errors = {};
	var s = hui.ui.Upload.Flash.status = {};
	if (window.SWFUpload) {
		e[SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED]			= 'Der er valgt for mange filer';
		e[SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT]		= 'Filen er for stor';
		e[SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE]					= 'Filen er tom';
		e[SWFUpload.QUEUE_ERROR.INVALID_FILETYPE]				= 'Filens type er ikke understøttet';
		e[SWFUpload.UPLOAD_ERROR.HTTP_ERROR]					= 'Der skete en netværksfejl';
		e[SWFUpload.UPLOAD_ERROR.MISSING_UPLOAD_URL]			= 'Upload-adressen findes ikke';
		e[SWFUpload.UPLOAD_ERROR.IO_ERROR]						= 'Der skete en IO-fejl';
		e[SWFUpload.UPLOAD_ERROR.SECURITY_ERROR]				= 'Der skete en sikkerhedsfejl';
		e[SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED]			= 'Upload-størrelsen er overskredet';
		e[SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED]					= 'Upload af filen fejlede';
		e[SWFUpload.UPLOAD_ERROR.SPECIFIED_FILE_ID_NOT_FOUND]	= 'Filens id kunne ikke findes';
		e[SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED]		= 'Validering af filen fejlede';
		e[SWFUpload.UPLOAD_ERROR.FILE_CANCELLED]				= 'Filen blev afbrudt';
		e[SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED]				= 'Upload af filen blev stoppet';
		s[SWFUpload.FILE_STATUS.QUEUED] 		= 'I kø';
		s[SWFUpload.FILE_STATUS.IN_PROGRESS] 	= 'I gang';
		s[SWFUpload.FILE_STATUS.ERROR] 			= 'Filen gav fejl';
		s[SWFUpload.FILE_STATUS.COMPLETE] 		= 'Færdig';
		s[SWFUpload.FILE_STATUS.CANCELLED] 		= 'Afbrudt';
	}
})()








//////////////////// HTML5 //////////////////////


/**
 * @class
 * @constructor
 */
hui.ui.Upload.HTML5 = function(parent) {
	this.parent = parent;
}

hui.ui.Upload.HTML5.support = function() {
	var supported = window.File!==undefined && (hui.browser.webkit || hui.browser.gecko || hui.browser.msie10 || hui.browser.msie11);//(window.File!==undefined && window.FileReader!==undefined && window.FileList!==undefined && window.Blob!==undefined);
	hui.log('HTML5: supported='+supported);
	//supported = !true;
	return {
		supported : supported,
		multiple : true
	};
}

hui.ui.Upload.HTML5.prototype = {
	initialize : function() {
		var options = this.parent.options;
		var span = hui.build('span',{'class':'hui_upload_button_input'});
        this.form = hui.build('form',{'style':'display: inline-block; margin:0;',parent:span});
		var ps = {'type':'file','name':options.fieldName,parent:this.form};
		if (options.multiple) {
			ps.multiple = 'multiple';
		}
		this.fileInput = hui.build('input',ps);
		var c = this.parent.$_getButtonContainer();		
		c.insertBefore(span,c.firstChild);
		hui.listen(this.fileInput,'change',this._submit.bind(this));
	},
	_submit : function(e) {
		var files = this.fileInput.files;
		this.parent._transferFiles(files);
        // TODO: reset/replace input field in IE
        this._resetInput();
	},
    _resetInput : function() {
        this.form.reset();
    }
}

/* EOF */