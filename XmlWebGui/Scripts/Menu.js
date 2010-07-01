
// check browsers
var ua = navigator.userAgent;
var opera = /opera [56789]|opera\/[56789]/i.test(ua);
var ie = !opera && /MSIE/.test(ua);
var ie50 = ie && /MSIE 5\.[01234]/.test(ua);
var ie6 = ie && /MSIE [6789]/.test(ua);
var ieBox = ie && (document.compatMode == null || document.compatMode != "CSS1Compat");
var moz = !opera && /gecko/i.test(ua);
var nn6 = !opera && /netscape.*6\./i.test(ua);

// define the default values
menuImagePath				= xwgPath;
menuDefaultWidth			= 100;

menuItemDefaultHeight		= 18;
menuItemDefaultText		= "Untitled";
menuItemDefaultHref		= "javascript:void(0)";

menuSeparatorDefaultHeight	= 6;

menuDefaultEmptyText		= "Empty";
menuDefaultUseAutoPosition		= nn6 ? false : true;

menuUseHover			= opera ? true : false;
menuHideTime			= 300;
menuShowTime			= 0;

var MenuHandler = {
	idCounter		:	0,
	idPrefix		:	"webfx-menu-object-",
	all				:	{},
	getId			:	function () { return this.idPrefix + this.idCounter++; },
	overMenuItem	:	function (oItem) {
		if (this.showTimeout != null)
			window.clearTimeout(this.showTimeout);
		if (this.hideTimeout != null)
			window.clearTimeout(this.hideTimeout);
		var jsItem = this.all[oItem.id];
		if (menuShowTime <= 0)
			this._over(jsItem);
		else
			//this.showTimeout = window.setTimeout(function () { MenuHandler._over(jsItem) ; }, menuShowTime);
			// I hate IE5.0 because the piece of shit crashes when using setTimeout with a function object
			this.showTimeout = window.setTimeout("MenuHandler._over(MenuHandler.all['" + jsItem.id + "'])", menuShowTime);
	},
	outMenuItem	:	function (oItem) {
		if (this.showTimeout != null)
			window.clearTimeout(this.showTimeout);
		if (this.hideTimeout != null)
			window.clearTimeout(this.hideTimeout);
		var jsItem = this.all[oItem.id];
		if (menuHideTime <= 0)
			this._out(jsItem);
		else
			//this.hideTimeout = window.setTimeout(function () { MenuHandler._out(jsItem) ; }, menuHideTime);
			this.hideTimeout = window.setTimeout("MenuHandler._out(MenuHandler.all['" + jsItem.id + "'])", menuHideTime);
	},
	blurMenu		:	function (oMenuItem) {
		window.setTimeout("MenuHandler.all[\"" + oMenuItem.id + "\"].subMenu.hide();", menuHideTime);
	},
	_over	:	function (jsItem) {
		if (jsItem.subMenu) {
			jsItem.parentMenu.hideAllSubs();
			jsItem.subMenu.show();
		}
		else
			jsItem.parentMenu.hideAllSubs();
	},
	_out	:	function (jsItem) {
		// find top most menu
		var root = jsItem;
		var m;
		m = jsItem.parentMenu;
		while (m.parentMenu != null)
			m = m.parentMenu;
		if (m != null)	
			m.hide();	
	},
	hideMenu	:	function (menu) {
		if (this.showTimeout != null)
			window.clearTimeout(this.showTimeout);
		if (this.hideTimeout != null)
			window.clearTimeout(this.hideTimeout);
		this.hideTimeout = window.setTimeout("MenuHandler.all['" + menu.id + "'].hide()", menuHideTime);
	},
	showMenu	:	function (menu, src, dir) {
		if (this.showTimeout != null)
			window.clearTimeout(this.showTimeout);
		if (this.hideTimeout != null)
			window.clearTimeout(this.hideTimeout);
		if (arguments.length < 3)
			dir = "vertical";
		
		menu.show(src, dir);
	}
};

function Menu() {
	this._menuItems	= [];
	this._subMenus	= [];
	this.id			= MenuHandler.getId();
	this.top		= 0;
	this.left		= 0;
	this.shown		= false;
	this.parentMenu	= null;
	MenuHandler.all[this.id] = this;
}
Menu.prototype.width			= menuDefaultWidth;
Menu.prototype.emptyText		= menuDefaultEmptyText;
Menu.prototype.useAutoPosition	= menuDefaultUseAutoPosition;


