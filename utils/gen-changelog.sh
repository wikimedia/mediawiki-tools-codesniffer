#!/bin/bash
# Generate changelog for HISTORY.md based on git log
git log --format='* %s (%aN)' --no-merges --reverse $(git describe --abbrev=0)...HEAD | sort
