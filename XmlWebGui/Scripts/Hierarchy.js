var HierarchyConfig = {
	rootIcon        : xwgGraphics+'Hierarchy/foldericon.png',
	openRootIcon    : xwgGraphics+'Hierarchy/openfoldericon.png',
	folderIcon      : xwgIconset+'Element/FolderStandard1.gif',
	openFolderIcon  : xwgIconset+'Element/FolderStandard1.gif',
	fileIcon        : xwgIconset+'File/GenericStandard1.gif',
	iIcon           : xwgGraphics+'Hierarchy/I.png',
	lIcon           : xwgGraphics+'Hierarchy/L.png',
	lMinusIcon      : xwgGraphics+'Hierarchy/Lminus.png',
	lPlusIcon       : xwgGraphics+'Hierarchy/Lplus.png',
	tIcon           : xwgGraphics+'Hierarchy/T.png',
	tMinusIcon      : xwgGraphics+'Hierarchy/Tminus.png',
	tPlusIcon       : xwgGraphics+'Hierarchy/Tplus.png',
	blankIcon       : xwgGraphics+'Hierarchy/blank.png',
	defaultText     : '',
	defaultAction   : 'javascript:void(0);',
	defaultBehavior : 'classic',
	usePersistence	: false
};

var HierarchyHandler = {
	idCounter : 0,
	selection : false,
	idPrefix  : "hierarchy-object-",
	all       : {},
	behavior  : null,
	deleagte  : null,
	highlighted  : null,
	onSelect  : null, /* should be part of tree, not handler */
	getId     : function() { return this.idPrefix + this.idCounter++; },
	toggle    : function (oItem) { this.all[oItem.id.replace('-plus','')].toggle(); },
	select    : function (oItem) { this.all[oItem.id.replace('-icon','')].select(); },
	focus     : function (oItem) { this.all[oItem.id.replace('-anchor','')].focus(); },
	blur      : function (oItem) { this.all[oItem.id.replace('-anchor','')].blur(); },
	contextmenu : function (id,event) {
		return this.callDelegate('contextMenuWillShow',this.all[id],event);
	},
	itemWasClicked : function (oItem) {
		if (this.selection) {
			var id = oItem.id.replace('-anchor','');
			if (this.highlighted) {
				this.all[this.highlighted].resetHighlight();
			}
			this.all[id].highlight();
			this.callDelegate('itemWasSelected',this.all[id]);
			this.highlighted = id;
		}
	},
	changeSelection : function (unique) {
		var id = null;
		for (var item in this.all) {
			if (this.all[item].unique == unique) {
				id=item;
			}
		}
		if (this.all[id]) {
			this.all[id].highlight();
		}
		if (this.all[this.highlighted] && this.highlighted!=id) {
			this.all[this.highlighted].resetHighlight();
		}
		this.highlighted = id;
	},
	setSelection : function(unique) {
		for (var item in this.all) {
			if (this.all[item].unique == unique) {
				this.highlighted = item;
			}
		}
	},
	callDelegate : function(action,value,event) {
		if (this.delegate) {
			if (action=='itemWasSelected' && this.delegate.itemWasSelected) {
				this.delegate.itemWasSelected(value);
			}
			else if (action=='contextMenuWillShow' && this.delegate.contextMenuWillShow) {
				return this.delegate.contextMenuWillShow(value,event);
			}
		}
	},
	keydown   : function (oItem, e) { return this.all[oItem.id].keydown(e.keyCode); },
	cookies   : new HierarchyCookie(),
	insertHTMLBeforeEnd	:	function (oElement, sHTML) {
		if (oElement.insertAdjacentHTML != null) {
			oElement.insertAdjacentHTML("BeforeEnd", sHTML)
			return;
		}
		var df;	// DocumentFragment
		var r = oElement.ownerDocument.createRange();
		r.selectNodeContents(oElement);
		r.collapse(false);
		df = r.createContextualFragment(sHTML);
		oElement.appendChild(df);
	}
};