Menu.prototype.add = function (menuItem) {
	this._menuItems[this._menuItems.length] = menuItem;
	if (menuItem.subMenu) {
		this._subMenus[this._subMenus.length] = menuItem.subMenu;
		menuItem.subMenu.parentMenu = this;
	}
	
	menuItem.parentMenu = this;
};

Menu.prototype.show = function (relObj, sDir) {
	if (this.useAutoPosition)
		this.position(relObj, sDir);
	
	var divElement = document.getElementById(this.id);
	divElement.style.left = opera ? this.left : this.left + "px";
	divElement.style.top = opera ? this.top : this.top + "px";
	divElement.style.visibility = "visible";
	this.shown = true;
	if (this.parentMenu)
		this.parentMenu.show();
};

Menu.prototype.hide = function () {
	this.hideAllSubs();
	var divElement = document.getElementById(this.id);
	divElement.style.visibility = "hidden";
	this.shown = false;
};

Menu.prototype.hideAllSubs = function () {
	for (var i = 0; i < this._subMenus.length; i++) {
		if (this._subMenus[i].shown)
			this._subMenus[i].hide();
	}
};

Menu.prototype.toString = function () {
	var top = this.top;
	var str = "<div id='" + this.id + "' class='Menu' style='" + 
	"width:" + (!ieBox  ?
		this.width  : 
		this.width) + "px;" +
	(this.useAutoPosition ?
		"left:" + this.left + "px;" + "top:" + this.top + "px;" :
		"") +
	(ie50 ? "filter: none;" : "") +
	"'>";
	
	if (this._menuItems.length == 0) {
		str +=	"<span class='Menu-empty'>" + this.emptyText + "</span>";
	}
	else {	
		// loop through all menuItems
		for (var i = 0; i < this._menuItems.length; i++) {
			var mi = this._menuItems[i];
			str += mi;
			if (!this.useAutoPosition) {
				if (mi.subMenu && !mi.subMenu.useAutoPosition)
					mi.subMenu.top = top;
				top += mi.height;
			}
		}

	}
	
	str += "</div>";

	for (var i = 0; i < this._subMenus.length; i++) {
		this._subMenus[i].left = this.left + this.width;
		str += this._subMenus[i];
	}
	
	return str;
};

// Menu.prototype.position defined later
function MenuItem(sText, sHref, sTarget, sToolTip, oSubMenu) {
	this.text = sText || menuItemDefaultText;
	this.target = sTarget;
	this.href = (sHref == null || sHref == "") ? menuItemDefaultHref : sHref;
	this.subMenu = oSubMenu;
	if (oSubMenu)
		oSubMenu.parentMenuItem = this;
	this.toolTip = sToolTip;
	this.id = MenuHandler.getId();
	MenuHandler.all[this.id] = this;
};

MenuItem.prototype.height = menuItemDefaultHeight;

MenuItem.prototype.toString = function () {
	return	"<a" +
	" id='" + this.id + "'" +
	" href=\"" + this.href + "\"" +
	(this.target ? " target=\"" + this.target + "\"" : "") +
	(this.toolTip ? " title=\"" + this.toolTip + "\"" : "") +
	(ie ? " style=\"width: 100%;\"" : "") +
	" onmouseover='MenuHandler.overMenuItem(this)'" +
	(menuUseHover ? " onmouseout='MenuHandler.outMenuItem(this)'" : "") +
	(this.subMenu ? " unselectable='on' tabindex='-1'" : "") +
	">" +
	(this.subMenu ? "<img class='arrow' src=\"" + menuImagePath + "arrow.right.png\">" : "") +
	this.text + 
	"</a>";
};


function MenuSeparator() {
	this.id = MenuHandler.getId();
	MenuHandler.all[this.id] = this;
};

MenuSeparator.prototype.height = menuSeparatorDefaultHeight;

MenuSeparator.prototype.toString = function () {
	return	"<div" +
	" id='" + this.id + "'" +
	(menuUseHover ? 
	" onmouseover='MenuHandler.overMenuItem(this)'" +
	" onmouseout='MenuHandler.outMenuItem(this)'"
        :
	"") +
	"></div>"
};

/* Position functions */

