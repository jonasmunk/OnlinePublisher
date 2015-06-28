var WysiHat = {};

WysiHat.Editor = {
  /**
   *  WysiHat.Editor.attach(textarea)
   *  - textarea (String | Element): an id or DOM node of the textarea that
   *  you want to convert to rich text.
   *
   *  Creates a new editor for the textarea.
   **/
  attach: function(textarea) {
    textarea = $(textarea);
    textarea.hide();

    return WysiHat.iFrame.create(textarea, function(editArea) {
      var document = editArea.getDocument();
      var window = editArea.getWindow();

      editArea.load();

      Event.observe(window, 'focus', function(event) { editArea.focus(); });
      Event.observe(window, 'blur', function(event) { editArea.blur(); });


      Event.observe(document, 'mouseup', function(event) {
        editArea.fire("wysihat:mouseup");
      });

      Event.observe(document, 'mousemove', function(event) {
        editArea.fire("wysihat:mousemove");
      });

      Event.observe(document, 'keypress', function(event) {
        editArea.fire("wysihat:change");
        editArea.fire("wysihat:keypress");
      });

      Event.observe(document, 'keyup', function(event) {
        editArea.fire("wysihat:change");
        editArea.fire("wysihat:keyup");
      });

      Event.observe(document, 'keydown', function(event) {
        if (event.keyCode == 86)
          editArea.fire("wysihat:paste");
      });

      Event.observe(window, 'paste', function(event) {
        editArea.fire("wysihat:paste");
      });


      editArea.observe("wysihat:change", function(event) {
        event.target.save();
      });
      //editArea.focus(); HACK!
    });
  }
};

/**
 * mixin WysiHat.Commands
 *
 *  Methods will be mixed into the editor element. Most of these
 *  methods will be used to bind to button clicks or key presses.
 *
 *  var editor = WysiHat.Editor.attach(textarea);
 *  $('bold_button').observe('click', function(event) {
 *    editor.boldSelection();
 *    Event.stop(event);
 *  });
 *
 *  In this example, it is important to stop the click event so you don't
 *  lose your current selection.
 **/
WysiHat.Commands = {
  /**
   * WysiHat.Commands#boldSelection() -> undefined
   *  Bolds the current selection.
   **/
  boldSelection: function() {
    this.execCommand('bold', false, null);
  },

  /**
   * WysiHat.Commands#underlineSelection() -> undefined
   *  Underlines the current selection.
   **/
  underlineSelection: function() {
    this.execCommand('underline', false, null);
  },

  /**
   * WysiHat.Commands#italicSelection() -> undefined
   *  Italicizes the current selection.
   **/
  italicSelection: function() {
    this.execCommand('italic', false, null);
  },

  /**
   * WysiHat.Commands#italicSelection() -> undefined
   *  Strikethroughs the current selection.
   **/
  strikethroughSelection: function() {
    this.execCommand('strikethrough', false, null);
  },

  /**
   * WysiHat.Commands#italicSelection() -> undefined
   *  Blockquotes the current selection.
   **/
  blockquoteSelection: function() {
    this.execCommand('blockquote', false, null);
  },

  /**
   * WysiHat.Commands#colorSelection(color) -> undefined
   *  - color (String): a color name or hexadecimal value
   *  Sets the foreground color of the current selection.
   **/
  colorSelection: function(color) {
    this.execCommand('forecolor', false, color);
  },

  /**
   * WysiHat.Commands#linkSelection(url) -> undefined
   *  - url (String): value for href
   *  Wraps the current selection in a link.
   **/
  linkSelection: function(url) {
    this.execCommand('createLink', false, url);
  },

  /**
   * WysiHat.Commands#insertOrderedList() -> undefined
   *  Formats current selection as an ordered list. If the selection is empty
   *  a new list is inserted.
   **/
  insertOrderedList: function() {
    this.execCommand('insertorderedlist', false, null);
  },

  /**
   * WysiHat.Commands#insertUnorderedList() -> undefined
   *  Formats current selection as an unordered list. If the selection is empty
   *  a new list is inserted.
   **/
  insertUnorderedList: function() {
    this.execCommand('insertunorderedlist', false, null);
  },

  /**
   * WysiHat.Commands#insertImage(url) -> undefined
   *  - url (String): value for src
   *  Insert an image at the insertion point with the given url.
   **/
  insertImage: function(url) {
    this.execCommand('insertImage', false, url);
  },

  /**
   * WysiHat.Commands#insertHTML(html) -> undefined
   *  - html (String): HTML or plain text
   *  Insert HTML at the insertion point.
   **/
  insertHTML: function(html) {
    if (Prototype.Browser.IE) {
      var range = this._selection.getRange();
      range.pasteHTML(html);
      range.collapse(false);
      range.select();
    } else {
      this.execCommand('insertHTML', false, html);
    }
  },

  /**
   * WysiHat.Commands#execCommand(command[, ui = false][, value = null]) -> undefined
   *  - command (String): Command to execute
   *  - ui (Boolean): Boolean flag for showing UI. Currenty this not
   *  implemented by any browser. Just use false.
   *  - value (String): Value to pass to command
   *  A simple delegation method to the documents execCommand method.
   **/
  execCommand: function(command, ui, value) {
    var document = this.getDocument();
    document.execCommand(command, ui, value);
  },

  queryStateCommands: $A(['bold', 'italic', 'underline', 'strikethrough']),

  /**
   * WysiHat.Commands#queryCommandState(state) -> Boolean
   *  - state (String): bold, italic, underline, etc
   *  Determines whether the current selection has the given state.
   *  queryCommandState('bold') would return true if the selected text
   *  is bold.
   *
   * You can extend this behavior by adding a custom method to the editor
   * element, queryCustom(). However this API is not final.
   * queryCommandState('link') would call queryLink().
   **/
  queryCommandState: function(state) {
    var document = this.getDocument();

    if (this.queryStateCommands.include(state))
      return document.queryCommandState(state);
    else if ((f = this['query' + state.capitalize()]))
      return f.bind(this).call();
    else
      return false;
  }
};
/**
 * mixin WysiHat.Persistence
 *
 *  Methods will be mixed into the editor element. These methods deal with
 *  extracting and filtering content going in and out of the editor.
 */
