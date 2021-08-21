#!/bin/bash
set -e

for f in /usr/local/entrypoint/etc/entrypoint.d/*.sh; do
  if [[ -x "$f" ]]; then
    "$f"
  else
    . "$f"
  fi
done
