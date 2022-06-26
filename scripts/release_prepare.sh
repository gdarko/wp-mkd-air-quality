#!/bin/bash

SCRIPTDIR="$( cd -- "$(dirname "$0")" >/dev/null 2>&1 ; pwd -P )"
BASEDIR="$( cd -- "$(dirname "$SCRIPTDIR")" >/dev/null 2>&1 ; pwd -P )"

cd $BASEDIR
rm -rf vendor/
composer install --no-dev
cd ..

rm digital-license-manager.zip

zip -r wp-mkd-air-quality.zip wp-mkd-air-quality \
-x "wp-mkd-air-quality/.git/*" \
-x "wp-mkd-air-quality/.gitignore" \
-x "wp-mkd-air-quality/README.md";

echo "New version ready."
