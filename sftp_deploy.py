#!/usr/bin/env python3
"""
Ontario's Best — SFTP Static File Deployer
Uploads HTML pages directly to Cloudways via SFTP as static index.html files,
bypassing WordPress entirely. Pages render as full custom HTML with no theme wrapping.

Usage:
  pip install paramiko
  python3 sftp_deploy.py

Fill in your Cloudways SFTP credentials below before running.
"""

import paramiko
import os
import sys
from pathlib import Path

# ── SFTP CONFIG — fill these in ──────────────────────────────────────────────
SFTP_HOST   = "147.182.159.124"
SFTP_PORT     = 22
SFTP_USER   = "heck"
SFTP_PASS   = "Moneymike28$!"
PUBLIC_HTML = "/home/1604690.cloudwaysapps.com/hagyftbksy/public_html"
# e.g. "/home/master/applications/ontariosb123/public_html"
# Check Cloudways → Application → Access Details for the exact path
# ─────────────────────────────────────────────────────────────────────────────


# ── PAGE MAP ─────────────────────────────────────────────────────────────────
# (local_html_file, remote_subfolder)
# Each file is uploaded as index.html inside the subfolder
# so ontariosbest.com/online-casinos/ serves the file directly
PAGES = [
    # iGaming
    ("ontariosbest-casinos-v2.html",         "online-casinos"),
    ("ontariosbest-sportsbetting.html",       "sports-betting"),
    ("ontariosbest-poker-v2.html",            "poker"),
    ("ontariosbest-prediction-markets.html",  "prediction-markets"),

    # Tourism
    ("ontariosbest-hotels.html",              "hotels"),
    ("ontariosbest-thingstodo.html",          "things-to-do/toronto"),
    ("ontariosbest-toronto-guide.html",       "toronto"),

    # B2B
    ("ontariosbest-advertise.html",           "get-featured"),
]
# ─────────────────────────────────────────────────────────────────────────────


def connect():
    print(f"  Connecting to {SFTP_HOST}:{SFTP_PORT} as {SFTP_USER}...")
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    ssh.connect(
        hostname=SFTP_HOST,
        port=SFTP_PORT,
        username=SFTP_USER,
        password=SFTP_PASS,
        timeout=15
    )
    sftp = ssh.open_sftp()
    print("  ✅  Connected\n")
    return ssh, sftp


def mkdir_p(sftp, remote_path):
    """Create remote directory and all parents if they don't exist."""
    parts = remote_path.split("/")
    current = ""
    for part in parts:
        if not part:
            current = "/"
            continue
        current = f"{current}/{part}" if current != "/" else f"/{part}"
        try:
            sftp.stat(current)
        except FileNotFoundError:
            sftp.mkdir(current)


def deploy(sftp, local_file, remote_subfolder):
    local_path = Path(__file__).parent / local_file

    if not local_path.exists():
        print(f"  ⚠️   File not found: {local_file} — skipping")
        return False

    remote_dir  = f"{PUBLIC_HTML}/{remote_subfolder}"
    remote_file = f"{remote_dir}/index.html"

    # Ensure directory exists
    mkdir_p(sftp, remote_dir)

    # Upload
    sftp.put(str(local_path), remote_file)

    size = local_path.stat().st_size // 1024
    print(f"  ✅  /{remote_subfolder}/  ({size} KB)")
    return True


def main():
    print("\n╔══════════════════════════════════════════════════════════╗")
    print("║     Ontario's Best — SFTP Static File Deployer           ║")
    print("╚══════════════════════════════════════════════════════════╝\n")

    if SFTP_HOST == "YOUR_SERVER_IP":
        print("❌  Fill in your SFTP credentials at the top of this script first.")
        print("   Cloudways → Application → Access Details → SFTP/SSH\n")
        sys.exit(1)

    print(f"  Target:  {SFTP_HOST} → {PUBLIC_HTML}\n")

    try:
        ssh, sftp = connect()
    except Exception as e:
        print(f"  ❌  Connection failed: {e}")
        sys.exit(1)

    success = 0
    skipped = 0

    print("  Uploading pages...\n")
    for local_file, remote_subfolder in PAGES:
        if deploy(sftp, local_file, remote_subfolder):
            success += 1
        else:
            skipped += 1

    sftp.close()
    ssh.close()

    print(f"\n  Deployed: {success} ✅   Skipped: {skipped} ⚠️")

    print("""
╔══════════════════════════════════════════════════════════╗
║                    Upload Complete ✅                     ║
╚══════════════════════════════════════════════════════════╝

  Pages are now live as static HTML at:

    ontariosbest.com/online-casinos/
    ontariosbest.com/sports-betting/
    ontariosbest.com/poker/
    ontariosbest.com/prediction-markets/
    ontariosbest.com/hotels/
    ontariosbest.com/things-to-do/toronto/
    ontariosbest.com/toronto/
    ontariosbest.com/get-featured/

  ⚠️  IMPORTANT — WordPress URL conflicts:
  If WordPress is routing those slugs to its own pages,
  you need to either:

  A) Delete the WP pages with matching slugs in WP Admin
  B) Or add rewrite rules to .htaccess so Apache/Nginx
     serves the static files first (see below)

  Add this to your .htaccess ABOVE the WordPress block:

  # Serve static index.html files directly
  RewriteCond %{REQUEST_URI} ^/(online-casinos|sports-betting|poker|prediction-markets|hotels|toronto|get-featured)/?$
  RewriteCond %{DOCUMENT_ROOT}%{REQUEST_URI}index.html -f
  RewriteRule ^ %{REQUEST_URI}index.html [L]

""")


if __name__ == "__main__":
    main()
