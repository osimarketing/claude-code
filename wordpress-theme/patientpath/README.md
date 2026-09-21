# PatientPath — WordPress Theme (scaffold)

A WordPress theme scaffold generated from the PatientPath static site. The homepage
is built with **ACF Flexible Content** so editors can add, reorder, and edit sections.

> **Status: scaffold.** All PHP passes `php -l`, but this has **not** been run inside a
> live WordPress install. Your team validates/finishes it (see "What's left").

## Requirements
- WordPress 6.0+, PHP 8.0+
- **Advanced Custom Fields PRO** (Flexible Content + Repeater are PRO-only) — required
- A form plugin for the contact form (WPForms / Gravity Forms / Contact Form 7)

## Install
1. Copy the `patientpath/` folder into `wp-content/themes/`.
2. Install & activate **ACF PRO**, then activate the **PatientPath** theme.
   - The "Page Sections" field group is registered in PHP (`inc/acf-fields.php`) — no import needed.
3. Create a Page (e.g. "Home"), then **Settings → Reading → Your homepage displays →
   A static page → Homepage = Home**.
4. Edit the Home page → **Page Sections** → add layouts in order:
   `Hero → What We Do → Problems We Solve → How We Work → Industries →
   Environment photo strip → Impact → Selected Work → Insights → Planner → CTA`.
5. Upload images to the Media Library and fill each section. Starter copy defaults are
   pre-filled on many fields.

## Content mapping
- **Blog posts** → real WordPress posts. The **Insights** layout pulls the latest N posts
  automatically (set the count). `single.php` matches the article design; set a Featured
  Image (used as the article hero image and the Insights card image).
- **Testimonials / work samples / industries / stats** → repeaters inside their layouts.
- **Contact form** → paste your form plugin's **shortcode** into the CTA layout's
  "Form shortcode" field. If empty, a non-functional placeholder form is shown.

## Important: JS optimization plugins
The 3D hero uses **Three.js as an ES module + an import map** (printed in `footer.php`),
plus GSAP/ScrollTrigger and `site.js`. Caching/optimization plugins (WP Rocket,
Autoptimize, LiteSpeed, SG Optimizer, etc.) **must exclude** these from JS
concatenation / minification / defer, or the hero and interactions break:
- `assets/js/vendor/gsap.min.js`
- `assets/js/vendor/ScrollTrigger.min.js`
- `assets/js/vendor/three.module.js`
- `assets/js/hero-scene.js`  ← ES module, never combine/defer
- `assets/js/site.js`

## Fonts
Currently Google Fonts (Poppins + Inter), enqueued in `functions.php`. Swap for
**Museo Sans Rounded** once licensed (self-host the webfont files or add the Adobe
Fonts embed), then update the `--display` / `--sans` variables in `assets/css/site.css`.

## What's left (your team)
- Stand up local + staging WordPress; validate the theme in a real install.
- Install ACF PRO + form plugin; wire the contact form (email, notifications, spam).
- Migrate the 3 blog posts; upload images; set featured images.
- Configure optimization-plugin exclusions (above) and verify the 3D hero renders.
- QA: editor experience (add/reorder layouts), cross-browser, mobile, Lighthouse,
  accessibility.
- Launch: DNS/SSL, redirects, caching/CDN, backups.

## Structure
```
patientpath/
  style.css                     theme header
  functions.php                 setup, enqueues, helpers, ACF registration include
  header.php / footer.php        chrome; footer prints the ES-module import map
  front-page.php                loops the "Page Sections" flexible layouts
  page.php / index.php / single.php
  inc/acf-fields.php            ACF Flexible Content field group (all layouts)
  template-parts/flexible/*.php one partial per layout
  assets/                       css, js, images (copied from the static build)
```
