#!/bin/bash

DIR=$(dirname $0)
CSS_PATH=${DIR}"/../css/"
JS_LIB_PATH=${DIR}"/../lib/"
JS_PATH=${DIR}"/../js/"
BIN_PATH=${DIR}"/../bin/"

${DIR}/concat.sh

echo "Compressing prototype"
#java -jar yuicompressor-2.2.4.jar ../lib/prototype.js --charset UTF-8 -o ../lib/prototype.min.js
echo "Compressing scripts"
java -jar yuicompressor-2.2.4.jar ${BIN_PATH}combined.js --charset UTF-8 -o ${BIN_PATH}minimized.js
echo "Compressing basic scripts"
#java -jar yuicompressor-2.2.4.jar ${BIN_PATH}combined.basic.js --charset UTF-8 -o ${BIN_PATH}minimized.basic.js
echo "Compressing site scripts"
java -jar yuicompressor-2.2.4.jar ${BIN_PATH}combined.site.js --charset UTF-8 -o ${BIN_PATH}minimized.site.js
echo "Compressing site scripts (no prototype)"
#java -jar yuicompressor-2.2.4.jar ${BIN_PATH}combined.site.noproto.js --charset UTF-8 -o ${BIN_PATH}minimized.site.noproto.js
echo "Compressing css"
java -jar yuicompressor-2.2.4.jar ${BIN_PATH}combined.css --charset UTF-8 -o ${BIN_PATH}minimized.css