function getInnerLeft(el) {
	if (el == null) return 0;
	if (ieBox && el == document.body || !ieBox && el == document.documentElement) return 0;
	return getLeft(el);
}

function getLeft(el) {
	if (el == null) return 0;
	return el.offsetLeft + getInnerLeft(el.offsetParent);
}

function getInnerTop(el) {
	if (el == null) return 0;
	if (ieBox && el == document.body || !ieBox && el == document.documentElement) return 0;
	return getTop(el);
}

function getTop(el) {
	if (el == null) return 0;
	return el.offsetTop + getInnerTop(el.offsetParent);
}

function opera_getLeft(el) {
	if (el == null) return 0;
	return el.offsetLeft + opera_getLeft(el.offsetParent);
}

function opera_getTop(el) {
	if (el == null) return 0;
	return el.offsetTop + opera_getTop(el.offsetParent);
}

function getOuterRect(el) {
	return {
		left:	(opera ? opera_getLeft(el) : getLeft(el)),
		top:	(opera ? opera_getTop(el) : getTop(el)),
		width:	el.offsetWidth,
		height:	el.offsetHeight
	};
}

// mozilla bug! scrollbars not included in innerWidth/height
function getDocumentRect(el) {
	return {
		left:	0,
		top:	0,
		width:	(ie ?
			(ieBox ? document.body.clientWidth : document.documentElement.clientWidth) :
			window.innerWidth),
		height:	(ie ?
			(ieBox ? document.body.clientHeight : document.documentElement.clientHeight) :
			window.innerHeight)
	};
}

function getScrollPos(el) {
	return {
		left:	(ie ?
					(ieBox ? document.body.scrollLeft : document.documentElement.scrollLeft) :
					window.pageXOffset
				),
		top:	(ie ?
					(ieBox ? document.body.scrollTop : document.documentElement.scrollTop) :
					window.pageYOffset
				)
	};
}

/* end position functions */

Menu.prototype.position = function (relEl, sDir) {
	var dir = sDir;
	// find parent item rectangle, piRect
	var piRect;
	if (!relEl) {
		var pi = this.parentMenuItem;
		if (!this.parentMenuItem)
			return;
		
		relEl = document.getElementById(pi.id);
		if (dir == null)
			dir = "horizontal";
		
		piRect = getOuterRect(relEl);
	}
	else if (relEl.left != null && relEl.top != null && relEl.width != null && relEl.height != null) {	// got a rect
		piRect = relEl;
	}
	else
		piRect = getOuterRect(relEl);
	
	var menuEl = document.getElementById(this.id);
	var menuRect = getOuterRect(menuEl);
	var docRect = getDocumentRect();
	var scrollPos = getScrollPos();
	var pMenu = this.parentMenu;
	
	if (dir == "vertical") {
		if (piRect.left + menuRect.width - scrollPos.left <= docRect.width)
			this.left = piRect.left;
		else if (docRect.width >= menuRect.width)
			this.left = docRect.width + scrollPos.left - menuRect.width;
		else
			this.left = scrollPos.left;
			
		if (piRect.top + piRect.height + menuRect.height <= docRect.height + scrollPos.top)
			this.top = piRect.top + piRect.height;
		else if (piRect.top - menuRect.height >= scrollPos.top)
			this.top = piRect.top - menuRect.height;
		else if (docRect.height >= menuRect.height)
			this.top = docRect.height + scrollPos.top - menuRect.height;
		else
			this.top = scrollPos.top;
	}
	else {
		if (piRect.top + menuRect.height <= docRect.height + scrollPos.top)
			this.top = piRect.top;
		else if (piRect.top + piRect.height - menuRect.height >= 0)
			this.top = piRect.top + piRect.height - menuRect.height;
		else if (docRect.height >= menuRect.height)
			this.top = docRect.height + scrollPos.top - menuRect.height;
		else
			this.top = scrollPos.top;
		
		if (piRect.left + piRect.width + menuRect.width <= docRect.width + scrollPos.left)
			this.left = piRect.left + piRect.width;
		else if (piRect.left - menuRect.width >= 0)
			this.left = piRect.left - menuRect.width;
		else if (docRect.width >= menuRect.width)
			this.left = docRect.width  + scrollPos.left - menuRect.width;
		else
			this.left = scrollPos.left;
	}
};