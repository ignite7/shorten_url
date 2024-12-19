#!/bin/sh

set -o errexit
set -o nounset

# Run git hooks script
sh entrypoints/git-hooks.sh

# Run remove packages script
sh entrypoints/remove-packages.sh

# Delete storage
sudo python3 entrypoints/clean_storage.py

# Docker
docker compose up -d