/*
 * HierarchyCookie class
 */

function HierarchyCookie() {
	if (document.cookie.length) { this.cookies = ' ' + document.cookie; }
}

HierarchyCookie.prototype.setCookie = function (key, value) {
	document.cookie = key + "=" + escape(value);
}

HierarchyCookie.prototype.getCookie = function (key) {
	if (this.cookies) {
		var start = this.cookies.indexOf(' ' + key + '=');
		if (start == -1) { return null; }
		var end = this.cookies.indexOf(";", start);
		if (end == -1) { end = this.cookies.length; }
		end -= start;
		var cookie = this.cookies.substr(start,end);
		return unescape(cookie.substr(cookie.indexOf('=') + 1, cookie.length - cookie.indexOf('=') + 1));
	}
	else { return null; }
}

/*
 * HierarchyAbstractNode class
 */

function HierarchyAbstractNode(sText, sAction) {
	this.childNodes  = [];
	this.id     = HierarchyHandler.getId();
	this.text   = sText || HierarchyConfig.defaultText;
	this.action = sAction || HierarchyConfig.defaultAction;
	this._last  = false;
	HierarchyHandler.all[this.id] = this;
}

/*
 * To speed thing up if you're adding multiple nodes at once (after load)
 * use the bNoIdent parameter to prevent automatic re-indentation and call
 * the obj.ident() method manually once all nodes has been added.
 */

HierarchyAbstractNode.prototype.add = function (node, bNoIdent) {
	node.parentNode = this;
	this.childNodes[this.childNodes.length] = node;
	var root = this;
	if (this.childNodes.length >= 2) {
		this.childNodes[this.childNodes.length - 2]._last = false;
	}
	while (root.parentNode) { root = root.parentNode; }
	if (root.rendered) {
		if (this.childNodes.length >= 2) {
			document.getElementById(this.childNodes[this.childNodes.length - 2].id + '-plus').src = ((this.childNodes[this.childNodes.length -2].folder)?((this.childNodes[this.childNodes.length -2].open)?HierarchyConfig.tMinusIcon:HierarchyConfig.tPlusIcon):HierarchyConfig.tIcon);
			this.childNodes[this.childNodes.length - 2].plusIcon = HierarchyConfig.tPlusIcon;
			this.childNodes[this.childNodes.length - 2].minusIcon = HierarchyConfig.tMinusIcon;
			this.childNodes[this.childNodes.length - 2]._last = false;
		}
		this._last = true;
		var foo = this;
		while (foo.parentNode) {
			for (var i = 0; i < foo.parentNode.childNodes.length; i++) {
				if (foo.id == foo.parentNode.childNodes[i].id) { break; }
			}
			if (i == foo.parentNode.childNodes.length - 1) { foo.parentNode._last = true; }
			else { foo.parentNode._last = false; }
			foo = foo.parentNode;
		}
		HierarchyHandler.insertHTMLBeforeEnd(document.getElementById(this.id + '-cont'), node.toString());
		if ((!this.folder) && (!this.openIcon)) {
			this.icon = HierarchyConfig.folderIcon;
			this.openIcon = HierarchyConfig.openFolderIcon;
		}
		if (!this.folder) { this.folder = true; this.collapse(true); }
		if (!bNoIdent) { this.indent(); }
	}
	return node;
}

HierarchyAbstractNode.prototype.toggle = function() {
	if (this.folder) {
		if (this.open) { this.collapse(); }
		else { this.expand(); }
	}	
}

HierarchyAbstractNode.prototype.select = function() {
	document.getElementById(this.id + '-anchor').focus();
}

HierarchyAbstractNode.prototype.deSelect = function() {
	document.getElementById(this.id + '-anchor').className = '';
	HierarchyHandler.selected = null;
}

