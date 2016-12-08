#!/bin/bash

set -ex

VERSION="$1"

[ -z "${VERSION}" ] && {
	echo "Version not set." >&2
	exit 1
}

rm -f composer.json composer.lock
sed -i -e "s,[0-9]\.[0-9]-dev,${VERSION},g" ext_emconf.php
rm build-release.sh

zip -r varnishcache_${VERSION}.zip *
