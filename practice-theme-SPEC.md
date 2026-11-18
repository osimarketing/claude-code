# Practice Theme — build spec

A reusable WordPress theme for orthodontic / dental practice sites, sold or
deployed per client. Structure derived from the Coastal Orthodontics build at
`talented-unicorn.10web.cloud` (a 10Web AI-generated site), rebuilt as a proper
classic theme with ACF PRO Flexible Content so every section is editable and
re-orderable per client.

Two goals, and they partly conflict — read "Design direction" before building.

---

## 1. Where the source came from

The reference site is a 10Web `wvc-theme` build: the page is a compiled React
app (SSR + hydrate) from `/wp-content/uploads/wvc-builds/build_1857294/Home/`,
using Tailwind + shadcn/ui + Radix + Lucide. It is not editable in the WP block
editor. We are rebuilding its *structure*, not porting its code.

Only the rendered HTML has been captured so far. **Still needed:**

- `/wp-content/themes/wvc-theme/style.css`
- `/wp-content/uploads/wvc-builds/build_1857294/Home/Home.css`  ← token values
- Screenshots of the rendered page (desktop + mobile)

Without these the palette, type scale and spacing are unknown.

---

## 2. Design direction — resolve this first

The request is both "recreate this site" and "apply the unslop pass". These
conflict. The reference site carries most of the catalogued slop signs:

| Sign in the reference build | Unslop fix |
|---|---|
| Sparkle icon ×3 (hero badge, feature card, services badge) | Remove |
| Pill badge above the headline (`✨ Coastal Orthodontics • …`) | Remove, or quiet text |
| Decorative squiggle SVG in **six** sections | One visual where it helps |
| Blurred blob behind first card (`w-64 h-64 blur-xl`), `glow-primary-hover` | Remove; depth from surface steps |
| Every control `rounded-full` | Radius by size: controls 10, badges 8, cards 12–16, panels 14–20 |
| Uppercase + wide tracking ("Investment", "Direct Line", "Connect With Us") | Sentence case, normal tracking |
| `hover:-translate-y-0.5` / `scale-105` on nearly everything | Colour or surface step on hover |
| `animate-pulse` skeletons as the shipped nav state | Server-render the menu |
| 5 stars with no source | Make specific and sourced, or remove |

**Recommended resolution:** keep the information architecture and section
order exactly; rebuild the surface clean. The deliverable is a theme that
*does the same job* as the reference, not one that looks identical to it.