HierarchyAbstractNode.prototype.focus = function() {
	if ((HierarchyHandler.selected) && (HierarchyHandler.selected != this)) { HierarchyHandler.selected.deSelect(); }
	HierarchyHandler.selected = this;
	if ((this.openIcon) && (HierarchyHandler.behavior != 'classic')) { document.getElementById(this.id + '-icon').src = this.openIcon; }
	document.getElementById(this.id + '-anchor').className = 'selected';
	document.getElementById(this.id + '-anchor').focus();
	if (HierarchyHandler.onSelect) { HierarchyHandler.onSelect(this); }
}

HierarchyAbstractNode.prototype.blur = function() {
	if ((this.openIcon) && (HierarchyHandler.behavior != 'classic')) { document.getElementById(this.id + '-icon').src = this.icon; }
	document.getElementById(this.id + '-anchor').className = 'selected-inactive';
}

HierarchyAbstractNode.prototype.doExpand = function() {
	if (HierarchyHandler.behavior == 'classic') { document.getElementById(this.id + '-icon').src = this.openIcon; }
	if (this.childNodes.length) {  document.getElementById(this.id + '-cont').style.display = 'block'; }
	this.open = true;
	if (HierarchyConfig.usePersistence) {
		HierarchyHandler.cookies.setCookie(this.id, '1');
}	}

HierarchyAbstractNode.prototype.doCollapse = function() {
	if (HierarchyHandler.behavior == 'classic') { document.getElementById(this.id + '-icon').src = this.icon; }
	if (this.childNodes.length) { document.getElementById(this.id + '-cont').style.display = 'none'; }
	this.open = false;
	if (HierarchyConfig.usePersistence) {
		HierarchyHandler.cookies.setCookie(this.id, '0');
}	}

HierarchyAbstractNode.prototype.expandAll = function() {
	this.expandChildren();
	if ((this.folder) && (!this.open)) { this.expand(); }
}

HierarchyAbstractNode.prototype.expandChildren = function() {
	for (var i = 0; i < this.childNodes.length; i++) {
		this.childNodes[i].expandAll();
} }

HierarchyAbstractNode.prototype.collapseAll = function() {
	this.collapseChildren();
	if ((this.folder) && (this.open)) { this.collapse(true); }
}

HierarchyAbstractNode.prototype.collapseChildren = function() {
	for (var i = 0; i < this.childNodes.length; i++) {
		this.childNodes[i].collapseAll();
} }

HierarchyAbstractNode.prototype.highlight = function() {
	document.getElementById(this.id).className='HierarchyItem highlighted';
}

HierarchyAbstractNode.prototype.resetHighlight = function() {
	document.getElementById(this.id).className='HierarchyItem';
}

HierarchyAbstractNode.prototype.indent = function(lvl, del, last, level, nodesLeft) {
	/*
	 * Since we only want to modify items one level below ourself,
	 * and since the rightmost indentation position is occupied by
	 * the plus icon we set this to -2
	 */
	if (lvl == null) { lvl = -2; }
	var state = 0;
	for (var i = this.childNodes.length - 1; i >= 0 ; i--) {
		state = this.childNodes[i].indent(lvl + 1, del, last, level);
		if (state) { return; }
	}
	if (del) {
		if ((level >= this._level) && (document.getElementById(this.id + '-plus'))) {
			if (this.folder) {
				document.getElementById(this.id + '-plus').src = (this.open)?HierarchyConfig.lMinusIcon:HierarchyConfig.lPlusIcon;
				this.plusIcon = HierarchyConfig.lPlusIcon;
				this.minusIcon = HierarchyConfig.lMinusIcon;
			}
			else if (nodesLeft) { document.getElementById(this.id + '-plus').src = HierarchyConfig.lIcon; }
			return 1;
	}	}
	var foo = document.getElementById(this.id + '-indent-' + lvl);
	if (foo) {
		if ((foo._last) || ((del) && (last))) { foo.src =  HierarchyConfig.blankIcon; }
		else { foo.src =  HierarchyConfig.iIcon; }
	}
	return 0;
}

