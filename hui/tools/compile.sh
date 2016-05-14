#!/bin/bash

DIR=$(dirname $0)
BIN_PATH=${DIR}"/../bin/"

${DIR}/join.sh

echo "Compressing scripts"
java -jar ${DIR}/yuicompressor-2.4.8.jar ${BIN_PATH}joined.js --charset UTF-8 -o ${BIN_PATH}minimized.js
echo "Compressing site scripts"
java -jar ${DIR}/yuicompressor-2.4.8.jar ${BIN_PATH}joined.site.js --charset UTF-8 -o ${BIN_PATH}minimized.site.js
echo "Compressing compatibility script"
java -jar ${DIR}/yuicompressor-2.4.8.jar ${DIR}/../js/compatibility.js --charset UTF-8 -o ${BIN_PATH}compatibility.min.js
echo "Compressing css"
java -jar ${DIR}/yuicompressor-2.4.8.jar ${BIN_PATH}joined.css --charset UTF-8 -o ${BIN_PATH}minimized.css
echo "Compressing site css"
java -jar ${DIR}/yuicompressor-2.4.8.jar ${BIN_PATH}joined.site.css --charset UTF-8 -o ${BIN_PATH}minimized.site.css
echo "Finished!"