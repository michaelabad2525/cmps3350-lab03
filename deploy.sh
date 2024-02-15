#!/bin/bash


php index.php > index.html

DEST=~/public_html/3350/lab03
rm -rf $DEST
declare -a FILES=("index.html" "style.css")
mkdir -p $DEST
for i in "${FILES[@]}"
do
    echo "Copying $i to $DEST"
    cp "$i" $DEST/.
done

rm index.html
