# Smilebliss Licensing — WordPress theme

Classic PHP theme. Every page is composed from ACF Flexible Content rows, so
sections can be added, reordered and removed without touching a template.

## Requirements

| | |
|---|---|
| WordPress | 6.4+ |
| PHP | 8.0+ |
| Plugin | **Advanced Custom Fields PRO** (Flexible Content is a PRO field type) |

The theme shows an admin notice and renders nothing if ACF PRO is missing.

## Install

1. Copy `smilebliss-theme/` into `wp-content/themes/` (rename the folder to
   `smilebliss` if you prefer) and activate it.
2. Activate Advanced Custom Fields PRO. The field groups load automatically from
   `acf-json/` — there is nothing to import.
3. Go to **Smilebliss → Starter Content** and click **Import starter content**.
   That builds the full page, sideloads the bundled photography and icons into
   the media library, and sets the page as the site front page.

## How a page is built

`front-page.php` and `page.php` both call `smilebliss_render_sections()`. That
walks the `sections` Flexible Content field and, for each row, loads
`template-parts/flexible/{layout}.php`.

**Layout name === partial filename.** Adding a section means adding a layout in
ACF and a file of the same name. Nothing else is wired up by hand.

| Layout | Renders |
|---|---|
| `hero` | Headline, lead, two buttons, cut-out photo, figure badge |
| `trust_cards` | The three tiles under the hero |
| `about` | Photo left, copy and animated stat rows right |
| `services` | The five support pillars |
| `revenue` | The proforma chart, highlight pills and full figures table |
| `benefits` | Numbered list left, photo right |
| `paths` | The two routes in |
| `testimonials` | Quote cards, with a sample-content flag per card |
| `checklist` | The interactive self-assessment |
| `cta_form` | Closing CTA and the enquiry form |

Every layout has an optional **Anchor (id)** field. Leave it empty and the
section keeps its default id, so in-page links like `#contact` keep working.

## Site-wide settings

**Smilebliss** in the admin menu: logo and reversed logo, header/sticky button,
contact details, footer blurb and legal line, social links, and the enquiry form
endpoint.

## The proforma chart

The figures live in the `revenue` layout's **Years** repeater. The template
prints them as JSON in a `<script type="application/json">` tag and
`assets/js/main.js` reads it. If the payload is missing or malformed the chart
falls back to its built-in figures rather than failing.

The same figures render the full table under the chart, which is what screen
readers, print and JS-off get.

**The disclaimer under the chart is not decoration.** Projected results need it.
If the figures change, the disclaimer needs reviewing with them.

## The enquiry form

The field set and both toggle groups are fixed markup, not ACF fields — they map
to the intake process. Only the surrounding copy, the submit label and the
success message are editable.

Set **Smilebliss → Settings → Form → Enquiry form endpoint** to post somewhere.
With it empty the form validates and shows the success panel without sending
anywhere, which is the current behaviour of the static build.

## No external runtime dependencies

The page loads its own CSS and JS and nothing else. The chart and the scroll
animations are hand-built; there is no Chart.js, GSAP or Three.js. The only
external request is Google Fonts.

## Checking your work

```
php -l <file>                 # every PHP file
node --check assets/js/main.js
python3 bin/check-fields.py   # run from the theme root
```

`bin/check-fields.py` cross-checks the templates and the starter content against
`acf-json/`. It fails if a partial reads a field that no field defines, if a
layout has no partial, or if the starter payload sets an unknown field — the
mistakes that otherwise show up as a silently blank section.

## Not done yet

- **Form endpoint.** Submissions currently go nowhere.
- **Footer links.** Locations, News, Careers, the partner portals, Privacy and
  Accessibility were left out rather than shipped as dead `#` links. Add them as
  a **Footer** menu once the URLs are confirmed.
- **Testimonials** are sample copy. Each card carries a visible "Sample Content"
  tag until you turn the flag off.
- **The "50+ partners" figure** needs named licensees behind it.
