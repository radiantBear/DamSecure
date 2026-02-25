find . \( -name "*.php" -o -path "./resources/*" -o -path "./public/*" -o -path "./docs/openapi.json" \) -not -path "./vendor/*" -not -path "./storage/*" | while read f;
do
    if [ "$f" = "." ] || [ "$f" = "./.git" ] || [ "$f" = "./uploads" ] || [ "$f" = "./scripts" ] || [ "$f" = "./.private" ] || [ "$f" = "./docs" ]; then
        continue
    fi

    FILE_PERMISSIONS=$(stat -c "%a" "$f")

    if [ -d "$f" ] && [ "$FILE_PERMISSIONS" -ne '775' ]; then
        echo
        echo "ERROR: Found directory '$f' with incorrect permissions '$FILE_PERMISSIONS'"
        echo "Run 'chmod 775 \"$f\"' (or 'sh scripts/allow.sh' to fix all permissions) from the repository root before committing."
        exit 1

    elif [ -f "$f" ] && [ "$FILE_PERMISSIONS" -ne '775' ]; then
        echo
        echo "ERROR: Found file '$f' with incorrect permissions '$FILE_PERMISSIONS'"
        echo "Run 'chmod 775 \"$f\"' (or 'sh scripts/allow.sh' to fix all permissions) from the repository root before committing."
        exit 1
    fi
done
