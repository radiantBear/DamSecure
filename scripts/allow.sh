#!/usr/bin/bash
#
# On the OSU servers, files and directories have to have certain permissions in order to
# be accessible from a browser. We set the correct permissions here.
for f in $(find . \( -name "*.php" -o -path "./resources/*" -o -path "./public/*" \) -not -path "./vendor/*" -not -path "./storage/*"); do
    if [ "$f" = "." ] || [ "$f" = ".." ]; then
        continue
    fi
    if [ -d "$f" ]; then
        chmod 775 "$f"
    else
        chmod 664 "$f"
    fi
done
