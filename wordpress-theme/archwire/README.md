# Archwire: orthodontic practice theme

A reusable WordPress theme for orthodontic clients. Every page is built from
**ACF Flexible Content** sections. Practice details are entered once, in
**Practice Settings**, and feed the header, footer, schema, FAQ markup and
`/llms.txt`. Copy can use `{city}`, `{practice}` and similar tokens, so one
set of starter content works for every client.

> **Status:** the PHP passes `php -l` and renders without notices through a
> stub harness (see `../archwire-preview`). It has **not** been run inside a
> live WordPress install yet. Do one staging install before the first client.

## Requirements

- WordPress 6.3+, PHP 8.0+
- One field plugin:
  - **ACF PRO**, or
  - **Secure Custom Fields** (free on wordpress.org; includes Flexible
    Content, Repeater and Options pages)
- An SEO plugin (Yoast, Rank Math, SEOPress or AIOSEO) for titles, sitemaps,
  canonicals and Open Graph. The theme leaves those to the plugin.

## New client in 15 minutes

1. Copy `archwire/` into `wp-content/themes/` and activate it.
2. **Practice → Practice Settings.** Fill every tab. Use the name, address
   and phone exactly as they appear on the Google Business Profile.
   - *Hours*: one row per set of hours, e.g. Mon–Thu 7:30–5:00, then Fri 7:30–1:00.
   - *Reviews*: the current rating and count, plus a link to the profile.
   - *Brand*: the client's color. Button and dark-panel shades are derived
     automatically and stay readable with white text (4.5:1).
   - *Search & AI*: treatments, languages, insurers, areas served.
3. **Appearance → Customize → Site Identity**: upload the logo (SVG or PNG).
4. Create a page called *Home*, save it, and click **Load starter sections**
   in the sidebar. Then set it as the homepage under **Settings → Reading**.
   The home starter leaves out *Cost and financing*, *Location and hours*,
   *Key facts* and the *Numbers band*. Those go on their own pages (e.g. Cost, Contact, About), built
   by adding the same sections there.
5. Replace every photo slot, paste real reviews, and confirm every number
   and claim with the practice (see "Before launch").
6. **Appearance → Menus**: assign *Primary*, *Footer* and *Legal links*.

## Sections

All sections are available on every page, so the same blocks build the home
page, treatment pages and city landing pages. Each one has a **Settings** tab
with an anchor ID (for links like `/#faq`), a background (white, tint, dark)
and a hide switch.

| Section | Use it for |
| --- | --- |
| Hero | Location line, h1, lead, booking + call buttons, photo, review rating with a source link |
| Highlights strip | Up to four reasons to choose the practice |
| Treatments | Cards linking to treatment pages |
| Numbers band | Up to four figures, each with what it counts, plus a source note |
| Consultation offer | The free first visit: what it includes, booking and call |
| Doctor | Portrait, bio, credentials (adds `Person` schema) |
| Treatment steps | Numbered process with timing |
| Patient reviews | Rating summary from settings + quoted reviews with name and source |
| Before and after | Drag-to-compare sliders (keyboard and touch; side by side without JS) |
| Cost and financing | Monthly price figure, ways to pay, insurers from settings |
| FAQ | Native `<details>` list that feeds `FAQPage` schema |
| Location and hours | Address, phone, the week's hours with today marked, map, areas served |
| Key facts | A plain list of who, where, what and how much, easy for AI answers to quote |
| Latest articles | Newest posts, optionally from one category |
| Call to action | Closing booking block |
| Text | Long-form rich text |

### Tokens and the smile arc

