/**
* @fileoverview PopUp menu library
* @requires In2iBase.js
*/


// Global constants
in2iMenuImagePath = "images/";
in2iMenuHideTime = 400;
in2iMenuShowTime = 0;

var in2iMenuHandler = {
	idCounter		:	0,
	idPrefix		:	"in2i-menu-object-",
	all				:	{},
	getId			:	function () { return this.idPrefix + this.idCounter++; },
	overMenuItem :
		function (oItem) {
			if (this.showTimeout != null) {
				window.clearTimeout(this.showTimeout);
			}
			if (this.hideTimeout != null) {
				window.clearTimeout(this.hideTimeout);
			}
			var jsItem = this.all[oItem.id];
			if (in2iMenuShowTime <= 0) {
				this._over(jsItem);
			} else {
				//this.showTimeout = window.setTimeout(function () { in2iMenuHandler._over(jsItem) ; }, in2iMenuShowTime);
				// I hate IE5.0 because the piece of shit crashes when using setTimeout with a function object
				this.showTimeout = window.setTimeout("in2iMenuHandler._over(in2iMenuHandler.all['" + jsItem.id + "'])", in2iMenuShowTime);
			}
	},
	outMenuItem :
		function (oItem) {
			if (this.showTimeout != null) {
				window.clearTimeout(this.showTimeout);
			}
			if (this.hideTimeout != null) {
				window.clearTimeout(this.hideTimeout);
			}
			var jsItem = this.all[oItem.id];
			if (in2iMenuHideTime <= 0) {
				this._out(jsItem);
			} else {
				//this.hideTimeout = window.setTimeout(function () { in2iMenuHandler._out(jsItem) ; }, in2iMenuHideTime);
				this.hideTimeout = window.setTimeout("in2iMenuHandler._out(in2iMenuHandler.all['" + jsItem.id + "'])", in2iMenuHideTime);
			}
		},
	blurMenu :
		function (oMenuItem) {
			window.setTimeout("in2iMenuHandler.all[\"" + oMenuItem.id + "\"].subMenu.hide();", in2iMenuHideTime);
		},
	_over :
		function (jsItem) {
			if (jsItem.subMenu) {
				jsItem.parentMenu.hideAllSubs();
				jsItem.subMenu.show();
			} else {
				jsItem.parentMenu.hideAllSubs();
			}
		},
	_out :
		function (jsItem) {
			// find top most menu
			var root = jsItem;
			var m;
//			if (root instanceof In2iMenuButton) {
//				m = root.subMenu;
//			} else {
				m = jsItem.parentMenu;
				while (m.parentMenu != null/* && !(m.parentMenu instanceof In2iMenuBar)*/) {
					m = m.parentMenu;
				}
//			}
			if (m != null) {	
				m.hide();
			}
		},
	hideMenu :
		function (menu) {
			if (this.showTimeout != null) {
				window.clearTimeout(this.showTimeout);
			}
			if (this.hideTimeout != null) {
				window.clearTimeout(this.hideTimeout);
			}
			//this.hideTimeout = window.setTimeout("in2iMenuHandler.all['" + menu.id + "'].hide()", in2iMenuHideTime);
		},
	showMenu :	
		function (menu, src, dir, event, showAtCursor) {
			if (this.showTimeout != null)
				window.clearTimeout(this.showTimeout);
			if (this.hideTimeout != null)
				window.clearTimeout(this.hideTimeout);
			if (!dir)
				dir = "vertical";
			this.hideAllMenus();
			menu.show(src, dir, event, showAtCursor);
		},
	hideAllMenus :
		function () {
			for (prop in this.all) {
				try {
					this.all[prop].hide();
					//alert(this.all[prop]);
				}
				catch (ignore) {
					//alert(ignore);
				};
			}
		}
};




/**
 * Creates a new instance of a menu
 * @class Represents a pop-up menu
 * @contrsuctor
 */
function In2iMenu() {
	this._menuItems	= [];
	this._subMenus	= [];
	this.id			= in2iMenuHandler.getId();
	this.top		= 0;
	this.left		= 0;
	this.shown		= false;
	this.parentMenu	= null;
	in2iMenuHandler.all[this.id] = this;
}

