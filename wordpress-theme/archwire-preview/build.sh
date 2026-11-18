#!/usr/bin/env bash
# Render the Archwire home page with sample data into dist/ for design review.
#   ./build.sh && python3 -m http.server 8765 --directory dist
set -euo pipefail
cd "$(dirname "$0")"
rm -rf dist && mkdir -p dist/theme/assets
python3 make-images.py dist/img
python3 crop-photos.py dist/img
php render.php ../archwire > dist/index.html
cp -r ../archwire/assets/css ../archwire/assets/js ../archwire/assets/fonts dist/theme/assets/
echo "Built dist/index.html"
