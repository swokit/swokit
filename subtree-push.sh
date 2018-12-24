#!/usr/bin/env bash
#
# usage: ./subtree-add.sh http-server
#

#echo $@
#echo $*
#echo $#

binName="bash ./$(basename $0)"

if [[ -z "$1" ]]; then
    echo "Usage:"
    echo "  $binName PROJECT_NAME  Push the given project name in the ./libs dir"
    echo "  $binName all           Push all projects in the ./libs dir"
    echo -e "\nExample:"
    echo "  $binName http-server"
    exit
fi

set -ex
libs=$(ls ./libs/)
echo $libs
#git subtree add --prefix=libs/$1 https://github.com/swokit/$1.git master --squash
