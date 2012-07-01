var partPosterController = {
	
	widget : null,
	dom : null,
	pageIndex : 0,
	
	$ready : function() {
		sourceWindow.show();
		var form = document.forms.PartForm;
		var recipe = form.recipe.value;
		this.dom = hui.xml.parse(recipe);
		if (!this.dom) {
			
		}
		sourceFormula.setValues({
			recipe : recipe
		})
		this._setPage(0);
		pageWindow.show();
	},
	_connectToWidget : function() {
		this.widget = hui.ui.get('part_poster_'+document.forms.PartForm.id.value);
		this.widget.setPage(this.pageIndex);
		this.widget.listen(this);
	},
	
	// part listener
	$pageChanged : function(index) {
		this._setPage(index);
	},
	
	$valuesChanged$sourceFormula : function(values) {
		var dom = hui.xml.parse(values.recipe);
		if (dom) {
			document.forms.PartForm.recipe.value = values.recipe;
			this.preview();
		}
	},
	preview : function() {
		op.part.utils.updatePreview({
			node : hui.get('part_poster_container'),
			form : document.forms.PartForm,
			type : 'poster',
			delay : 500,
			runScripts : true,
			onComplete : this._connectToWidget.bind(this)
		});
	},
	$valuesChanged$pageFormula : function(values) {
		var node = this.dom.getElementsByTagName('page')[this.pageIndex];
		var text = hui.get.firstByTag(node,'text');
		if (!text) {
			text = this.dom.createElement('text');
			node.appendChild(text);
		}
		hui.dom.setText(text,values.text);

		var title = hui.get.firstByTag(node,'title');
		if (!title) {
			title = this.dom.createElement('title');
			node.appendChild(title);
		}
		hui.dom.setText(title,values.title);

		this._syncDom();
	},
	_setPage : function(index) {
		this.pageIndex = index;
		var values = {};
		var node = this.dom.getElementsByTagName('page')[index];
		var text = hui.get.firstByTag(node,'text');
		if (text) {
			values.text = hui.dom.getText(text);
		}

		var title = hui.get.firstByTag(node,'title');
		if (title) {
			values.title = hui.dom.getText(title);
		}

		pageFormula.setValues(values);
	},
	_syncDom : function() {
		var xml = hui.xml.serialize(this.dom);
		document.forms.PartForm.recipe.value = xml;
		sourceFormula.setValues({recipe:xml})
		this.preview();
	},
	
	// Page controls ...
	
	$click$addPage : function() {
		var pages = this.dom.getElementsByTagName('pages')[0];
		if (pages) {
			
		}
	}
};

hui.ui.listen(partPosterController);

