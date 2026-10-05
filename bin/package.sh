#!/bin/sh
# Builds upload-ready zips of the theme and plugin in dist/.
#   sh bin/package.sh
# Then in WordPress: Appearance › Themes › Add New › Upload Theme (signal-shield.zip)
# and Plugins › Add New › Upload Plugin (signal-shield-core.zip), and activate both.
set -e
cd "$(dirname "$0")/.."
mkdir -p dist
rm -f dist/signal-shield.zip dist/signal-shield-core.zip
python3 - <<'PY'
import os, zipfile
for folder, out in (("wp-content/themes/signal-shield", "dist/signal-shield.zip"),
                    ("wp-content/plugins/signal-shield-core", "dist/signal-shield-core.zip")):
    base = os.path.dirname(folder)
    with zipfile.ZipFile(out, "w", zipfile.ZIP_DEFLATED) as z:
        for root, dirs, files in os.walk(folder):
            dirs[:] = [d for d in dirs if not d.startswith(".git")]
            for f in sorted(files):
                if f == ".DS_Store":
                    continue
                path = os.path.join(root, f)
                z.write(path, os.path.relpath(path, base))
    print("Wrote", out)
PY
