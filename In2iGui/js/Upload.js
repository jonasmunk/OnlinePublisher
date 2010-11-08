/**
 * @class
 * @constructor
 *
 * Options
 * {url:'',parameters:{}}
 *
 * Events:
 * uploadDidCompleteQueue / queueComplete
 * uploadDidStartQueue
 * uploadDidComplete(file)
 */
In2iGui.Upload = function(options) {
	this.options = n2i.override({url:'',parameters:{},maxItems:50,maxSize:"20480",types:"*.*",useFlash:true,fieldName:'file',chooseButton:'Choose files...'},options);
	this.element = $(options.element);
	this.itemContainer = this.element.select('.in2igui_upload_items')[0];
	this.status = this.element.select('.in2igui_upload_status')[0];
	this.placeholder = this.element.select('.in2igui_upload_placeholder')[0];
	this.name = options.name;
	this.items = [];
	this.busy = false;
	this.loaded = false;
	this.useFlash = this.options.useFlash;
	if (this.options.useFlash) {
		this.useFlash = In2iGui.Flash.getMajorVersion()>=10;
	}
	In2iGui.extend(this);
	this.addBehavior();
}

In2iGui.Upload.nameIndex = 0;

/** Creates a new upload widget */
In2iGui.Upload.create = function(options) {
	options = options || {};
	options.element = new Element('div',{'class':'in2igui_upload'});
	options.element.update(
		'<div class="in2igui_upload_items"></div>'+
		'<div class="in2igui_upload_status"></div>'+
		(options.placeholder ? '<div class="in2igui_upload_placeholder"><span class="in2igui_upload_icon"></span>'+
			(options.placeholder.title ? '<h2>'+options.placeholder.title+'</h2>' : '')+
			(options.placeholder.text ? '<p>'+options.placeholder.text+'</p>' : '')+
		'</div>' : '')
	);
	return new In2iGui.Upload(options);
}

