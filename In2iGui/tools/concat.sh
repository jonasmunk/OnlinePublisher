#!/bin/bash

DIR=$(dirname $0)
CSS_PATH=${DIR}"/../css/"
JS_LIB_PATH=${DIR}"/../lib/"
JS_PATH=${DIR}"/../js/"
BIN_PATH=${DIR}"/../bin/"

echo "Concatenating CSS"
cat ${CSS_PATH}master.css ${CSS_PATH}button.css ${CSS_PATH}common.css ${CSS_PATH}tabbox.css ${CSS_PATH}formula.css ${CSS_PATH}dropdown.css ${CSS_PATH}layout.css ${CSS_PATH}alert.css ${CSS_PATH}view.css ${CSS_PATH}toolbar.css ${CSS_PATH}window.css ${CSS_PATH}list.css ${CSS_PATH}selection.css ${CSS_PATH}imagepicker.css ${CSS_PATH}boundpanel.css ${CSS_PATH}panel.css ${CSS_PATH}richtext.css ${CSS_PATH}imageviewer.css ${CSS_PATH}picker.css ${CSS_PATH}editor.css ${CSS_PATH}menu.css ${CSS_PATH}overlay.css ${CSS_PATH}upload.css ${CSS_PATH}progressbar.css ${CSS_PATH}gallery.css ${CSS_PATH}calendar.css ${CSS_PATH}box.css ${CSS_PATH}articles.css ${CSS_PATH}wizard.css ${CSS_PATH}searchfield.css ${CSS_PATH}dock.css ${CSS_PATH}tabs.css ${CSS_PATH}bar.css ${CSS_PATH}videoplayer.css ${CSS_PATH}message.css ${CSS_PATH}segmented.css ${CSS_PATH}links.css ${CSS_PATH}effects.css ${CSS_PATH}colorpicker.css > ${BIN_PATH}combined.css

echo "Concatenating site CSS"
cat ${CSS_PATH}imageviewer.css ${CSS_PATH}editor.css ${CSS_PATH}overlay.css ${CSS_PATH}box.css ${CSS_PATH}searchfield.css > ${BIN_PATH}combined.site.css

echo "Concatenating scripts (no prototype)"
# ${JS_LIB_PATH}swfobject.js
# ${JS_LIB_PATH}json2.js
cat  ${JS_LIB_PATH}n2i.js ${JS_LIB_PATH}swfupload/swfupload.js ${JS_LIB_PATH}json2.js ${JS_LIB_PATH}In2iScripts/In2iDate.js ${JS_PATH}In2iGui.js ${JS_PATH}Source.js ${JS_PATH}DragDrop.js ${JS_PATH}Window.js ${JS_PATH}Formula.js ${JS_PATH}List.js ${JS_PATH}Tabs.js ${JS_PATH}ObjectList.js ${JS_PATH}Alert.js ${JS_PATH}Button.js ${JS_PATH}Selection.js ${JS_PATH}Toolbar.js ${JS_PATH}ImagePicker.js ${JS_PATH}BoundPanel.js ${JS_PATH}RichText.js ${JS_PATH}ImageViewer.js ${JS_PATH}Picker.js ${JS_PATH}Editor.js ${JS_PATH}Menu.js ${JS_PATH}Overlay.js ${JS_PATH}Upload.js ${JS_PATH}ProgressBar.js ${JS_PATH}Gallery.js ${JS_PATH}Calendar.js ${JS_PATH}Layout.js ${JS_PATH}Dock.js ${JS_PATH}Box.js ${JS_PATH}Wizard.js ${JS_PATH}Articles.js ${JS_PATH}TextField.js ${JS_PATH}InfoView.js ${JS_PATH}Overflow.js ${JS_PATH}SearchField.js ${JS_PATH}Fragment.js ${JS_PATH}LocationPicker.js ${JS_PATH}Bar.js ${JS_PATH}IFrame.js ${JS_PATH}VideoPlayer.js ${JS_PATH}Segmented.js ${JS_PATH}Flash.js ${JS_PATH}Link.js ${JS_PATH}Links.js ${JS_PATH}MarkupEditor.js ${JS_PATH}ColorPicker.js > ${BIN_PATH}combined.noproto.js

echo "Concatenating scripts"
cat  ${JS_LIB_PATH}prototype.js ${BIN_PATH}combined.noproto.js ${JS_LIB_PATH}wysihat.js > ${BIN_PATH}combined.js

echo "Concatenating basic scripts"
cat ${JS_LIB_PATH}prototype.js ${JS_LIB_PATH}n2i.js ${JS_PATH}In2iGui.js > ${BIN_PATH}combined.basic.js

echo "Concatenating site scripts"
cat ${JS_LIB_PATH}prototype.js ${JS_LIB_PATH}n2i.js ${JS_PATH}In2iGui.js ${JS_PATH}ImageViewer.js ${JS_PATH}Box.js ${JS_PATH}SearchField.js > ${BIN_PATH}combined.site.js

echo "Concatenating site scripts (no prototype)"
cat ${JS_LIB_PATH}n2i.js ${JS_PATH}In2iGui.js ${JS_PATH}ImageViewer.js ${JS_PATH}Box.js ${JS_PATH}SearchField.js > ${BIN_PATH}combined.site.noproto.js