/*
 * Hierarchy class
 */

function Hierarchy(sText, sAction, sBehavior, sIcon, sOpenIcon) {
	this.base = HierarchyAbstractNode;
	this.base(sText, sAction);
	this.icon      = sIcon || HierarchyConfig.rootIcon;
	this.openIcon  = sOpenIcon || HierarchyConfig.openRootIcon;
	/* Defaults to open */
	if (HierarchyConfig.usePersistence) {
		this.open  = (HierarchyHandler.cookies.getCookie(this.id.substr(18,this.id.length - 18)) == '0')?false:true;
	} else { this.open  = true; }
	this.folder    = true;
	this.rendered  = false;
	this.onSelect  = null;
	if (!HierarchyHandler.behavior) {  HierarchyHandler.behavior = sBehavior || HierarchyConfig.defaultBehavior; }
}

Hierarchy.prototype = new HierarchyAbstractNode;

Hierarchy.prototype.setBehavior = function (sBehavior) {
	HierarchyHandler.behavior =  sBehavior;
};

Hierarchy.prototype.getBehavior = function (sBehavior) {
	return HierarchyHandler.behavior;
};

Hierarchy.prototype.getSelected = function() {
	if (HierarchyHandler.selected) { return HierarchyHandler.selected; }
	else { return null; }
}

Hierarchy.prototype.remove = function() { }

Hierarchy.prototype.expand = function() {
	this.doExpand();
}

Hierarchy.prototype.collapse = function(b) {
	if (!b) { this.focus(); }
	this.doCollapse();
}

Hierarchy.prototype.getFirst = function() {
	return null;
}

Hierarchy.prototype.getLast = function() {
	return null;
}

Hierarchy.prototype.getNextSibling = function() {
	return null;
}

Hierarchy.prototype.getPreviousSibling = function() {
	return null;
}

Hierarchy.prototype.keydown = function(key) {
	if (key == 39) {
		if (!this.open) { this.expand(); }
		else if (this.childNodes.length) { this.childNodes[0].select(); }
		return false;
	}
	if (key == 37) { this.collapse(); return false; }
	if ((key == 40) && (this.open) && (this.childNodes.length)) { this.childNodes[0].select(); return false; }
	return true;
}

Hierarchy.prototype.toString = function() {

	var str = "<div id=\"" + this.id + "-cont\" class=\"HierarchyContainer\" style=\"display: " + ((this.open)?'block':'none') + ";\">";
	var sb = [];
	for (var i = 0; i < this.childNodes.length; i++) {
		sb[i] = this.childNodes[i].toString(i, this.childNodes.length);
	}
	this.rendered = true;
	return str + sb.join("") + "</div>";
};

/*
 * HierarchyItem class
 */

function HierarchyItem(sText, sAction, eParent, sIcon, sOpenIcon) {
	this.base = HierarchyAbstractNode;
	this.style='Standard';
	this.info = {};
	this.base(sText, sAction);
	/* Defaults to close */
	if (HierarchyConfig.usePersistence) {
		this.open = (HierarchyHandler.cookies.getCookie(this.id) == '1')?true:false;
	} else { this.open = false; }
	if (sIcon) { this.icon = sIcon; }
	if (sOpenIcon) { this.openIcon = sOpenIcon; }
	if (eParent) { eParent.add(this); }
}

HierarchyItem.prototype = new HierarchyAbstractNode;

