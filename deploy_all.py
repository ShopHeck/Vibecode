#!/usr/bin/env python3
"""
Ontario's Best — Full Site Deployer
Deploys all HTML pages to WordPress AND uploads + assigns featured images.

Usage:
  pip install requests
  python3 deploy_all.py

  Options:
  python3 deploy_all.py --pages-only    (skip images)
  python3 deploy_all.py --images-only   (skip page deploy)

Place this script in the same folder as your HTML files.
"""

import requests
import sys
import time
import os
from pathlib import Path

# ── CONFIG ───────────────────────────────────────────────────────────────────
WP_URL  = "https://ontariosbest.com"
WP_USER = "michaelheckert@heckholdings.com"
WP_PASS = "4Ekw WddS Fakz bY8R iQFq yX2B"

API  = f"{WP_URL}/wp-json/wp/v2"
AUTH = (WP_USER, WP_PASS)
# ─────────────────────────────────────────────────────────────────────────────


# ── PAGES ────────────────────────────────────────────────────────────────────
# (html_file, slug, title, parent_slug or None)
PAGES = [
    # iGaming
    ("ontariosbest-casinos-v2.html",         "online-casinos",        "Best Online Casinos in Ontario 2026",          None),
    ("ontariosbest-sportsbetting.html",      "sports-betting",        "Best Sports Betting Sites Ontario 2026",       None),
    ("ontariosbest-poker-v2.html",           "poker",                 "Best Online Poker Sites in Ontario 2026",      None),
    ("ontariosbest-prediction-markets.html", "prediction-markets",    "Are Prediction Markets Legal in Ontario 2026", None),

    # Tourism
    ("ontariosbest-hotels.html",             "hotels",                "Best Hotels in Ontario 2026",                  None),
    ("ontariosbest-thingstodo.html",         "toronto-things-to-do",  "Best Things To Do in Toronto 2026",            "things-to-do"),
    ("ontariosbest-toronto-guide.html",      "toronto",               "Toronto City Guide 2026",                      None),

    # B2B
    ("ontariosbest-advertise.html",          "get-featured",          "Get Featured on Ontario's Best",               None),
]
# ─────────────────────────────────────────────────────────────────────────────


# ── FEATURED IMAGES ──────────────────────────────────────────────────────────
# (page_slug, filename, alt_text, unsplash_photo_id)
# All Unsplash images are free for commercial use — no attribution required.
IMAGES = [
    (
        "online-casinos",
        "featured-online-casinos.jpg",
        "Best Online Casinos in Ontario 2026 — AGCO Licensed",
        "photo-1601597111158-2fceff292cdc",   # casino chips / cards
    ),
    (
        "sports-betting",
        "featured-sports-betting.jpg",
        "Best Sports Betting Sites Ontario 2026",
        "photo-1522778119026-d647f0596c20",   # stadium at night
    ),
    (
        "poker",
        "featured-poker.jpg",
        "Best Online Poker Sites Ontario 2026 — All 6 Licensed Rooms",
        "photo-1541278107931-e006523892df",   # poker chips and cards
    ),
    (
        "prediction-markets",
        "featured-prediction-markets.jpg",
        "Are Prediction Markets Legal in Ontario 2026?",
        "photo-1611974789855-9c2a0a7236a3",   # financial trading screens
    ),
    (
        "hotels",
        "featured-hotels.jpg",
        "Best Hotels in Ontario 2026 — Luxury and Boutique Picks",
        "photo-1566073771259-6a8506099945",   # luxury hotel pool
    ),
    (
        "toronto-things-to-do",
        "featured-things-to-do-toronto.jpg",
        "Best Things To Do in Toronto 2026 — Top Attractions",
        "photo-1517090504586-fde19ea6066f",   # Toronto skyline + CN Tower
    ),
    (
        "toronto",
        "featured-toronto-guide.jpg",
        "Toronto City Guide 2026 — Complete Visitor's Guide",
        "photo-1569701813229-33284b643e3c",   # Toronto waterfront panorama
    ),
    (
        "get-featured",
        "featured-advertise.jpg",
        "Get Featured on Ontario's Best — Sponsored Placement Packages",
        "photo-1460925895917-afdab827c52f",   # business / laptop / data
    ),
]

