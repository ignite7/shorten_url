#!/bin/sh

set -o errexit
set -o nounset

# Copy git hooks
cp -r githooks/* .git/hooks

printf "Moving git hooks to '.git/hooks' 📁\n"

# Make only the copied files executable
for file in githooks/*; do
    if [ -f "$file" ]; then
        target=".git/hooks/$(basename "$file")"
        chmod +x "$target"
        printf "Made %s git hook executable 📄\n" "$target"
    fi
done

printf "Git hooks copied and made executable! ✅\n"
