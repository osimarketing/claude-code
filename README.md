# PatientPath — Landing Page

A visually captivating, high-converting landing page for **PatientPath** ("Your Healthcare Growth Partner"), built with **GSAP** and **Three.js**. Inspired by the brand at [yourpatientpath.com](https://yourpatientpath.com), reimagined as an immersive, animated single-page experience.

![Built with GSAP & Three.js](https://img.shields.io/badge/built%20with-GSAP%20%2B%20Three.js-eb9b3e)

## ✨ Highlights

- **Three.js animated background** — a drifting "connected paths" particle network (nodes + glowing links) with parallax that reacts to the pointer and scroll, plus brand-colored rings echoing the original PatientPath mark. Pauses when the tab is hidden, scales down on mobile, and disables entirely for `prefers-reduced-motion`.
- **GSAP + ScrollTrigger** — masked hero headline reveal, staggered scroll reveals, parallax on the feature image, and a smooth load timeline.
- **Animated metric counters** — impact/RCM numbers count up when scrolled into view (IntersectionObserver).
- **High-conversion structure** — clear value prop, social-proof marquee, four service pillars, RCM proof stats, impact numbers, testimonial, and a working lead-capture CTA with inline validation.
- **Fully responsive** — verified with headless Chromium at 1440 / 1024 / 768 / 390 / 320 px. Off-canvas mobile nav, no horizontal overflow at any width.
- **Self-contained** — GSAP and Three.js are vendored locally (`assets/js/vendor/`), so the site has **no runtime CDN dependency** and works offline. The hero/feature artwork is inline SVG.
- **Accessible** — semantic landmarks, ARIA labels, keyboard-operable nav, visible focus states, and full reduced-motion fallbacks.

## 🚀 Run it

No build step required — it's static. Serve the folder with any static server:

```bash
npm start            # python3 -m http.server 8080
# then open http://localhost:8080
```

Or open `index.html` directly (a server is recommended so the Three.js ES module / import map resolves correctly).

## 📁 Structure

```
index.html                  # markup + section content
assets/
  css/style.css             # design system, layout, responsive rules
  js/
    main.js                 # GSAP intro, ScrollTrigger reveals, counters, nav, form
    three-scene.js          # Three.js particle-network background (ES module)
    vendor/                 # gsap, ScrollTrigger, three (vendored locally)
  img/
    success.svg             # feature-section illustration
    favicon.svg
```

## 🎨 Design system

| Token        | Value     | Use                          |
|--------------|-----------|------------------------------|
| Deep teal    | `#14323b` | Hero / dark sections         |
| Section blue | `#2b93b0` | "Path to Success" band       |
| Gold accent  | `#eb9b3e` | CTAs, highlights, the mark   |
| Ink          | `#16323a` | Body text on light           |

Headings use **Poppins**, body uses **Inter** (Google Fonts, with a `system-ui` fallback).

## ✅ Verification

Checked in headless Chromium across five viewports (1440 → 320 px):

- No console errors or page errors (the only network error in a sandbox is the optional Google Fonts request, which gracefully falls back to system fonts).
- No horizontal overflow at any breakpoint.
- GSAP loads, the preloader completes, scroll reveals fire, and metric counters reach their final values.
- Mobile hamburger drawer opens/closes and is keyboard-dismissible (Esc).

## ⚙️ Tech

- [GSAP 3.12](https://gsap.com/) + ScrollTrigger
- [Three.js r160](https://threejs.org/)
- Vanilla HTML/CSS/JS — no framework, no bundler