In2iGui.Upload.prototype = {
	/** @private */
	addBehavior : function() {
		if (!this.useFlash) {
			this.createIframeVersion();
			return;
		}
		In2iGui.onDomReady(this.createFlashVersion.bind(this));
	},
	/**
	 * Change a parameter
	 */
	setParameter : function(name,value) {
		if (this.useFlash) {
			alert('Not implemented for flash');
		} else {
			var existing = this.form.getElementsByTagName('input');
			for (var i=0; i < existing.length; i++) {
				if (existing[i].name==name) {
					existing[i].value = value;
					return;
				}
			};
			this.form.insert(new Element('input',{'type':'hidden','name':name,'value':value}));
		}
	},
	
	/////////////////////////// Iframe //////////////////////////
	
	/** @private */
	createIframeVersion : function() {
		In2iGui.Upload.nameIndex++;
		var frameName = 'in2igui_upload_'+In2iGui.Upload.nameIndex;
		
		var atts = {'action':this.options.url || '', 'method':'post', 'enctype':'multipart/form-data','encoding':'multipart/form-data','target':frameName};
		var form = this.form = new Element('form',atts);
		if (this.options.parameters) {
			$H(this.options.parameters).each(function(pair) {
				var hidden = new Element('input',{'type':'hidden','name':pair.key});
				hidden.setValue(pair.value);
				form.insert(hidden);
			});
		}
		var iframe = this.iframe = new Element('iframe',{name:frameName,id:frameName,src:In2iGui.context+'/In2iGui/html/blank.html',style:'display:none'});
		this.element.insert(iframe);
		this.fileInput = new Element('input',{'type':'file','class':'file','name':this.options.fieldName});
		this.fileInput.observe('change',this.iframeSubmit.bind(this));
		form.insert(this.fileInput);
		var buttonContainer = new Element('span',{'class':'in2igui_upload_button'});
		var span = new Element('span',{'class':'in2igui_upload_button_input'});
		span.insert(form);
		buttonContainer.insert(span);
		if (this.options.widget) {
			In2iGui.onDomReady(function() {
				var w = In2iGui.get(this.options.widget);
				w.element.wrap(buttonContainer);
			}.bind(this));
		} else {
			buttonContainer.insert('<a href="javascript:void(0);" class="in2igui_button"><span><span>'+this.options.chooseButton+'</span></span></a>');
			this.element.insert(buttonContainer);
		}
		iframe.observe('load',function() {this.iframeUploadComplete()}.bind(this));
	},
	/** @private */
	iframeUploadComplete : function() {
		if (!this.uploading) return;
		n2i.log('iframeUploadComplete uploading: '+this.uploading+' ('+this.name+')');
		this.uploading = false;
		this.form.reset();
		var doc = n2i.getFrameDocument(this.iframe);
		var last = this.items.last();
		if (doc.body.innerHTML.indexOf('SUCCESS')!=-1) {
			if (last) {
				last.update({progress:1,filestatus:'Færdig'});
			}
		} else if (last) {
			last.setError('Upload af filen fejlede!');
		}
		this.fire('uploadDidCompleteQueue');
		this.fire('queueComplete');
		this.iframe.src=In2iGui.context+'/In2iGui/html/blank.html';
		this.endIframeProgress();
	},
	/** @private */
	iframeSubmit : function() {
		this.startIframeProgress();
		this.uploading = true;
		// IE: set value of parms again since they disappear
		if (n2i.browser.msie) {
			var p = this.options.parameters;
			$H(this.options.parameters).each(function(pair) {
				this.form[pair.key].value=pair.value;
			}.bind(this));
		}
		this.form.submit();
		this.fire('uploadDidStartQueue');
		var fileName = this.fileInput.value.split('\\').pop();
		this.addItem({name:fileName,filestatus:'I gang'}).setWaiting();
	},
	/** @private */
	startIframeProgress : function() {
		this.form.style.display='none';
	},
	/** @private */
	endIframeProgress : function() {
		n2i.log('endIframeProgress');
		this.form.style.display='block';
		this.form.reset();
	},
	/** @public */
	clear : function() {
		for (var i=0; i < this.items.length; i++) {
			if (this.items[i]) {
				this.items[i].destroy();
			}
		};
		this.items = [];
		this.itemContainer.hide();
		this.status.hide();
		if (this.placeholder) {
			this.placeholder.show();
		}
	},
	
	/////////////////////////// Flash //////////////////////////
	
	/** @private */
	getAbsoluteUrl : function(relative) {
		var loc = new String(document.location);
		var url = loc.slice(0,loc.lastIndexOf('/'));
		while (relative.indexOf('../')===0) {
			relative=relative.substring(3);
			url = url.slice(0,url.lastIndexOf('/'));
		}
		url += '/'+relative;
		return url;
	},
	
	/** @private */
	createFlashVersion : function() {
		n2i.log('Creating flash verison');
		var url = this.getAbsoluteUrl(this.options.url);
		var javaSession = n2i.cookie.get('JSESSIONID');
		if (javaSession) {
			url+=';jsessionid='+javaSession;
		}
		var phpSession = n2i.cookie.get('PHPSESSID');
		if (phpSession) {
			url+='?PHPSESSID='+phpSession;
		}
		var buttonContainer = new Element('span',{'class':'in2igui_upload_button'});
		var placeholder = new Element('span',{'class':'in2igui_upload_button_object'});
		buttonContainer.insert(placeholder);
		if (this.options.widget) {
			var w = In2iGui.get(this.options.widget);
			w.element.wrap(buttonContainer);
		} else {
			buttonContainer.insert('<a href="javascript:void(0);" class="in2igui_button"><span><span>'+this.options.chooseButton+'</span></span></a>');
			this.element.insert(buttonContainer);
		}
		
		var self = this;
		this.loader = new SWFUpload({
			upload_url : url,
			flash_url : In2iGui.context+"/In2iGui/lib/swfupload/swfupload.swf",
			file_size_limit : this.options.maxSize,
			file_queue_limit : this.options.maxItems,
			file_post_name : this.options.fieldName,
			file_upload_limit : this.options.maxItems,
			file_types : this.options.types,
			debug : true,
			post_params : this.options.parameters,
			button_placeholder_id : 'x',
			button_placeholder : placeholder,
			button_width : '100%',
			button_height : 30,

			swfupload_loaded_handler : this.flashLoaded.bind(this),
			file_queued_handler : self.fileQueued.bind(self),
			file_queue_error_handler : this.fileQueueError.bind(this),
			file_dialog_complete_handler : this.fileDialogComplete.bind(this),
			upload_start_handler : this.uploadStart.bind(this),
			upload_progress_handler : this.uploadProgress.bind(this),
			upload_error_handler : this.uploadError.bind(this),
			upload_success_handler : this.uploadSuccess.bind(this),
			upload_complete_handler : this.uploadComplete.bind(this)
		});
	},
	/** @private */
	startNextUpload : function() {
		this.loader.startUpload();
	},
	
	//////////////////// Events //////////////
	
	/** @private */
	flashLoaded : function() {
		n2i.log('flash loaded');
		this.loaded = true;
	},
	/** @private */
	addError : function(file,error) {
		var item = this.addItem(file);
		item.setError(error);
	},
	/** @private */
	fileQueued : function(file) {
		this.addItem(file);
	},
	/** @private */
	fileQueueError : function(file, error, message) {
		if (file!==null) {
			this.addError(file,error);
		} else {
			In2iGui.showMessage({text:In2iGui.Upload.errors[error],duration:4000});
		}
	},
	/** @private */
	fileDialogComplete : function() {
		n2i.log('fileDialogComplete');
		this.startNextUpload();
	},
	/** @private */
	uploadStart : function() {
		this.status.setStyle({display:'block'});
		n2i.log('uploadStart');
		if (!this.busy) {
			this.fire('uploadDidStartQueue');
		}
		this.busy = true;
	},
	/** @private */
	uploadProgress : function(file,complete,total) {
		this.updateStatus();
		this.items[file.index].updateProgress(complete,total);
	},
	/** @private */
	uploadError : function(file, error, message) {
		n2i.log('uploadError file:'+file+', error:'+error+', message:'+message);
		if (file) {
			this.items[file.index].update(file);
		}
	},
	/** @private */
	uploadSuccess : function(file,data) {
		n2i.log('uploadSuccess file:'+file+', data:'+data);
		this.items[file.index].updateProgress(file.size,file.size);
	},
	/** @private */
	uploadComplete : function(file) {
		this.items[file.index].update(file);
		this.startNextUpload();
		var self = this;
		this.fire('uploadDidComplete',file);
		if (this.loader.getStats().files_queued==0) {
			this.fire('uploadDidCompleteQueue');
			this.fire('queueComplete');
		}
		this.updateStatus();
		this.busy = false;
	},
	
	//////////// Items ////////////
	
	/** @private */
	addItem : function(file) {
		var index = file.index;
		if (index===undefined) {
			index = this.items.length;
			file.index = index;
		}
		var item = new In2iGui.Upload.Item(file);
		this.items[index] = item;
		this.itemContainer.insert(item.element);
		this.itemContainer.setStyle({display:'block'});
		if (this.placeholder) {
			this.placeholder.hide();
		}
		return item;
	},
	
	/** @private */
	updateStatus : function() {
		var s = this.loader.getStats();
		if (this.items.length==0) {
			this.status.hide();
		} else {
			this.status.update('Status: '+Math.round(s.successful_uploads/this.items.length*100)+'%').setStyle({display:'block'});
		}
		n2i.log(s);
	}
}

