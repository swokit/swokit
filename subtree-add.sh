#!/usr/bin/env bash
#
# usage: ./subtree-add.sh http-server
#

#echo $@
#echo $*
#echo $#

if [[ -z "$1" ]]; then
    echo -e "Usage: $(basename $0) PROJECT_NAME\n"
    echo "Example:"
    echo "  $(basename $0) http-server"
    exit
fi

set -ex
git subtree add --prefix=libs/$1 https://github.com/swokit/$1.git master --squash
