#!/bin/bash

DIR=$(dirname $0)
CSS_PATH=${DIR}"/../css/"
JS_LIB_PATH=${DIR}"/../lib/"
JS_PATH=${DIR}"/../js/"
IPHONE_PATH=${DIR}"/../iphone/"

#iphone 
echo "Compressing iPhone CSS"
java -jar yuicompressor-2.2.4.jar ${IPHONE_PATH}css/iphone.css --charset UTF-8 -o ${IPHONE_PATH}css/iphone.min.css

echo "Building iPhone scripts"
cat ${JS_LIB_PATH}In2iScripts/In2iScripts.js ${JS_LIB_PATH}prototype.js ${JS_PATH}In2iGui.js ${IPHONE_PATH}js/In2iPhone.js > ${IPHONE_PATH}js/combined.js
java -jar yuicompressor-2.2.4.jar ${IPHONE_PATH}js/combined.js --charset UTF-8 -o ${IPHONE_PATH}js/minimized.js