# Proof - Sales Notifications for WooCommerce

Show small popups in a corner of the screen that surface recent real purchases
from your WooCommerce store — for example "Alex from Berlin bought Hoodie 2 hours
ago" — to build trust and a gentle sense of urgency.

## Features

- Builds a rotation of recent-sale popups from real WooCommerce orders (completed and processing).
- Privacy-safe: only first name and billing city are ever exposed to the browser — never surnames, emails, addresses or order numbers, and each field is individually toggleable.
- Real orders only: demo data is off by default, and with no qualifying orders the plugin shows nothing rather than inventing sales.
- Lightweight vanilla-JS popup loaded in the footer, with a configurable initial delay, display time, interval and per-page-view frequency cap.
- Cached order feed so the storefront does not query orders on every page load.
- Accessible and performance-friendly: announced via an aria-live region, respects prefers-reduced-motion, dark-mode aware, and fixed positioning means no layout shift.

## Installation

1. Upload the plugin to `/wp-content/plugins/proof`, or install it via Plugins → Add New.
2. Activate it. WooCommerce must be installed and active.
3. Configure display scope, fields, timing and limits under WooCommerce → Proof.

## Frequently Asked Questions

**Does it require WooCommerce?**
Yes. WooCommerce must be installed and active.

**What customer data is shown?**
Only the first name and billing city, and only the fields you enable. No
surnames, emails, addresses or order numbers are ever sent to the browser.

Built by WPPoland — https://plogins.com

License: GPL-2.0-or-later