IMG_W = 1200
IMG_H = 628
# ─────────────────────────────────────────────────────────────────────────────


# ═══════════════════════════════════════════════════════════════════════════════
#  HELPERS
# ═══════════════════════════════════════════════════════════════════════════════

def header(text):
    bar = "─" * 58
    print(f"\n{bar}")
    print(f"  {text}")
    print(f"{bar}\n")


def check_auth():
    try:
        r = requests.get(f"{API}/users/me", auth=AUTH, timeout=15)
    except requests.exceptions.ConnectionError:
        print("❌  Could not reach ontariosbest.com — check your internet connection.")
        return False

    if r.status_code == 200:
        data = r.json()
        print(f"  ✅  Authenticated as: {data.get('name')} ({data.get('email', '')})")
        print(f"  🔑  Roles: {data.get('roles', [])}\n")
        return True

    print(f"  ❌  Auth failed ({r.status_code})")
    if r.status_code == 401:
        print("  ⚠️   Check your Application Password — it may have been revoked.")
    print(f"  →  {r.text[:200]}")
    return False


def get_page_id(slug):
    r = requests.get(
        f"{API}/pages",
        params={"slug": slug, "per_page": 1},
        auth=AUTH, timeout=15
    )
    data = r.json()
    if isinstance(data, list) and data:
        return data[0]["id"]
    return None


def get_or_create_parent(slug):
    """Ensure a parent page container exists (e.g. /things-to-do/)."""
    existing = get_page_id(slug)
    if existing:
        return existing
    r = requests.post(
        f"{API}/pages",
        json={
            "title":   slug.replace("-", " ").title(),
            "slug":    slug,
            "status":  "publish",
            "content": "",
        },
        auth=AUTH, timeout=15
    )
    if r.status_code == 201:
        pid = r.json()["id"]
        print(f"    📁  Created parent page /{slug}/ (ID {pid})")
        return pid
    print(f"    ⚠️   Could not create parent '{slug}': {r.text[:100]}")
    return None


# ═══════════════════════════════════════════════════════════════════════════════
#  STEP 1 — DEPLOY PAGES
# ═══════════════════════════════════════════════════════════════════════════════

def deploy_pages():
    header("STEP 1 / 2 — Deploying HTML Pages")

    parent_cache = {}
    success = 0
    skipped = 0

    for filename, slug, title, parent_slug in PAGES:
        print(f"  →  {title}")

        # Read HTML
        path = Path(__file__).parent / filename
        if not path.exists():
            print(f"     ⚠️   File not found: {filename} — skipping\n")
            skipped += 1
            continue

        html = path.read_text(encoding="utf-8")

        # Resolve parent if needed
        parent_id = None
        if parent_slug:
            if parent_slug not in parent_cache:
                parent_cache[parent_slug] = get_or_create_parent(parent_slug)
            parent_id = parent_cache[parent_slug]

        # Build payload
        payload = {
            "title":   title,
            "slug":    slug,
            "status":  "publish",
            "content": html,
        }
        if parent_id:
            payload["parent"] = parent_id

        # Create or update
        existing_id = get_page_id(slug)
        if existing_id:
            r = requests.post(
                f"{API}/pages/{existing_id}",
                json=payload, auth=AUTH, timeout=60
            )
            action = "Updated"
        else:
            r = requests.post(
                f"{API}/pages",
                json=payload, auth=AUTH, timeout=60
            )
            action = "Created"

        if r.status_code in (200, 201):
            link = r.json().get("link", "")
            print(f"     ✅  {action}: {link}")
            success += 1
        else:
            print(f"     ❌  Failed ({r.status_code}): {r.text[:200]}")

        print()
        time.sleep(0.5)   # be polite to the API

    print(f"  Pages deployed: {success} ✅   Skipped: {skipped} ⚠️")


# ═══════════════════════════════════════════════════════════════════════════════
#  STEP 2 — FEATURED IMAGES
# ═══════════════════════════════════════════════════════════════════════════════

