#!/usr/bin/env bash
#
# usage: ./subtree-push.sh http-server
#

#echo $@
#echo $*
#echo $#

binName="bash $(basename $0)"

if [[ -z "$1" ]]; then
    echo "Usage:"
    echo "  $binName PROJECT_NAME  Pull the given project name in the ./libs dir"
    echo "  $binName all           Pull all projects in the ./libs dir"
    echo -e "\nExample:"
    echo "  $binName http-server"
    exit
fi

libs=$(ls ./libs/)
echo "Will pulled libs:"
echo ${libs}

set -ex
for lbName in ${libs} ; do
  git subtree push --prefix=libs/${lbName} https://github.com/swokit/${lbName}.git master --squash
done

