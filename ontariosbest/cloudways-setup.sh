#!/usr/bin/env bash
# =============================================================================
# OntariosBest.com — Cloudways Server Bootstrap
# Run this ONCE on the Cloudways server after WordPress is installed.
#
# Usage (from your Mac terminal):
#   ssh master@147.182.159.124 -p 22
#   bash <(curl -s https://raw.githubusercontent.com/ShopHeck/Vibecode/claude/setup-vibecode-cli-QkD85/ontariosbest/cloudways-setup.sh)
#
# Or manually:
#   1. Clone the repo to a temp location
#   2. Copy files into public_html
#   3. Run deploy.sh
# =============================================================================

set -euo pipefail

WEBROOT="/home/1604690.cloudwaysapps.com/hagyftbksy/public_html"
REPO="https://github.com/ShopHeck/Vibecode.git"
BRANCH="claude/setup-vibecode-cli-QkD85"
TMP_DIR="/tmp/vibecode-deploy"

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

log()  { echo -e "${GREEN}[✓]${NC} $1"; }
warn() { echo -e "${YELLOW}[!]${NC} $1"; }

echo ""
echo "=================================================="
echo "  OntariosBest.com — Cloudways Bootstrap"
echo "=================================================="
echo ""

# Clone repo
log "Cloning repository..."
rm -rf "$TMP_DIR"
git clone --depth=1 --branch "$BRANCH" "$REPO" "$TMP_DIR"
log "Repository cloned"

# Copy deploy files to webroot
log "Copying deploy files to $WEBROOT..."
cp "$TMP_DIR/ontariosbest/dist/ontariosbest-theme.zip" "$WEBROOT/ontariosbest-theme.zip"
cp "$TMP_DIR/ontariosbest/deploy.sh" "$WEBROOT/deploy.sh"
mkdir -p "$WEBROOT/acf"
cp "$TMP_DIR/ontariosbest/wordpress/acf/"*.json "$WEBROOT/acf/"
chmod +x "$WEBROOT/deploy.sh"
log "Files copied"

# Run deploy script from webroot
log "Running deploy script..."
cd "$WEBROOT"
bash deploy.sh

# Cleanup
rm -rf "$TMP_DIR"
log "Cleanup done"

echo ""
echo "Bootstrap complete! Check output above for next steps."
