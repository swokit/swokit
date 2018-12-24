#!/usr/bin/env bash
#
# usage: ./subtree-add.sh http-server
#

#echo $@
#echo $*
#echo $#

binName="bash $(basename $0)"

if [[ -z "$1" ]]; then
    echo -e "Usage: $binName PROJECT_NAME\n"
    echo "Example:"
    echo "  $binName http-server"
    exit
fi

set -ex
git subtree add --prefix=libs/$1 https://github.com/swokit/$1.git master --squash