In2iGui.Upload.Item = function(file) {
	this.element = new Element('div').addClassName('in2igui_upload_item');
	if (file.index % 2 == 1) {
		this.element.addClassName('in2igui_upload_item_alt')
	}
	this.content = new Element('div').addClassName('in2igui_upload_item_content');
	this.icon = In2iGui.createIcon('file/generic',2);
	this.element.insert(this.icon);
	this.element.insert(this.content);
	this.info = new Element('strong');
	this.status = new Element('em');
	this.progress = In2iGui.ProgressBar.create({small:true});
	this.content.insert(this.progress.getElement());
	this.content.insert(this.info);
	this.content.insert(this.status);
	this.update(file);
}

In2iGui.Upload.Item.prototype = {
	update : function(file) {
		this.status.update(In2iGui.Upload.status[file.filestatus] || file.filestatus);
		if (file.name) {
			this.info.update(file.name);
		}
		if (file.progress!==undefined) {
			this.setProgress(file.progress);
		}
		if (file.filestatus==SWFUpload.FILE_STATUS.ERROR) {
			this.element.addClassName('in2igui_upload_item_error');
			this.progress.hide();
		}
	},
	setError : function(error) {
		this.status.update(In2iGui.Upload.errors[error] || error);
		this.element.addClassName('in2igui_upload_item_error');
		this.progress.hide();
	},
	updateProgress : function(complete,total) {
		this.progress.setValue(complete/total);
		return this;
	},
	setProgress : function(value) {
		this.progress.setValue(value);
		return this;
	},
	setWaiting : function(value) {
		this.progress.setWaiting();
		return this;
	},
	hide : function() {
		this.element.hide();
	},
	destroy : function() {
		this.element.remove();
	}
}

if (window.SWFUpload) {
(function(){
	var e = In2iGui.Upload.errors = {};
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
	var s = In2iGui.Upload.status = {};
	s[SWFUpload.FILE_STATUS.QUEUED] 		= 'I kø';
	s[SWFUpload.FILE_STATUS.IN_PROGRESS] 	= 'I gang';
	s[SWFUpload.FILE_STATUS.ERROR] 			= 'Filen gav fejl';
	s[SWFUpload.FILE_STATUS.COMPLETE] 		= 'Færdig';
	s[SWFUpload.FILE_STATUS.CANCELLED] 		= 'Afbrudt';
}())
}
/* EOF */