/** @private */
In2iMenu.prototype.width = 100;
/** @private */
In2iMenu.prototype.emptyText = "Empty";
/** @private */
In2iMenu.prototype.useAutoPosition = true;


/**
 * Adds a new menuitem to the menu
 * @param {In2iMenuItem} menuItem The In2iMenuItem to add
 */
In2iMenu.prototype.add = function (menuItem) {
	this._menuItems[this._menuItems.length] = menuItem;
	if (menuItem.subMenu) {
		this._subMenus[this._subMenus.length] = menuItem.subMenu;
		menuItem.subMenu.parentMenu = this;
	}
	
	menuItem.parentMenu = this;
};

/**
 * Counts the number of menuitems of the menu
 * @return {int} The number of menuitems of the menu
 */
In2iMenu.prototype.getItemCount = function () {
	return this._menuItems.length;
}

/**
 * @private
 */
In2iMenu.prototype.show = function (relObj, sDir, event ,showAtCursor) {
	
	// Call to calculate position
	if (showAtCursor) {
		var e = new In2iEvent(event);
		this.left = e.mouseLeft();
		this.top = e.mouseTop();
	}
	else if (this.useAutoPosition) {
		this.position(relObj, sDir);
		/*var e = new In2iEvent(event);
		if (e.mouseLeft()-this.left>100) {
			this.left = e.mouseLeft();
		}
		if (e.mouseTop()-this.top>20) {
			this.top = e.mouseTop();
		}*/
	}
	
	
	// Set top+left and show the menu
	var divElement = document.getElementById(this.id);
	divElement.style.left = isOpera() ? this.left : this.left + "px";
	divElement.style.top = isOpera() ? this.top : this.top + "px";
	divElement.style.visibility = "visible";
	
	// Mark as shown
	this.shown = true;
	
	// Show the parent menu if it has one
	if (this.parentMenu)
		this.parentMenu.show();
};

/**
 * @private
 */
In2iMenu.prototype.hide = function () {
	this.hideAllSubs();
	var divElement = document.getElementById(this.id);
	divElement.style.visibility = "hidden";
	this.shown = false;
};

/**
 * @private
 */
In2iMenu.prototype.hideAllSubs = function () {
	for (var i = 0; i < this._subMenus.length; i++) {
		if (this._subMenus[i].shown)
			this._subMenus[i].hide();
	}
};

/**
 * Generates the HTML for the complete menu
 * @return {String} HTML of the menu as a string
 */
