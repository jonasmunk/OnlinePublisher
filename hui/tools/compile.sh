#!/bin/bash

DIR=$(dirname $0)
BIN_PATH=${DIR}"/../bin/"

${DIR}/concat.sh

echo "Compressing scripts"
java -jar yuicompressor-2.2.4.jar ${BIN_PATH}combined.js --charset UTF-8 -o ${BIN_PATH}minimized.js
echo "Compressing site scripts"
java -jar yuicompressor-2.2.4.jar ${BIN_PATH}combined.site.js --charset UTF-8 -o ${BIN_PATH}minimized.site.js
echo "Compressing css"
java -jar yuicompressor-2.2.4.jar ${BIN_PATH}combined.css --charset UTF-8 -o ${BIN_PATH}minimized.css
echo "Compressing site css"
java -jar yuicompressor-2.2.4.jar ${BIN_PATH}combined.site.css --charset UTF-8 -o ${BIN_PATH}minimized.site.css
echo "Finished!"