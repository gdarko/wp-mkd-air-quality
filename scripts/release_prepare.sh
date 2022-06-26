#!/bin/bash

SCRIPT_DIR="$( cd -- "$(dirname "$0")" >/dev/null 2>&1 ; pwd -P )"
PLUGIN_DIR="$( cd -- "$(dirname "$SCRIPT_DIR")" >/dev/null 2>&1 ; pwd -P )"
PLUGINS_ROOT_DIR="$( cd -- "$(dirname "$PLUGIN_DIR")" >/dev/null 2>&1 ; pwd -P )"
PLUGIN_SLUG=$(basename $PLUGIN_DIR)

if [[ -f "$PLUGIN_DIR/composer.json" ]]; then
  rm -rf "$PLUGIN_DIR/vendor"
  composer install --no-dev
fi

rm "$PLUGINS_ROOT_DIR/$PLUGIN_SLUG.zip"
cd $PLUGINS_ROOT_DIR

zip -r "$PLUGIN_SLUG.zip" "$PLUGIN_SLUG" \
 -x="*scripts*" \
 -x="*.git*" \
 -x="*README.md*"

echo "New version ready."