def download_image(photo_id, width=IMG_W, height=IMG_H):
    url = f"https://images.unsplash.com/{photo_id}?w={width}&h={height}&fit=crop&q=85&auto=format"
    print(f"     ↓  Fetching from Unsplash CDN...")
    try:
        r = requests.get(url, timeout=30)
        if r.status_code == 200:
            return r.content
    except Exception as e:
        print(f"     ⚠️   Download error: {e}")

    # Fallback to Toronto skyline
    print(f"     ⚠️   Primary image failed — using fallback")
    fallback = f"https://images.unsplash.com/photo-1517090504586-fde19ea6066f?w={width}&h={height}&fit=crop&q=85"
    try:
        r2 = requests.get(fallback, timeout=30)
        if r2.status_code == 200:
            return r2.content
    except Exception:
        pass

    return None


def upload_to_media_library(img_bytes, filename, alt_text):
    r = requests.post(
        f"{API}/media",
        data=img_bytes,
        headers={
            "Content-Disposition": f'attachment; filename="{filename}"',
            "Content-Type":        "image/jpeg",
        },
        auth=AUTH,
        timeout=60,
        params={"alt_text": alt_text, "title": alt_text},
    )
    if r.status_code == 201:
        media = r.json()
        print(f"     ☁️   Uploaded to Media Library (ID {media['id']})")
        return media["id"]
    print(f"     ❌  Upload failed ({r.status_code}): {r.text[:200]}")
    return None


def assign_featured_image(page_id, media_id):
    r = requests.post(
        f"{API}/pages/{page_id}",
        json={"featured_media": media_id},
        auth=AUTH, timeout=15
    )
    if r.status_code == 200:
        print(f"     🖼   Featured image assigned to page ID {page_id}")
        return True
    print(f"     ❌  Assignment failed: {r.text[:200]}")
    return False


def upload_images():
    header("STEP 2 / 2 — Uploading Featured Images")

    success = 0
    skipped = 0

    for slug, filename, alt_text, photo_id in IMAGES:
        print(f"  →  {slug}")

        # Find the page
        page_id = get_page_id(slug)
        if not page_id:
            print(f"     ⚠️   Page '{slug}' not found — run without --images-only first\n")
            skipped += 1
            continue

        print(f"     📄  Page found (ID {page_id})")

        # Download
        img_data = download_image(photo_id)
        if not img_data:
            print(f"     ❌  Could not download image — skipping\n")
            skipped += 1
            continue

        print(f"     📦  Downloaded {len(img_data) // 1024} KB")

        # Upload to WP
        media_id = upload_to_media_library(img_data, filename, alt_text)
        if not media_id:
            skipped += 1
            print()
            continue

        # Assign
        if assign_featured_image(page_id, media_id):
            success += 1

        print()
        time.sleep(1)

    print(f"  Images uploaded & assigned: {success} ✅   Skipped: {skipped} ⚠️")


# ═══════════════════════════════════════════════════════════════════════════════
#  MAIN
# ═══════════════════════════════════════════════════════════════════════════════

def main():
    pages_only  = "--pages-only"  in sys.argv
    images_only = "--images-only" in sys.argv

    print("\n╔══════════════════════════════════════════════════════════╗")
    print("║       Ontario's Best — WordPress Full Site Deployer       ║")
    print("╚══════════════════════════════════════════════════════════╝\n")
    print(f"  Target:  {WP_URL}")
    print(f"  User:    {WP_USER}")
    mode = "Pages only" if pages_only else "Images only" if images_only else "Full deploy (pages + images)"
    print(f"  Mode:    {mode}\n")

    if not check_auth():
        sys.exit(1)

    if not images_only:
        deploy_pages()

    if not pages_only:
        upload_images()

    print("\n╔══════════════════════════════════════════════════════════╗")
    print("║                    Deploy Complete ✅                     ║")
    print("╚══════════════════════════════════════════════════════════╝\n")
    print("  Next steps:")
    print("  1. Visit https://ontariosbest.com/wp-admin/pages/ to")
    print("     confirm all pages are live and images are set")
    print()
    print("  2. For full-width rendering (no theme header/footer),")
    print("     install 'HTML Page Templates' plugin so each page")
    print("     serves your raw HTML without WP theme wrapping")
    print()
    print("  3. Revoke this Application Password when done:")
    print("     WP Admin → Users → Profile → Application Passwords")
    print()
    print("  4. Submit sitemap to Google Search Console:")
    print(f"     {WP_URL}/sitemap.xml")
    print()


if __name__ == "__main__":
    main()