WysiHat.Persistence = (function() {
  /**
   * WysiHat.Persistence#outputFilter(text) -> String
   *  - text (String): HTML string
   *
   *  Use to filter content coming out of the editor. By default it calls
   *  text.format_html_output. This method has been extract so you can override
   *  it and provide your own custom output filter.
   */
  function outputFilter(text) {
    return text.format_html_output();
  }

  /**
   * WysiHat.Persistence#inputFilter(text) -> String
   *  - text (String): HTML string
   *
   *  Use to filter content going into the editor. By default it calls
   *  text.format_html_input. This method has been extract so you can override
   *  it and provide your own custom input filter.
   */
  function inputFilter(text) {
    return text.format_html_input();
  }

  /**
   * WysiHat.Persistence#content() -> String
   *  Returns the editors HTML contents. The contents are first passed
   *  through outputFilter.
   *
   *  You can replace the generic outputFilter with your own function. The
   *  default behavior is to use String#format_html_output.
   *
   *  editor.outputFilter = function(text) {
   *    return MyUtils.format_and_santize(text);
   *  };
   **/
  function content() {
    return this.outputFilter(this.rawContent());
  }

  /**
   * WysiHat.Persistence#setContent(text) -> undefined
   *  - text (String): HTML string
   *
   *  Replaces editor's entire contents with the given HTML. The contents are
   *  first passed through inputFilter.
   *
   *  You can replace the generic inputFilter with your own function. The
   *  default behavior is to use String#format_html_input.
   *
   *  editor.inputFilter = function(text) {
   *    return MyUtils.format_and_santize(text);
   *  };
   **/
  function setContent(text) {
    this.setRawContent(this.inputFilter(text));
  }

  /**
   * WysiHat.Persistence#save() -> undefined
   *  Saves editors contents back out to the textarea.
   **/
  function save() {
    this.textarea.value = this.content();
  }

  /**
   * WysiHat.Persistence#load() -> undefined
   *  Loads textarea contents into editor.
   **/
   function load() {
     this.setContent(this.textarea.value);
  }

  /**
   * WysiHat.Persistence#reload() -> undefined
   *  Saves current contents and loads contents into editor.
   **/
  function reload() {
    this.selection.setBookmark();
    this.save();
    this.load();
    this.selection.moveToBookmark();
  }

  return {
    outputFilter: outputFilter,
    inputFilter:  inputFilter,
    content:      content,
    setContent:   setContent,
    save:         save,
    load:         load,
    reload:       reload
  };
})();
/**
 * mixin WysiHat.Window
 *
 *  Methods will be mixed into the editor element. These methods handle window
 *  events such as focus and blur events on the editor.
 */
