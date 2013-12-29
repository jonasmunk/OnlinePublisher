#!/bin/bash

DIR=$(dirname $0)
CSS_PATH=${DIR}"/../css/"
JS_LIB_PATH=${DIR}"/../lib/"
JS_PATH=${DIR}"/../js/"
BIN_PATH=${DIR}"/../bin/"
EXT_PATH=${DIR}"/../ext/"

# ${CSS_PATH}richtext.css

echo "Concatenating CSS"
cat ${CSS_PATH}body.css ${CSS_PATH}dragproxy.css ${CSS_PATH}link.css ${CSS_PATH}text.css ${CSS_PATH}bg.css ${CSS_PATH}curtain.css ${CSS_PATH}tooltip.css ${CSS_PATH}disclosure.css ${CSS_PATH}icon.css ${CSS_PATH}button.css ${CSS_PATH}tabbox.css ${CSS_PATH}formula.css ${CSS_PATH}dropdown.css ${CSS_PATH}layout.css ${CSS_PATH}overflow.css ${CSS_PATH}alert.css ${CSS_PATH}view.css ${CSS_PATH}toolbar.css ${CSS_PATH}window.css ${CSS_PATH}list.css ${CSS_PATH}selection.css ${CSS_PATH}imageinput.css ${CSS_PATH}boundpanel.css ${CSS_PATH}panel.css ${CSS_PATH}imageviewer.css ${CSS_PATH}picker.css ${CSS_PATH}editor.css ${CSS_PATH}menu.css ${CSS_PATH}overlay.css ${CSS_PATH}upload.css ${CSS_PATH}progressbar.css ${CSS_PATH}gallery.css ${CSS_PATH}calendar.css ${CSS_PATH}datepicker.css ${CSS_PATH}box.css ${CSS_PATH}wizard.css ${CSS_PATH}searchfield.css ${CSS_PATH}dock.css ${CSS_PATH}tabs.css ${CSS_PATH}bar.css ${CSS_PATH}videoplayer.css ${CSS_PATH}message.css ${CSS_PATH}segmented.css ${CSS_PATH}links.css ${CSS_PATH}effects.css ${CSS_PATH}colorpicker.css ${CSS_PATH}locationfield.css ${CSS_PATH}tokenfield.css ${CSS_PATH}checkbox.css ${CSS_PATH}checkboxes.css ${CSS_PATH}infoview.css ${CSS_PATH}radiobutton.css ${CSS_PATH}numberfield.css ${CSS_PATH}rendering.css ${CSS_PATH}colorinput.css ${CSS_PATH}structure.css ${CSS_PATH}slider.css ${CSS_PATH}codeinput.css ${CSS_PATH}objectinput.css ${CSS_PATH}fontinput.css ${CSS_PATH}fontpicker.css ${CSS_PATH}split.css ${CSS_PATH}columns.css ${CSS_PATH}markupeditor.css > ${BIN_PATH}combined.css

echo "Concatenating site CSS"
cat ${CSS_PATH}icon.css ${CSS_PATH}curtain.css ${CSS_PATH}imageviewer.css ${CSS_PATH}editor.css ${CSS_PATH}overlay.css ${CSS_PATH}box.css ${CSS_PATH}button.css ${CSS_PATH}formula.css ${CSS_PATH}message.css ${CSS_PATH}searchfield.css ${CSS_PATH}checkbox.css ${CSS_PATH}checkboxes.css > ${BIN_PATH}combined.site.css

# ${JS_PATH}RichText.js

echo "Concatenating scripts"
cat  ${JS_PATH}hui.js ${JS_PATH}hui_animation.js ${JS_PATH}hui_color.js ${JS_PATH}hui_require.js ${JS_PATH}hui_parallax.js ${JS_PATH}hui_store.js ${JS_LIB_PATH}swfupload/swfupload.js ${JS_LIB_PATH}json2.js ${JS_LIB_PATH}date.js ${JS_PATH}ui.js ${JS_PATH}Source.js ${JS_PATH}DragDrop.js ${JS_PATH}Window.js ${JS_PATH}Formula.js ${JS_PATH}List.js ${JS_PATH}Tabs.js ${JS_PATH}ObjectList.js ${JS_PATH}DropDown.js ${JS_PATH}Alert.js ${JS_PATH}Button.js ${JS_PATH}Selection.js ${JS_PATH}Toolbar.js ${JS_PATH}ImageInput.js ${JS_PATH}BoundPanel.js ${JS_PATH}ImageViewer.js ${JS_PATH}Picker.js ${JS_PATH}Menu.js ${JS_PATH}Overlay.js ${JS_PATH}Upload.js ${JS_PATH}ProgressBar.js ${JS_PATH}Gallery.js ${JS_PATH}Calendar.js ${JS_PATH}DatePicker.js ${JS_PATH}Layout.js ${JS_PATH}Dock.js ${JS_PATH}Box.js ${JS_PATH}Wizard.js ${JS_PATH}Input.js ${JS_PATH}InfoView.js ${JS_PATH}Overflow.js ${JS_PATH}SearchField.js ${JS_PATH}Fragment.js ${JS_PATH}LocationPicker.js ${JS_PATH}Bar.js ${JS_PATH}IFrame.js ${JS_PATH}VideoPlayer.js ${JS_PATH}Segmented.js ${JS_PATH}Flash.js ${JS_PATH}Link.js ${JS_PATH}Links.js ${JS_PATH}MarkupEditor.js ${JS_PATH}ColorPicker.js ${JS_PATH}LocationField.js ${JS_PATH}StyleLength.js ${JS_PATH}DateTimeField.js ${JS_PATH}TokenField.js ${JS_PATH}Checkbox.js ${JS_PATH}Checkboxes.js ${JS_PATH}Radiobuttons.js ${JS_PATH}NumberField.js ${JS_PATH}TextField.js ${JS_PATH}Rendering.js ${JS_PATH}Icon.js ${JS_PATH}ColorInput.js ${JS_PATH}Columns.js ${JS_PATH}Finder.js ${JS_PATH}Structure.js ${JS_PATH}Slider.js ${JS_PATH}CodeInput.js ${JS_PATH}ObjectInput.js ${JS_PATH}FontInput.js ${JS_PATH}FontPicker.js ${JS_PATH}Split.js > ${BIN_PATH}combined.js

echo "Concatenating site scripts"
cat ${JS_PATH}hui.js ${JS_PATH}hui_animation.js ${JS_PATH}hui_color.js ${JS_PATH}hui_require.js ${JS_PATH}hui_parallax.js ${JS_PATH}ui.js ${JS_PATH}ImageViewer.js ${JS_PATH}Box.js ${JS_PATH}SearchField.js ${JS_PATH}Overlay.js ${JS_PATH}Button.js > ${BIN_PATH}combined.site.js

echo "Concatenating all scripts"
cat ${BIN_PATH}combined.js ${JS_PATH}Graph.js ${EXT_PATH}Graphviz.js ${JS_PATH}Test.js ${JS_PATH}Editor.js ${EXT_PATH}FlashChart.js ${JS_PATH}Drawing.js ${EXT_PATH}ImagePaster.js ${JS_PATH}Tiles.js ${JS_PATH}Pages.js ${JS_PATH}Chart.js ${JS_PATH}Diagram.js ${EXT_PATH}RichText.js > ${BIN_PATH}all.js