function tabHandleKeyDown(evt) { 
    var tab = String.fromCharCode(9); 
    var e = window.event || evt; 
    var t = e.target ? e.target : e.srcElement ? e.srcElement : e.which; 
    var scrollTop = t.scrollTop; 
    var k = e.keyCode ? e.keyCode : e.charCode ? e.charCode : e.which; 
    if (k == 9 && !e.ctrlKey && !e.altKey) { 
        if(t.setSelectionRange){ 
            e.preventDefault(); 
            var ss = t.selectionStart; 
            var se = t.selectionEnd; 
            // Multi line selection 
            if (ss != se && t.value.slice(ss,se).indexOf("\n") != -1) { 
                if(ss>0){ 
                    ss = t.value.slice(0,ss).lastIndexOf("\n")+1; 
                } 
                var pre = t.value.slice(0,ss); 
                var sel = t.value.slice(ss,se); 
                var post = t.value.slice(se,t.value.length); 
                if(e.shiftKey){ 
                    var a = sel.split("\n") 
                    for (i=0;i<a.length;i++){ 
                        if(a[i].slice(0,1)==tab||a[i].slice(0,1)==' ' ){ 
                            a[i]=a[i].slice(1,a[i].length) 
                        } 
                    } 
                    sel = a.join("\n"); 
                    t.value = pre.concat(sel,post); 
                    t.selectionStart = ss; 
                    t.selectionEnd = pre.length + sel.length; 
                } 
                else{ 
                    sel = sel.replace(/\n/g,"\n"+tab); 
                    pre = pre.concat(tab); 
                    t.value = pre.concat(sel,post); 
                    t.selectionStart = ss; 
                    t.selectionEnd = se + (tab.length * sel.split("\n").length); 
                } 
            } 
            // Single line selection 
            else { 
                if(e.shiftKey){  
                    var brt = t.value.slice(0,ss); 
                    var ch = brt.slice(brt.length-1,brt.length); 
                    if(ch == tab||ch== ' '){ 
                        t.value = brt.slice(0,brt.length-1).concat(t.value.slice(ss,t.value.length)); 
                        t.selectionStart = ss-1; 
                        t.selectionEnd = se-1; 
                    } 
                } 
                else{ 
                    t.value = t.value.slice(0,ss).concat(tab).concat(t.value.slice(ss,t.value.length)); 
                    if (ss == se) { 
                        t.selectionStart = t.selectionEnd = ss + tab.length; 
                    } 
                    else { 
                        t.selectionStart = ss + tab.length; 
                        t.selectionEnd = se + tab.length; 
                    } 
                } 
            } 
        } 
        else{ 
            e.returnValue=false; 
            var r = document.selection.createRange(); 
            var br = document.body.createTextRange(); 
            br.moveToElementText(t); 
            br.setEndPoint("EndToStart", r); 
            //Single line selection 
            if (r.text.length==0||r.text.indexOf("\n") == -1) { 
                if(e.shiftKey){      
                    var ch = br.text.slice(br.text.length-1,br.text.length); 
                    if(ch==tab||ch==' '){ 
                        br.text = br.text.slice(0,br.text.length-1) 
                        r.setEndPoint("StartToEnd", br); 
                    } 
                } 
                else{ 
                    var rtn = t.value.slice(br.text.length,br.text.length+1); 
                    if(rtn!=r.text.slice(0,1)){ 
                        br.text = br.text.concat(rtn);  
                    } 
                    br.text = br.text.concat(tab);  
                } 
                var nr = document.body.createTextRange(); 
                nr.setEndPoint("StartToEnd", br); 
                nr.setEndPoint("EndToEnd", r); 
                nr.select(); 
            } 
            //Multi line selection 
            else{ 
                if(e.shiftKey){      
                    var a = r.text.split("\r\n") 
                    var rt = t.value.slice(br.text.length,br.text.length+2); 
                    if(rt==r.text.slice(0,2)){ 
                        var p = br.text.lastIndexOf("\r\n".concat(tab)); 
                        if(p!=-1){ 
                            br.text = br.text.slice(0,p+2).concat(br.text.slice(p+3,br.text.length)); 
                        } 
                    } 
                    for (i=0;i<a.length;i++){ 
                        var ch = a[i].length>0&&a[i].slice(0,1); 
                        if(ch==tab||ch==' '){ 
                            a[i]=a[i].slice(1,a[i].length) 
                        } 
                    } 
                    r.text = a.join("\r\n"); 
                } 
                else{ 
                    if(br.text.length>0){ 
                        var rt = t.value.slice(br.text.length,br.text.length+2); 
                        if(rt!=r.text.slice(0,2)){ 
                            r.text = tab.concat(r.text.split("\r\n").join("\r\n".concat(tab))); 
                        } 
                        else{ 
                            var p = br.text.slice(0,ss).lastIndexOf("\r\n")+2;   
                            br.text = br.text.slice(0,p).concat(tab,br.text.slice(p,br.text.length)); 
                            r.text = r.text.split("\r\n").join("\r\n".concat(tab)); 
                        } 
                    } 
                    else{ 
                        r.text = tab.concat(r.text).split("\r\n").join("\r\n".concat(tab)); 
                    } 
                }  
                var nr = document.body.createTextRange(); 
                nr.setEndPoint("StartToEnd", br); 
                nr.setEndPoint("EndToEnd", r); 
                nr.select(); 
            } 
        } 
    } 
    t.scrollTop = scrollTop; 
} 