HierarchyItem.prototype.remove = function() {
	var iconSrc = document.getElementById(this.id + '-plus').src;
	var parentNode = this.parentNode;
	var prevSibling = this.getPreviousSibling(true);
	var nextSibling = this.getNextSibling(true);
	var folder = this.parentNode.folder;
	var last = ((nextSibling) && (nextSibling.parentNode) && (nextSibling.parentNode.id == parentNode.id))?false:true;
	this.getPreviousSibling().focus();
	this._remove();
	if (parentNode.childNodes.length == 0) {
		document.getElementById(parentNode.id + '-cont').style.display = 'none';
		parentNode.doCollapse();
		parentNode.folder = false;
		parentNode.open = false;
	}
	if (!nextSibling || last) { parentNode.indent(null, true, last, this._level, parentNode.childNodes.length); }
	if ((prevSibling == parentNode) && !(parentNode.childNodes.length)) {
		prevSibling.folder = false;
		prevSibling.open = false;
		iconSrc = document.getElementById(prevSibling.id + '-plus').src;
		iconSrc = iconSrc.replace('minus', '').replace('plus', '');
		document.getElementById(prevSibling.id + '-plus').src = iconSrc;
		document.getElementById(prevSibling.id + '-icon').src = HierarchyConfig.fileIcon;
	}
	if (document.getElementById(prevSibling.id + '-plus')) {
		if (parentNode == prevSibling.parentNode) {
			iconSrc = iconSrc.replace('minus', '').replace('plus', '');
			document.getElementById(prevSibling.id + '-plus').src = iconSrc;
}	}	}

HierarchyItem.prototype._remove = function() {
	for (var i = this.childNodes.length - 1; i >= 0; i--) {
		this.childNodes[i]._remove();
 	}
	for (var i = 0; i < this.parentNode.childNodes.length; i++) {
		if (this == this.parentNode.childNodes[i]) {
			for (var j = i; j < this.parentNode.childNodes.length; j++) {
				this.parentNode.childNodes[j] = this.parentNode.childNodes[j+1];
			}
			this.parentNode.childNodes.length -= 1;
			if (i + 1 == this.parentNode.childNodes.length) { this.parentNode._last = true; }
			break;
	}	}
	HierarchyHandler.all[this.id] = null;
	var tmp = document.getElementById(this.id);
	if (tmp) { tmp.parentNode.removeChild(tmp); }
	tmp = document.getElementById(this.id + '-cont');
	if (tmp) { tmp.parentNode.removeChild(tmp); }
}

HierarchyItem.prototype.expand = function() {
	this.doExpand();
	document.getElementById(this.id + '-plus').src = this.minusIcon;
}

HierarchyItem.prototype.collapse = function(b) {
	//if (!b) { this.focus(); }
	this.doCollapse();
	document.getElementById(this.id + '-plus').src = this.plusIcon;
}

HierarchyItem.prototype.getFirst = function() {
	return this.childNodes[0];
}

HierarchyItem.prototype.getLast = function() {
	if (this.childNodes[this.childNodes.length - 1].open) { return this.childNodes[this.childNodes.length - 1].getLast(); }
	else { return this.childNodes[this.childNodes.length - 1]; }
}

HierarchyItem.prototype.getNextSibling = function() {
	for (var i = 0; i < this.parentNode.childNodes.length; i++) {
		if (this == this.parentNode.childNodes[i]) { break; }
	}
	if (++i == this.parentNode.childNodes.length) { return this.parentNode.getNextSibling(); }
	else { return this.parentNode.childNodes[i]; }
}

HierarchyItem.prototype.getPreviousSibling = function(b) {
	for (var i = 0; i < this.parentNode.childNodes.length; i++) {
		if (this == this.parentNode.childNodes[i]) { break; }
	}
	if (i == 0) { return this.parentNode; }
	else {
		if ((this.parentNode.childNodes[--i].open) || (b && this.parentNode.childNodes[i].folder)) { return this.parentNode.childNodes[i].getLast(); }
		else { return this.parentNode.childNodes[i]; }
} }