WysiHat.Window = (function() {
  /**
   * WysiHat.Window#getDocument() -> Document
   *  Cross browser method to return the iFrame's document.
   *  You should not need to access this directly, and this API is not final.
   */
  function getDocument() {
    return this.contentDocument || this.contentWindow.document;
  }

  /**
   * WysiHat.Window#getWindow() -> Window
   *  Cross browser method to return the iFrame's window.
   *  You should not need to access this directly, and this API is not final.
   */
  function getWindow() {
    if (this.contentWindow.document)
      return this.contentWindow;
    else if (this.contentDocument)
      return this.contentDocument.defaultView;
    else
      return null;
  }

  /**
   * WysiHat.Window#focus() -> undefined
   *  binds observers to mouseup, mousemove, keypress, and keyup on focus
   **/
  function focus() {
    this.getWindow().focus();

    if (this.hasFocus)
      return;

    this.hasFocus = true;
  }

  /**
   * WysiHat.Window#blur() -> undefined
   *  removes observers to mouseup, mousemove, keypress, and keyup on blur
   **/
  function blur() {
    this.hasFocus = false;
  }

  return {
    getDocument: getDocument,
    getWindow: getWindow,
    focus: focus,
    blur: blur
  };
})();

WysiHat.iFrame = {
  create: function(textarea, callback) {
    var editArea = new Element('iframe', { 'id': textarea.id + '_editor', 'class': 'editor', allowtransparency:'true' });

    Object.extend(editArea, WysiHat.Commands);
    Object.extend(editArea, WysiHat.Persistence);
    Object.extend(editArea, WysiHat.Window);
    Object.extend(editArea, WysiHat.iFrame.Methods);

    editArea.attach(textarea, callback);

    textarea.insert({before: editArea});

    return editArea;
  }
};

WysiHat.iFrame.Methods = {
  attach: function(element, callback) {
    this.textarea = element;

    this.observe('load', function() {
      function setDocumentStyles(document, styles) {
        if (Prototype.Browser.IE) {
          var style = document.createStyleSheet();
          style.addRule("body", "border: 0");
          style.addRule("p", "margin: 0");

          $H(styles).each(function(pair) {
            var value = pair.first().underscore().dasherize() + ": " + pair.last();
            style.addRule("body", value);
          });
        } else if (Prototype.Browser.Opera) {
          var style = Element('style').update("p { margin: 0; }");
          var head = document.getElementsByTagName('head')[0];
          head.appendChild(style);
        } else {
          Element.setStyle(document.body, styles);
        }
      }

      try {
        var document = this.getDocument();
      } catch(e) { return; } // No iframe, just stop

      this.selection = new WysiHat.Selection(this);

      if (this.ready && document.designMode == 'on')
        return;

      setDocumentStyles(document, WysiHat.Editor.styles || {});

      document.designMode = 'on';
      callback(this);
      this.ready = true;
	if (document.body) {
      this.fire("wysihat:loaded"); // HACK
}
    });
  },

  rawContent: function() {
    var document = this.getDocument();
    return document.body.innerHTML;
  },

  setRawContent: function(text) {
    var document = this.getDocument();
    if (document.body)
      document.body.innerHTML = text;
  }
};

