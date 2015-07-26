#! /usr/bin/env python

import os
import re
import codecs
import sys

pathname = os.path.dirname(sys.argv[0])
base = os.path.abspath(pathname)+'/..'
    
def joinStyle(files,more='') :
    destination = codecs.open(base+'/bin/joined'+more+'.css', mode='w')
    dev = codecs.open(base+'/bin/development'+more+'.css', mode='w')


    for name in files :
        path = base + '/css/' + name + '.css'
        file = open(path,'rb')
        dev.write('@import url(../css/' + name + '.css);\n')
        destination.write(file.read())
        destination.write('\n\n')
        file.close();
    
    destination.close();
    dev.close();

def joinScript(files,more='') :

    destination = codecs.open(base+'/bin/joined'+more+'.js', mode='w')
    dev = codecs.open(base+'/bin/development'+more+'.js', mode='w')

    #destination.write('"use strict";\n\n')
    for name in files :
        path = base + '/' + name
        file = open(path,'rb')
        dev.write('document.write(\'<script type="text/javascript" src="\'+_context+\'/'+name+'"></script>\');\n')
        destination.write(file.read())
        destination.write('\n\n')
        file.close();
    
    destination.close();
    dev.close();


jsFiles = [
    'js/hui.js',
    'js/hui_animation.js',
    'js/hui_color.js',
    'js/hui_require.js',
    'js/hui_parallax.js',
    'js/hui_store.js',
    'js/hui_xml.js',
    'lib/swfupload/swfupload.js',
    'lib/date.js',
    'js/ui.js',
    'js/Component.js',
    'js/Source.js',
    'js/DragDrop.js',
    'js/Window.js',
    'js/Formula.js',
    'js/List.js',
    'js/Tabs.js',
    'js/ObjectList.js',
    'js/DropDown.js',
    'js/Alert.js',
    'js/Button.js',
    'js/Selection.js',
    'js/Toolbar.js',
    'js/ImageInput.js',
    'js/BoundPanel.js',
    'js/ImageViewer.js',
    'js/Picker.js',
    'js/Menu.js',
    'js/Overlay.js',
    'js/Upload.js',
    'js/ProgressBar.js',
    'js/Gallery.js',
    'js/Calendar.js',
    'js/DatePicker.js',
    'js/Layout.js',
    'js/Dock.js',
    'js/Box.js',
    'js/Wizard.js',
    'js/Input.js',
    'js/InfoView.js',
    'js/Overflow.js',
    'js/SearchField.js',
    'js/Fragment.js',
    'js/LocationPicker.js',
    'js/Bar.js',
    'js/IFrame.js',
    'js/VideoPlayer.js',
    'js/Segmented.js',
    'js/Flash.js',
    'js/Link.js',
    'js/Links.js',
    'js/MarkupEditor.js',
    'js/ColorPicker.js',
    'js/LocationField.js',
    'js/StyleLength.js',
    'js/DateTimeField.js',
    'js/TokenField.js',
    'js/Checkbox.js',
    'js/Checkboxes.js',
    'js/Radiobuttons.js',
    'js/NumberField.js',
    'js/TextField.js',
    'js/Rendering.js',
    'js/Icon.js',
    'js/ColorInput.js',
    'js/Columns.js',
    'js/Finder.js',
    'js/Structure.js',
    'js/Slider.js',
    'js/CodeInput.js',
    'js/LinkInput.js',
    'js/FontInput.js',
    'js/FontPicker.js',
    'js/Split.js',
    'js/NumberValidator.js',
    'js/ObjectInput.js',
    'js/Rows.js'
]

jsFilesSite = [
    'js/hui.js',
    'js/hui_animation.js',
    'js/hui_color.js',
    'js/hui_require.js',
    'js/hui_parallax.js',
    'js/ui.js',
    'js/ImageViewer.js',
    'js/Box.js',
    'js/SearchField.js',
    'js/Overlay.js',
    'js/Button.js'
]

cssFiles = [
    'body',
    'dragproxy',
    'link',
    'text',
    'bg',
    'curtain',
    'tooltip',
    'disclosure',
    'icon',
    'button',
    'tabbox',
    'formula',
    'dropdown',
    'layout',
    'overflow',
    'alert',
    'view',
    'toolbar',
    'window',
    'list',
    'selection',
    'imageinput',
    'boundpanel',
    'panel',
    'imageviewer',
    'picker',
    'editor',
    'menu',
    'overlay',
    'upload',
    'progressbar',
    'gallery',
    'calendar',
    'datepicker',
    'box',
    'wizard',
    'searchfield',
    'dock',
    'tabs',
    'bar',
    'videoplayer',
    'message',
    'segmented',
    'links',
    'effects',
    'colorpicker',
    'locationfield',
    'tokenfield',
    'checkbox',
    'checkboxes',
    'infoview',
    'radiobutton',
    'numberfield',
    'rendering',
    'colorinput',
    'structure',
    'slider',
    'codeinput',
    'linkinput',
    'fontinput',
    'fontpicker',
    'split',
    'columns',
    'markupeditor',
    'objectinput',
    'rows'
]

cssFilesSite = [
    'icon', 'curtain', 'imageviewer', 'editor', 'overlay', 'box', 'button', 'formula', 'message', 'searchfield', 'checkbox', 'checkboxes'
]

print('Joining JavaScript')
joinScript(jsFiles)
joinScript(jsFilesSite,'.site')

print('Joining CSS')
joinStyle(cssFiles)
joinStyle(cssFilesSite,'.site')

print('Joining all JavaScript')
allFiles = [];
for file in jsFiles :
    allFiles.append(file)
    
files = os.listdir(base+'/js')

for file in files :
    if os.path.splitext(file)[1] == '.js' :
        path = 'js/' + file
        if not path in allFiles :
            allFiles.append(path)
            print('- '+path)
    
files = os.listdir(base+'/ext')

for file in files :
    if os.path.splitext(file)[1] == '.js' :
        path = 'ext/' + file
        if not path in allFiles :
            allFiles.append(path)
            print('- '+path)


destination = codecs.open(base+'/bin/all.js', mode='w')

for name in allFiles :
    path = base + '/' + name
    file = open(path,'rb')
    destination.write(file.read())
    destination.write('\n\n')
    file.close();

destination.close();