Decide before building, because it changes the field set (e.g. an "eyebrow
badge" field only exists if we keep the badge).

---

## 3. Section inventory → ACF Flexible Content layouts

Derived from the reference markup. Each becomes one layout in
`template-parts/flexible/`.

### `hero`
- `eyebrow_text` (text, optional — see Design direction)
- `heading_lead` (text) + `heading_accent` (text) — rendered as two spans, the
  second in the accent colour. Reference: "Creating confident, healthy smiles"
  / "in [City, State Zip]."
- `lead` (textarea)
- `button_primary` (link), `button_secondary` (link)
- `image` (image), `image_alt` handled by the media library
- Layout: 12-col split, copy 6 / image 6, image full-bleed to the right edge

### `cta_band`
Dark band. Reference id `consultation-call-to-action`.
- `heading` (text), `text` (textarea), `button` (link)
- `tone` (select: dark | light)

### `feature_grid`
Reference id `why-choose-us`. Heading block + 4 cards in a 12-col grid, cards
at `lg:col-span-6`. The cards are **not** uniform — three variants:
- `heading` (text), `intro` (textarea)
- `cards` (repeater):
  - `style` (select: **filled** | **image** | **bordered**)
  - `icon` (select from a curated icon set — do not free-text Lucide names)
  - `title` (text), `text` (textarea)
  - `image` (image — only for `style = image`)
  - `footnote` (text, optional — the small line under the divider)

### `services_pricing`
- `badge` (text, optional), `heading_lead` + `heading_accent`, `intro`
- `cards` (repeater, 3-up):
  - `image`, `title`, `text`
  - `price_label` (text, default "Investment")
  - `price` (text — free text, e.g. "Starting at $3,200 / Flexible Plans")
  - `button` (link)

> **Client-safety note:** published prices are a commitment. Make the whole
> price block toggleable per client (`show_pricing` true/false).

### `testimonials`
Left sticky column + right timeline of cards.
- `badge` (text), `heading` (text), `intro` (textarea)
- `quotes` (repeater):
  - `rating` (number 1–5), `quote` (textarea), `name` (text), `role` (text)
  - `is_sample` (true/false) — renders a visible "Sample content" tag, so a
    client site never ships fabricated reviews unlabelled

### `social_grid`
- `heading_lead` + `heading_accent`, `intro`
- `images` (gallery, 6 images, square crop)
- `button` (link), `handle` (text)

### `final_cta`
- `heading`, `text`, `button`, `image`
- Layout: copy 5 / image 7

### Header (theme options, not flexible)
- `logo` (image, plus `logo_reverse`), header menu location, `header_cta` (link)

### Footer (theme options, not flexible)
- `address`, `phone`, `email`
- `hours` (repeater: `days`, `times`)
- `social` (repeater: `network` select, `url`)
- `copyright` (text), `bottom_links` (repeater: link)
- Footer menu location

---

## 4. Reuse from `smilebliss-theme/`

That theme is a working implementation of this exact pattern. Lift wholesale:

- `functions.php`, `inc/acf.php` — ACF PRO bootstrap, local JSON sync, options page
- `acf-json/` — field groups as version-controlled JSON
- `inc/starter-sections.php` — starter-content importer so a fresh install
  renders a complete demo site
- `bin/check-fields.py` — **cross-checks every `get_sub_field()` in every
  template against `acf-json/`, and the starter payload too.** Catches typo'd
  field names before they become silent empty sections. Negative-tested.
- `front-page.php` / `page.php` flexible-content loop and `template-parts/no-sections.php`
- The `assets/css/main.css` ↔ page `<style>` sync discipline

What must change: everything is `smilebliss_` / `sb_` prefixed. Rename to a
neutral prefix for a multi-client theme.

---

## 5. Fixes the reference site needs (do not reproduce these)

Live placeholder content on the reference build:

- `[City, State Zip]` literally in the H1 and footer address
- "Office Address Placeholder", `(555) 000-0000`, `hello@example.com`
- All four social links point to bare `instagram.com` / `facebook.com` /
  `twitter.com` / `linkedin.com`, including in the `sameAs` Organization schema
- Footer reads "Reusable practice website template."
- A testimonial names "Dr. Specialist"

Two genuine bugs:

- **Empty logo link.** Header and footer both render `<a href="…"></a>` with no
  content and no accessible name. The logo is injected on hydration.
  → In our build, server-render the logo with real `alt`.
- **JS-only navigation.** Both menus ship as `animate-pulse` skeletons and are
  fetched client-side. If the request fails the visitor gets pulsing grey bars.
  → In our build, render `wp_nav_menu()` server-side.

Because the theme ships to clients, the field set should make the placeholder
trap hard to fall into: required fields where a blank is worse than an omission,
and a per-client checklist in the README.

---

## 6. Non-negotiables to carry over

- Keyboard focus visible on every control (the Smilebliss build shipped for a
  while with `outline: 3px solid var(--navy)` where `--navy` was undefined,
  which silently resolved to no focus ring at all — verify computed styles, not
  source)
- `prefers-reduced-motion` respected
- Works down to 360px
- WCAG AA: 4.5:1 body text, 3:1 large text and non-text UI — measured, not assumed
- No horizontal scroll at any width

Verification harness worth rebuilding: headless Chromium sweep across ~18
widths asserting no h-scroll, no element wider than the viewport, all reveals
settled, focus ring present.

---

## 7. Open questions

1. Which client/practice is the first real deployment?
2. Keep the reference's visual identity, or clean surface per §2?
3. Is pricing shown by default?
4. Does the theme need multi-page support (About / Braces / Invisalign /
   Contact exist on the reference) or is the front page the whole product?
5. Licensing of the reference imagery — the 10Web build's photos are generated;
   client sites will need their own.
