#!/bin/bash

DEST=~/public_html/3350/lab03
declare -a FILES=("auth.php" "index.php" "style.css" "users.php")
mkdir -p $DEST
for i in "${FILES[@]}"
do
    echo "Copying $i to $DEST"
    cp "$i" $DEST/.
done