#!/bin/bash

set -o errexit
set -o nounset

# Link public storage
docker exec app sh -c "ln -s ../storage/app/public public/storage"
