#!/bin/sh

set -o errexit
set -o nounset

# Copy git hooks
cp -r githooks/* .git/hooks