Text fields accept `{practice}`, `{doctor}`, `{city}`, `{state}`, `{phone}`,
`{area}` (first service area) and `{areas}` ("Round Rock, Cedar Park and
Hutto").

Wrap one word of a heading in `*asterisks*` to draw the smile arc under it.
This is the theme's one signature detail, so use it **once per page**, in the hero.

### One h1 per page

The first heading on a page becomes the `h1`; every later heading is `h2`. If
a page doesn't start with a Hero, the page title is printed as the `h1`.

## Local SEO and AI search (GEO)

What the theme does:

- **One source of practice details.** The header, footer, location section,
  schema and llms.txt all read the same settings, so the name, address and
  phone always match.
- **`Dentist` schema on every page**, with address, geo coordinates, opening
  hours, `areaServed` cities, `availableService` treatments, languages,
  `sameAs` profiles and a `ReserveAction` booking link. (Schema.org has no
  `Orthodontist` type; `Dentist` with `medicalSpecialty: Dentistry` and
  `knowsAbout: Orthodontics` is the closest correct match.)
- **`Person` schema** for each Doctor section, linked to the practice.
- **`FAQPage` schema** from FAQ sections on the page.
- **No review-star markup.** Google ignores self-serving review markup on
  LocalBusiness pages and can flag it. The rating is visible text that links
  to its source instead.
- **`/llms.txt`**: a Markdown summary of the practice, its pages and its
  articles, for AI assistants. Turn it off under Search & AI. If it 404s
  after activation, re-save **Settings → Permalinks** once.
- **Fallback meta description** from the page excerpt (pages support
  excerpts) or the practice description, used only when no SEO plugin is
  active.
- **Speed**: one self-hosted variable font (20 KB, preloaded), one CSS file,
  one deferred script, no jQuery and no animation libraries. The hero image
  loads eagerly with `fetchpriority="high"`; everything else is lazy.

What the agency does per client:

- **City landing pages.** Duplicate the home page for each nearby city
  (Cedar Park, Pflugerville…), then rewrite the hero, FAQ and Text sections
  for that city. Don't just swap the city name: thin copies of the same
  page hurt more than they help.
- **Treatment pages** for braces, Invisalign, early treatment and adult
  orthodontics, each with its own FAQ.
- **Answer-first writing.** Start each FAQ answer with the direct answer in
  one sentence, then add detail (40–80 words). AI answers quote these.
- Keep the Google Business Profile, Apple Business Connect, Bing Places,
  Healthgrades and the AAO "Find an Orthodontist" listing identical to
  Practice Settings.

## Design rules (unslop)

The theme was built against the
[unslop](https://github.com/heydamir/unslop) checklist by Damir Sokolovsky
(CC BY 4.0). How the common "AI-made" tells are handled:

| Tell | In this theme |
| --- | --- |
| Emoji as icons | One line icon set (Lucide), one stroke, colored by the surrounding text |
| Random spacing | Every gap and padding is a step on a 4px grid |
| Pill badge above the headline | None. The hero opens with a plain location line |
| Gradient text | Solid colors only |
| Heavy headings | Headings at weight 500; hierarchy comes from size |
| Headline for any product | Starter h1 names the treatments, the patients and the city |
| Purple-to-blue gradients | Brand palette: Dark Turquoise accent on Pale Cool Gray and white |
| Proof with no source | The rating names its source and links to it; numbers carry a source note |
| Everything centered | Hero is an asymmetric split: copy left, photo right |
| Glossy blob hero | A real photo, with a live open-now card |

**Keep it that way when editing:**

- Sentence case for labels and headings. No ALL-CAPS eyebrows.
- No exclamation marks, and no periods at the end of headings or buttons.
- Avoid "world-class", "state-of-the-art", "cutting-edge" and "seamless".
  Say what happens instead ("A digital scan replaces the impression putty").
- Only publish numbers the practice can back up, and fill in the source note.
- Use real reviews word for word, with permission.
- Use real photos of the office, team and patients rather than stock photos
  of models. The hero photo should be under 250 KB.

### Palette

| Token | Value | Use |
| --- | --- | --- |
| `--brand` | `#2E95B3` Dark Turquoise | Arc, stars, accents |
| `--brand-ink` | `#237086` | Buttons, links, labels (5.6:1 on white) |
| `--brand-deep` | `#113A46` | Dark panels |
| `--sky` | `#85BADB` Light Sky Blue | Accents on dark panels |
| `--paper` | `#F2F7FA` Pale Cool Gray | Tinted sections |
| `--white` | `#FFFFFF` | Page |

## Before launch

- [ ] Every starter number, price and credential confirmed with the practice
      (starter copy includes examples such as "$189/month" and "18 years")
- [ ] Real reviews pasted in, or the review list left empty (the summary still shows)
- [ ] Photos in every slot; before/after pairs shot with the same framing
- [ ] Google Maps embed URL and lat/long set
- [ ] Rich Results Test passes on the home page and one FAQ page
- [ ] `/llms.txt` loads
- [ ] Lighthouse mobile run on staging
- [ ] Caching plugin: keep `assets/js/main.js` deferred; it has no dependencies

## Files

```
archwire/
  style.css                 theme header
  functions.php             loads inc/*
  inc/
    practice.php            practice data, hours, address helpers
    helpers.php             tokens, headings, buttons, images, section wrapper
    setup.php               supports, menus, assets, brand colors
    fields-practice.php     Practice Settings options page
    fields-sections.php     Page sections flexible content (16 layouts)
    sections.php            renderer + FAQ collector
    schema.php              Dentist, Person, FAQPage JSON-LD
    seo.php                 meta description fallback, /llms.txt
    starter-content.php     starter sections + "Load starter sections" button
    icons.php               Lucide icons (ISC)
  template-parts/sections/  one file per layout
  assets/css/main.css       all styles (tokens at the top)
  assets/js/main.js         menu, open-now status, compare sliders
  assets/fonts/             Figtree (SIL Open Font License)
```

## Credits

- Design checklist: unslop by Damir Sokolovsky ([@heydamir](https://x.com/heydamir)), CC BY 4.0
- Icons: [Lucide](https://lucide.dev), ISC License (`assets/LICENSE-lucide.txt`)
- Font: [Figtree](https://fonts.google.com/specimen/Figtree) by Erik Kennedy, SIL Open Font License 1.1