HierarchyItem.prototype.keydown = function(key) {
	if ((key == 39) && (this.folder)) {
		if (!this.open) { this.expand(); }
		else { this.getFirst().select(); }
		return false;
	}
	else if (key == 37) {
		if (this.open) { this.collapse(); }
		else { this.parentNode.select(); }
		return false;
	}
	else if (key == 40) {
		if (this.open) { this.getFirst().select(); }
		else {
			var sib = this.getNextSibling();
			if (sib) { sib.select(); }
		}
		return false;
	}
	else if (key == 38) { this.getPreviousSibling().select(); return false; }
	return true;
}

HierarchyItem.prototype.toString = function (nItem, nItemCount) {
	var foo = this.parentNode;
	var indent = '';
	if (nItem + 1 == nItemCount) { this.parentNode._last = true; }
	var i = 0;
	while (foo.parentNode) {
		foo = foo.parentNode;
		indent = "<img id=\"" + this.id + "-indent-" + i + "\" src=\"" + ((foo._last)?HierarchyConfig.blankIcon:HierarchyConfig.iIcon) + "\" width=\"19\" height=\"18\">" + indent;
		i++;
	}
	this._level = i;
	if (this.childNodes.length) { this.folder = 1; }
	else { this.open = false; }
	if ((this.folder) || (HierarchyHandler.behavior != 'classic')) {
		if (!this.icon) { this.icon = HierarchyConfig.folderIcon; }
		if (!this.openIcon) { this.openIcon = HierarchyConfig.openFolderIcon; }
	}
	else if (!this.icon) { this.icon = HierarchyConfig.fileIcon; }
	var label = this.text.replace(/</g, '&lt;').replace(/>/g, '&gt;');
	var str = "<div id=\"" + this.id + "\" ondblclick=\"HierarchyHandler.toggle(this);\" oncontextmenu=\"return HierarchyHandler.contextmenu('"+this.id+"',event);\""+
	" class=\"HierarchyItem HierarchyItem"+this.style+(HierarchyHandler.selection && HierarchyHandler.highlighted==this.id ? " highlighted" : "")+"\""+
	" onkeydown=\"return HierarchyHandler.keydown(this, event)\" nowrap=\"true\">" +
		indent +
		"<img id=\"" + this.id + "-plus\" src=\"" + ((this.folder)?((this.open)?((this.parentNode._last)?HierarchyConfig.lMinusIcon:HierarchyConfig.tMinusIcon):((this.parentNode._last)?HierarchyConfig.lPlusIcon:HierarchyConfig.tPlusIcon)):((this.parentNode._last)?HierarchyConfig.lIcon:HierarchyConfig.tIcon)) + "\" onclick=\"HierarchyHandler.toggle(this);\" ondblclick=\"HierarchyHandler.toggle(this);\" width=\"19\" height=\"18\">" +
		"<span id=\""+this.dropId+"\"><img id=\"" + this.id + "-icon\" class=\"HierarchyIcon\" src=\"" + ((HierarchyHandler.behavior == 'classic' && this.open)?this.openIcon:this.icon) + "\" onclick=\"HierarchyHandler.select(this);\" width=\"16\" height=\"16\">" +
		"<a href=\"" + this.action + "\" id=\"" + this.id + "-anchor\" onfocus=\"HierarchyHandler.focus(this);\" onblur=\"HierarchyHandler.blur(this);\" onclick=\"HierarchyHandler.itemWasClicked(this);\"" +
		(this.target ? " target=\"" + this.target + "\"" : "") +
		">" + label + "</a></span></div>" +
		"<div id=\"" + this.id + "-cont\" class=\"HierarchyContainer\" style=\"display: " + ((this.open)?'block':'none') + ";\">";
	var sb = [];
	for (var i = 0; i < this.childNodes.length; i++) {
		sb[i] = this.childNodes[i].toString(i,this.childNodes.length);
	}
	this.plusIcon = ((this.parentNode._last)?HierarchyConfig.lPlusIcon:HierarchyConfig.tPlusIcon);
	this.minusIcon = ((this.parentNode._last)?HierarchyConfig.lMinusIcon:HierarchyConfig.tMinusIcon);
	return str + sb.join("") + "</div>";
}