Object.extend(String.prototype, (function() {
  /**
   * String#format_html_output() -> String
   *
   *  Cleanup browser's HTML mess!
   *
   *  There is no standard formatting among the major browsers for the rich
   *  text output. Safari wraps its line breaks with "div" tags, Firefox puts
   *  "br" tags at the end of the line, and other such as Internet Explorer
   *  wrap lines in "p" tags.
   *
   *  The output is a standarizes these inconsistencies and produces a clean
   *  result. A single return creates a line break "br" and double returns
   *  create a new paragraph. This is similar to how Textile and Markdown
   *  handle whitespace.
   *
   *  Raw browser content => String#format_html_output => Textarea
   **/
  function format_html_output() {
    var text = String(this);
    text = text.tidy_xhtml();

    if (Prototype.Browser.WebKit) {
      text = text.replace(/(<div>)+/g, "\n");
      text = text.replace(/(<\/div>)+/g, "");

      text = text.replace(/<p>\s*<\/p>/g, "");

      text = text.replace(/<br \/>(\n)*/g, "\n");
    } else if (Prototype.Browser.Gecko) {
      text = text.replace(/<p>/g, "");
      text = text.replace(/<\/p>(\n)?/g, "\n");

      text = text.replace(/<br \/>(\n)*/g, "\n");
    } else if (Prototype.Browser.IE || Prototype.Browser.Opera) {
      text = text.replace(/<p>(&nbsp;|&#160;|\s)<\/p>/g, "<p></p>");

      text = text.replace(/<br \/>/g, "");

      text = text.replace(/<p>/g, '');

      text = text.replace(/&nbsp;/g, '');

      text = text.replace(/<\/p>(\n)?/g, "\n");

      text = text.gsub(/^<p>/, '');
      text = text.gsub(/<\/p>$/, '');
    }

    text = text.gsub(/<b>/, "<strong>");
    text = text.gsub(/<\/b>/, "</strong>");

    text = text.gsub(/<i>/, "<em>");
    text = text.gsub(/<\/i>/, "</em>");

    text = text.replace(/\n\n+/g, "</p>\n\n<p>");

    text = text.gsub(/(([^\n])(\n))(?=([^\n]))/, "#{2}<br />\n");

    text = '<p>' + text + '</p>';

    text = text.replace(/<p>\s*/g, "<p>");
    text = text.replace(/\s*<\/p>/g, "</p>");

    var element = Element("body");
    element.innerHTML = text;

    if (Prototype.Browser.WebKit || Prototype.Browser.Gecko) {
      var replaced;
      do {
        replaced = false;
        element.select('span').each(function(span) {
          if (span.hasClassName('Apple-style-span')) {
            span.removeClassName('Apple-style-span');
            if (span.className == '')
              span.removeAttribute('class');
            replaced = true;
          } else if (span.getStyle('fontWeight') == 'bold') {
            span.setStyle({fontWeight: ''});
            if (span.style.length == 0)
              span.removeAttribute('style');
            span.update('<strong>' + span.innerHTML + '</strong>');
            replaced = true;
          } else if (span.getStyle('fontStyle') == 'italic') {
            span.setStyle({fontStyle: ''});
            if (span.style.length == 0)
              span.removeAttribute('style');
            span.update('<em>' + span.innerHTML + '</em>');
            replaced = true;
          } else if (span.getStyle('textDecoration') == 'underline') {
            span.setStyle({textDecoration: ''});
            if (span.style.length == 0)
              span.removeAttribute('style');
            span.update('<u>' + span.innerHTML + '</u>');
            replaced = true;
          } else if (span.attributes.length == 0) {
            span.replace(span.innerHTML);
            replaced = true;
          }
        });
      } while (replaced);

    }

    for (var i = 0; i < element.descendants().length; i++) {
      var node = element.descendants()[i];
      if (node.innerHTML.blank() && node.nodeName != 'BR' && node.id != 'bookmark')
        node.remove();
    }

    text = element.innerHTML;
    text = text.tidy_xhtml();

    text = text.replace(/<br \/>(\n)*/g, "<br />\n");
    text = text.replace(/<\/p>\n<p>/g, "</p>\n\n<p>");

    text = text.replace(/<p>\s*<\/p>/g, "");

    text = text.replace(/\s*$/g, "");

    return text;
  }

  /**
   * String#format_html_input() -> String
   *
   *  Prepares sane HTML for editing.
   *
   *  This function preforms the reserve function of String#format_html_output. Each
   *  browser has difficulty editing mix formatting conventions. This restores
   *  most of the original browser specific formatting tags and some other
   *  styling conventions.
   *
   *  Textarea => String#format_html_input => Raw content
  **/
  function format_html_input() {
    var text = String(this);

    var element = Element("body");
    element.innerHTML = text;

    if (Prototype.Browser.Gecko || Prototype.Browser.WebKit) {
      element.select('strong').each(function(element) {
        element.replace('<span style="font-weight: bold;">' + element.innerHTML + '</span>');
      });
      element.select('em').each(function(element) {
        element.replace('<span style="font-style: italic;">' + element.innerHTML + '</span>');
      });
      element.select('u').each(function(element) {
        element.replace('<span style="text-decoration: underline;">' + element.innerHTML + '</span>');
      });
    }

    if (Prototype.Browser.WebKit)
      element.select('span').each(function(span) {
        if (span.getStyle('fontWeight') == 'bold')
          span.addClassName('Apple-style-span');

        if (span.getStyle('fontStyle') == 'italic')
          span.addClassName('Apple-style-span');

        if (span.getStyle('textDecoration') == 'underline')
          span.addClassName('Apple-style-span');
      });

    text = element.innerHTML;
    text = text.tidy_xhtml();

    text = text.replace(/<\/p>(\n)*<p>/g, "\n\n");

    text = text.replace(/(\n)?<br( \/)?>(\n)?/g, "\n");

    text = text.replace(/^<p>/g, '');
    text = text.replace(/<\/p>$/g, '');

    if (Prototype.Browser.Gecko) {
      text = text.replace(/\n/g, "<br>");
      text = text + '<br>';
    } else if (Prototype.Browser.WebKit) {
      text = text.replace(/\n/g, "</div><div>");
      text = '<div>' + text + '</div>';
      text = text.replace(/<div><\/div>/g, "<div><br></div>");
    } else if (Prototype.Browser.IE || Prototype.Browser.Opera) {
      text = text.replace(/\n/g, "</p>\n<p>");
      text = '<p>' + text + '</p>';
      text = text.replace(/<p><\/p>/g, "<p>&nbsp;</p>");
      text = text.replace(/(<p>&nbsp;<\/p>)+$/g, "");
    }

    return text;
  }

  /**
   * String#tidy_xhtml() -> String
   *
   *  Normalizes and tidies text into XHTML content.
   *   * Strips out browser line breaks, '\r'
   *   * Downcases tag names
   *   * Closes line break tags
   **/
  function tidy_xhtml() {
    var text = String(this);

    text = text.gsub(/\r\n?/, "\n");

    text = text.gsub(/<([A-Z]+)([^>]*)>/, function(match) {
      return '<' + match[1].toLowerCase() + match[2] + '>';
    });

    text = text.gsub(/<\/([A-Z]+)>/, function(match) {
      return '</' + match[1].toLowerCase() + '>';
    });

    text = text.replace(/<br>/g, "<br />");

    return text;
  }

  return {
    format_html_output: format_html_output,
    format_html_input:  format_html_input,
    tidy_xhtml:         tidy_xhtml
  };
})());
Object.extend(String.prototype, {
  /**
   * String#sanitize([options]) -> String
   * - options (Hash): Whitelist options
   *
   *  Sanitizes HTML tags and attributes. Options accepts an array of
   *  allowed tags and attributes.
   #
   *  "<a href='#'>Example</a>".sanitize({tags: ['a'], attributes: ['href']})
   **/
  sanitize: function(options) {
    return Element("div").update(this).sanitize(options).innerHTML.tidy_xhtml();
  }
});

Element.addMethods({
  /**
   * Element#sanitize([options]) -> Element
   * - options (Hash): Whitelist options
   *
   *  Sanitizes element tags and attributes. Options accepts an array of
   *  allowed tags and attributes.
   #
   *  This method is called by String#sanitize().
   **/
  sanitize: function(element, options) {
    element = $(element);
    options = $H(options);
    var allowed_tags = $A(options.get('tags') || []);
    var allowed_attributes = $A(options.get('attributes') || []);
    var sanitized = Element(element.nodeName);

    $A(element.childNodes).each(function(child) {
      if (child.nodeType == 1) {
        var children = $(child).sanitize(options).childNodes;

        if (allowed_tags.include(child.nodeName.toLowerCase())) {
          var new_child = Element(child.nodeName);
          allowed_attributes.each(function(attribute) {
            if ((value = child.readAttribute(attribute)))
              new_child.writeAttribute(attribute, value);
          });
          sanitized.appendChild(new_child);

          $A(children).each(function(grandchild) { new_child.appendChild(grandchild); });
        } else {
          $A(children).each(function(grandchild) { sanitized.appendChild(grandchild); });
        }
      } else if (child.nodeType == 3) {
        sanitized.appendChild(child);
      }
    });
    return sanitized;
  }
});

/**
 * class Range
 *
 *  *Under construction*
 *
 *  An attempt to implement the W3C Range in IE.
 *
 **/
if (Prototype.Browser.IE) {
  function Range(ownerDocument) {
    this.ownerDocument = ownerDocument;

    this.startContainer = this.ownerDocument.documentElement;
    this.startOffset    = 0;
    this.endContainer   = this.ownerDocument.documentElement;
    this.endOffset      = 0;

    this.collapsed = true;
    this.commonAncestorContainer = null;

    this.START_TO_START = 0;
    this.START_TO_END   = 1;
    this.END_TO_END     = 2;
    this.END_TO_START   = 3;
  }

  document.createRange = function() {
    return new Range(this);
  };

  Object.extend(Range.prototype, {
    setStart: function(parent, offset) {},
    setEnd: function(parent, offset) {},
    setStartBefore: function(node) {},
    setStartAfter: function(node) {},
    setEndBefore: function(node) {},
    setEndAfter: function(node) {},

    collapse: function(toStart) {},

    selectNode: function(n) {},
    selectNodeContents: function(n) {},

    compareBoundaryPoints: function(how, sourceRange) {},

    deleteContents: function() {},
    extractContents: function() {},
    cloneContents: function() {},

    insertNode: function(n) {
      var range = this.ownerDocument.selection.createRange();
      var parent = this.ownerDocument.createElement('div');
      parent.appendChild(n);
      range.collapse();
      range.pasteHTML(parent.innerHTML);
    },
    surroundContents: function(newParent) {
      var range = this.ownerDocument.selection.createRange();
      var parent = this.document.createElement('div');
      parent.appendChild(newParent);
      node.innerHTML += range.htmlText;
      range.pasteHTML(parent.innerHTML);
    },

    cloneRange: function() {},
    toString: function() {},
    detach: function() {}
  });
}
/**
 * class WysiHat.Selection
 **/
WysiHat.Selection = Class.create((function() {
  /**
   *  new WysiHat.Selection(editor)
   *  - editor (WysiHat.Editor): the editor object that you want to bind to
   **/
  function initialize(editor) {
    this.window = editor.getWindow();
    this.document = editor.getDocument();
  }

  /**
   * WysiHat.Selection#getSelection() -> Selection
   *  Get selected text.
   **/
  function getSelection() {
    return this.window.getSelection ? this.window.getSelection() : this.document.selection;
  }

  /**
   * WysiHat.Selection#getRange() -> Range
   *  Get range for selected text.
   **/
  function getRange() {
    var selection = this.getSelection();

    try {
      var range;
      if (selection.getRangeAt)
        range = selection.getRangeAt(0);
      else
        range = selection.createRange();
    } catch(e) { return null; }

    if (Prototype.Browser.WebKit) {
      range.setStart(selection.baseNode, selection.baseOffset);
      range.setEnd(selection.extentNode, selection.extentOffset);
    }

    return range;
  }

  /**
   * WysiHat.Selection#selectNode(node) -> undefined
   * - node (Element): Element or node to select
   **/
  function selectNode(node) {
    var selection = this.getSelection();

    if (Prototype.Browser.IE) {
      var range = createRangeFromElement(this.document, node);
      range.select();
    } else if (Prototype.Browser.WebKit) {
      selection.setBaseAndExtent(node, 0, node, node.innerText.length);
    } else if (Prototype.Browser.Opera) {
      range = this.document.createRange();
      range.selectNode(node);
      selection.removeAllRanges();
      selection.addRange(range);
    } else {
      var range = createRangeFromElement(this.document, node);
      selection.removeAllRanges();
      selection.addRange(range);
    }
  }

  /**
   * WysiHat.Selection#getNode() -> Element
   *  Returns selected node.
   **/
  function getNode() {
    var nodes = null, candidates = [], children, el;
    var range = this.getRange();

    if (!range)
      return null;

    var parent;
    if (range.parentElement)
      parent = range.parentElement();
    else
      parent = range.commonAncestorContainer;

    if (parent) {
      while (parent.nodeType != 1) parent = parent.parentNode;
      if (parent.nodeName.toLowerCase() != "body") {
        el = parent;
        do {
          el = el.parentNode;
          candidates[candidates.length] = el;
        } while (el.nodeName.toLowerCase() != "body");
      }
      children = parent.all || parent.getElementsByTagName("*");
      for (var j = 0; j < children.length; j++)
        candidates[candidates.length] = children[j];
      nodes = [parent];
      for (var ii = 0, r2; ii < candidates.length; ii++) {
        r2 = createRangeFromElement(this.document, candidates[ii]);
        if (r2 && compareRanges(range, r2))
          nodes[nodes.length] = candidates[ii];
      }
    }

    return nodes.first();
  }

  function createRangeFromElement(document, node) {
    if (document.body.createTextRange) {
      var range = document.body.createTextRange();
      range.moveToElementText(node);
    } else if (document.createRange) {
      var range = document.createRange();
      range.selectNodeContents(node);
    }
    return range;
  }

  function compareRanges(r1, r2) {
    if (r1.compareEndPoints) {
      return !(
        r2.compareEndPoints('StartToStart', r1) == 1 &&
        r2.compareEndPoints('EndToEnd', r1) == 1 &&
        r2.compareEndPoints('StartToEnd', r1) == 1 &&
        r2.compareEndPoints('EndToStart', r1) == 1
        ||
        r2.compareEndPoints('StartToStart', r1) == -1 &&
        r2.compareEndPoints('EndToEnd', r1) == -1 &&
        r2.compareEndPoints('StartToEnd', r1) == -1 &&
        r2.compareEndPoints('EndToStart', r1) == -1
      );
    } else if (r1.compareBoundaryPoints) {
      return !(
        r2.compareBoundaryPoints(0, r1) == 1 &&
        r2.compareBoundaryPoints(2, r1) == 1 &&
        r2.compareBoundaryPoints(1, r1) == 1 &&
        r2.compareBoundaryPoints(3, r1) == 1
        ||
        r2.compareBoundaryPoints(0, r1) == -1 &&
        r2.compareBoundaryPoints(2, r1) == -1 &&
        r2.compareBoundaryPoints(1, r1) == -1 &&
        r2.compareBoundaryPoints(3, r1) == -1
      );
    }

    return null;
  };

  function setBookmark() {
    var bookmark = this.document.getElementById('bookmark');
    if (bookmark)
      bookmark.parentNode.removeChild(bookmark);

    bookmark = this.document.createElement('span');
    bookmark.id = 'bookmark';
    bookmark.innerHTML = '&nbsp;';

    var range;
    if (Prototype.Browser.IE)
      range = new Range(this.document);
    else
      range = this.getRange();
    range.insertNode(bookmark);
  }

  function moveToBookmark() {
    var bookmark = this.document.getElementById('bookmark');
    if (!bookmark)
      return;

    if (Prototype.Browser.IE) {
      var range = this.getRange();
      range.moveToElementText(bookmark);
      range.collapse();
      range.select();
    } else if (Prototype.Browser.WebKit) {
      var selection = this.getSelection();
      selection.setBaseAndExtent(bookmark, 0, bookmark, 0);
    } else {
      var range = this.getRange();
      range.setStartBefore(bookmark);
    }

    bookmark.parentNode.removeChild(bookmark);
  }

  return {
    initialize:     initialize,
    getSelection:   getSelection,
    getRange:       getRange,
    getNode:        getNode,
    selectNode:     selectNode,
    setBookmark:    setBookmark,
    moveToBookmark: moveToBookmark
  };
})());

/**
 * class WysiHat.Toolbar
 **/
WysiHat.Toolbar = Class.create((function() {
  /**
   * new WysiHat.Toolbar(editor)
   *  - editor (WysiHat.Editor): the editor object that you want to attach to
   *
   *  Creates a toolbar element above the editor. The WysiHat.Toolbar object
   *  has many helper methods to easily add buttons to the toolbar.
   *
   *  This toolbar class is not required for the Editor object to function.
   *  It is merely a set of helper methods to get you started and to build
   *  on top of.
   **/
  function initialize(editArea) {
    this.editArea = editArea;

    this.hasMouseDown = false;
    this.element = new Element('div', { 'class': 'editor_toolbar' });

    var toolbar = this;
    this.element.observe('mousedown', function(event) { toolbar.mouseDown(event); });
    this.element.observe('mouseup', function(event) { toolbar.mouseUp(event); });

    this.editArea.insert({before: this.element});
  }

  /**
   * WysiHat.Toolbar#addButtonSet(set) -> undefined
   *  - set (Array): The set array contains nested arrays that hold the
   *  button options, and handler.
   *
   *  Adds a button set to the toolbar.
   *
   *  WysiHat.Toolbar.ButtonSets.Basic is a built in button set,
   *  that looks like:
   *  [
   *    [{ name: 'bold', label: "Bold" }, function(editor) {
   *      editor.boldSelection();
   *    }],
   *    [{ name: 'underline', label: "Underline" }, function(editor) {
   *      editor.underlineSelection();
   *    }],
   *    [{ name: 'italic', label: "Italic" }, function(editor) {
   *      editor.italicSelection();
   *    }]
   *  ]
   **/
  function addButtonSet(set) {
    var toolbar = this;
    $A(set).each(function(button) {
      var options = button.first();
      var handler = button.last();
      toolbar.addButton(options, handler);
    });
  }

  /**
   * WysiHat.Toolbar#addButton(options, handler) -> undefined
   *  - options (Hash): Required options hash
   *  - handler (Function): Function to bind to the button
   *
   *  The options hash accepts two required keys, name and label. The label
   *  value is used as the link's inner text. The name value is set to the
   *  link's class and is used to check the button state.
   *
   *  toolbar.addButton({
   *    name: 'bold', label: "Bold" }, function(editor) {
   *      editor.boldSelection();
   *  });
   *
   *  Would create a link,
   *  "<a href='#' class='button bold'><span>Bold</span></a>"
   **/
  function addButton(options, handler) {
    options = $H(options);
    var button = Element('a', { 'class': 'button', 'href': '#' }).update('<span>' + options.get('label') + '</span>');
    button.addClassName(options.get('name'));

    this.observeButtonClick(button, handler);
    this.observeStateChanges(button, options.get('name'));
    this.element.appendChild(button);
  }

  /**
   * WysiHat.Toolbar#observeButtonClick(element, handler) -> undefined
   *  - element (String | Element): Element to bind handler to
   *  - handler (Function): Function to bind to the element
   *  fires wysihat:change
   *
   *  In addition to binding the given handler to the element, this observe
   *  function also sets up a few more events. When the elements onclick is
   *  fired, the toolbars hasMouseDown property will be set to true and
   *  back to false on exit.
   **/
  function observeButtonClick(element, handler) {
    var toolbar = this;
    $(element).observe('click', function(event) {
      toolbar.hasMouseDown = true;
      handler(toolbar.editArea);
      toolbar.editArea.fire("wysihat:change");
      Event.stop(event);
      toolbar.hasMouseDown = false;
    });
  }

  /**
   * WysiHat.Toolbar#observeStateChanges(element, command) -> undefined
   *  - element (String | Element): Element to receive changes
   *  - command (String): Name of editor command to observe
   *
   *  Adds the class "selected" to the given Element when the selected text
   *  matches the command.
   *
   *  toolbar.observeStateChanges(buttonElement, 'bold')
   *  would add the class "selected" to the buttonElement when the editor's
   *  selected text was bold.
   **/
  function observeStateChanges(element, command) {
    this.editArea.observe("wysihat:mousemove", function(event) {
      if (event.target.queryCommandState(command))
        element.addClassName('selected');
      else
        element.removeClassName('selected');
    });
  }

  /**
   * WysiHat.Toolbar#mouseDown(event) -> undefined
   *  - event (Event)
   *  This function is triggered when the user clicks their mouse down on
   *  the toolbar element. For now, it only updates the hasMouseDown property
   *  to true.
   **/
  function mouseDown(event) {
    this.hasMouseDown = true;
  }

  /**
   * WysiHat.Toolbar#mouseDown(event) -> undefined
   *  - event (Event)
   *  This function is triggered when the user releases their mouse from
   *  the toolbar element. It resets the hasMouseDown property back to false
   *  and refocuses on the editing window.
   **/
  function mouseUp(event) {
    this.editArea.focus();
    this.hasMouseDown = false;
  }

  return {
    initialize:          initialize,
    addButtonSet:        addButtonSet,
    addButton:           addButton,
    observeButtonClick:  observeButtonClick,
    observeStateChanges: observeStateChanges,
    mouseDown:           mouseDown,
    mouseUp:             mouseUp
  };
})());

WysiHat.Toolbar.ButtonSets = {};

/**
 * WysiHat.Toolbar.ButtonSets.Basic = $A([
 *    [{ name: 'bold', label: "Bold" }, function(editor) {
 *      editor.boldSelection();
 *    }],
 *
 *    [{ name: 'underline', label: "Underline" }, function(editor) {
 *      editor.underlineSelection();
 *    }],
 *
 *    [{ name: 'italic', label: "Italic" }, function(editor) {
 *      editor.italicSelection();
 *    }]
 *  ])
 *
 *  A basic set of buttons: bold, underline, and italic. This set is
 *  compatible with WysiHat.Toolbar, and can be added to the toolbar with:
 *  toolbar.addButtonSet(WysiHat.Toolbar.ButtonSets.Basic);
 **/
WysiHat.Toolbar.ButtonSets.Basic = $A([
  [{ name: 'bold', label: "Bold" }, function(editor) {
    editor.boldSelection();
  }],

  [{ name: 'underline', label: "Underline" }, function(editor) {
    editor.underlineSelection();
  }],

  [{ name: 'italic', label: "Italic" }, function(editor) {
    editor.italicSelection();
  }]
]);