In2iMenu.prototype.toString = function () {
	var top = this.top;
	var str = "<div id='" + this.id + "' class='in2i-menu' style='" + 
		"width:" + this.width + "px;" +
			(this.useAutoPosition ?
			"left:" + this.left + "px;" + "top:" + this.top + "px;" : "") +
	"'>";
	
	if (this._menuItems.length == 0) {
		str +=	"<span class='in2i-menu-empty'>" + this.emptyText + "</span>";
	}
	else {
		//alert(this._menuItems.length);
		// loop through all menuItems
		for (var i = 0; i < this._menuItems.length; i++) {
			var mi = this._menuItems[i];
			str += mi.toString();
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



////////////////////////////////////////////////////////////////////////
////                       In2iMenuItem                             ////
////////////////////////////////////////////////////////////////////////

/**
 * Creates a new instance of a menu item
 * @class Represents an item in a menu
 * @param {String} text The text of the menu item
 * @param {String} href The link of the menu item
 * @param {String} toolTip The tool tip of the menu item
 * @param {In2iMenu} subMenu A sub menu of the menuitem
 */
function In2iMenuItem(text, href, toolTip, subMenu) {
	this.text = text || "Untitled";
	this.href = (href == null || href == "") ? "javascript:void(0)" : href;
	this.subMenu = subMenu;
	if (subMenu)
		subMenu.parentMenuItem = this;
	this.toolTip = toolTip;
	this.id = in2iMenuHandler.getId();
	in2iMenuHandler.all[this.id] = this;
}

/**
 * @private
 */
In2iMenuItem.prototype.height = 18;

/**
 * @private
 */
In2iMenuItem.prototype.toString = function() {
	return "<a id='" + this.id + "'" +
		" href=\"" + this.href + "\"" +
		" onmouseover='in2iMenuHandler.overMenuItem(this)'" +
		(this.toolTip ? " title=\"" + this.toolTip + "\"" : "") +
		(this.subMenu ? " unselectable='on' tabindex='-1'" : "") +
		">" +
		(this.subMenu ? "<img class='arrow' src=\"" + in2iMenuImagePath + "arrow.right.png\">" : "") +
		this.text + "</a>";
} 




/**
 * Creates a new instance of a menu separator
 * @class Represents a separator in a menu
 * @constructor
 */
function In2iMenuSeparator() {
	this.id = in2iMenuHandler.getId();
	in2iMenuHandler.all[this.id] = this;
};

/**
 * @private
 */
In2iMenuSeparator.prototype.height = 6;

/**
 * @private
 */
In2iMenuSeparator.prototype.toString = function () {
	return	"<div id='" + this.id + "' onmouseout='in2iMenuHandler.outMenuItem(this)'></div>";
};


/**
 * @private
 */
In2iMenu.prototype.position = function (relEl, sDir) {
	var dir = sDir;
	// find parent item rectangle, piRect
	var piRect;
	if (!relEl) {
		var pi = this.parentMenuItem;
		if (!this.parentMenuItem)
			return;
		
		relEl = document.getElementById(pi.id);
		if (dir == null)
			dir = /*pi instanceof In2iMenuButton ? "vertical" :*/ "horizontal";
		
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
			this.left = piRect.left + piRect.width + getDisplayStyleInt(menuEl,'padding-top');
		else if (piRect.left - menuRect.width >= 0)
			this.left = piRect.left - menuRect.width;
		else if (docRect.width >= menuRect.width)
			this.left = docRect.width  + scrollPos.left - menuRect.width;
		else
			this.left = scrollPos.left;
	}
};



////////////////////////////////////////////////////////////////////////////////
//                          In2iMenuAttacher                                  //
////////////////////////////////////////////////////////////////////////////////

/**
 * @class Static class with methods to attach menus to elements
 */
function In2iMenuAttacher() {
}

/**
 * Attaches a menu to an element - to be shown when clicked
 * @param {String} objId The id of the element the menu should be attached to
 * @param {In2iMenu} menu The menu to be attached
 * @param {boolean} showAtCursor Sets whether the menu should appear at the cursor
 */
In2iMenuAttacher.attachAsClickMenu = function(objId,menu,showAtCursor) {
    var obj = document.getElementById(objId);
    obj.onclick = In2iMenuAttacher.clickHandler;
    obj.onblur = In2iMenuAttacher.menuHider;
	obj.in2iMenuShowAtCursor = showAtCursor;
    obj.in2iMenu = menu;
}

/**
 * Attaches a menu to an element - to be shown when right clicked
 * @param {String} objId The id of the element the menu should be attached to
 * @param {In2iMenu} menu The menu to be attached
 * @param {boolean} showAtCursor Sets whether the menu should appear at the cursor
 */
In2iMenuAttacher.attachAsContextMenu = function(objId,menu,showAtCursor) {
    var obj = document.getElementById(objId);
    obj.oncontextmenu = In2iMenuAttacher.contextHandler;
    obj.onblur=In2iMenuAttacher.menuHider;
	obj.in2iMenuShowAtCursor = showAtCursor;
    obj.in2iMenu = menu;
}

/**
 * @private
 */
In2iMenuAttacher.contextHandler = function(event) {
    in2iMenuHandler.showMenu(this.in2iMenu, this, 'vertical', event,this.in2iMenuShowAtCursor);
    return false;
}

/**
 * @private
 */
In2iMenuAttacher.clickHandler = function(event) {
    in2iMenuHandler.showMenu(this.in2iMenu, this, 'vertical', event,this.in2iMenuShowAtCursor);
	stopEventPropagation(event);
    return false;
}

/**
 * @private
 */
In2iMenuAttacher.menuHider = function() {
    in2iMenuHandler.hideMenu(this.in2iMenu);
}

// Hide all windows on document click
document.onclick = function() {in2iMenuHandler.hideAllMenus()};