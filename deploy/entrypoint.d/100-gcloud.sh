#!/bin/bash
set -e

if \
	[[ -n "$GOOGLE_CLOUD_KEY_FILE_BASE64" ]] &&
	[[ -n "$GOOGLE_CLOUD_KEY_FILE" ]] &&
	[[ ! -e "$GOOGLE_CLOUD_KEY_FILE" ]]
then
	mkdir -p /etc/private/psykhe
	base64 -d <<<"$GOOGLE_CLOUD_KEY_FILE_BASE64" > "$GOOGLE_CLOUD_KEY_FILE"
fi
