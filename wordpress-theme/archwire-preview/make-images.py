"""Draw the preview-only images: photo slots, before/after teeth, a map.

Real client sites use real photos. These exist so the preview shows the
layout honestly: slots say what photo goes there.
"""
import math, os, random, sys

out = sys.argv[1]
os.makedirs(out, exist_ok=True)

def save(name, svg):
    open(os.path.join(out, name), "w").write(svg)

def slot(name, w, h, caption):
    save(name, f'''<svg xmlns="http://www.w3.org/2000/svg" width="{w}" height="{h}" viewBox="0 0 {w} {h}">
<defs><linearGradient id="g" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#E6EEF3"/><stop offset="1" stop-color="#D6E3EB"/></linearGradient></defs>
<rect width="{w}" height="{h}" fill="url(#g)"/>
<g fill="none" stroke="#B9CBD6" stroke-width="2" transform="translate({w/2-28},{h/2-40})">
<rect x="0" y="12" width="56" height="40" rx="8"/><circle cx="28" cy="32" r="11"/><path d="M18 12l4-8h12l4 8"/></g>
<text x="{w/2}" y="{h/2+44}" text-anchor="middle" font-family="Figtree, system-ui, sans-serif" font-size="{max(14, w//34)}" fill="#57717D">{caption}</text>
</svg>''')

def tooth_path(x, y, w, h):
    r = w * 0.42
    return (f'M{x},{y} L{x+w},{y} L{x+w},{y+h-r} Q{x+w},{y+h} {x+w-r},{y+h} '
            f'L{x+r},{y+h} Q{x},{y+h} {x},{y+h-r} Z')

def mouth(name, seed, crooked, gap=False):
    random.seed(seed)
    W, H = 720, 540
    widths = [44, 50, 54, 70, 70, 54, 50, 44]
    heights = [92, 100, 112, 124, 124, 112, 100, 92]
    total = sum(widths) + 7 * 3
    x = (W - total) / 2
    parts = []
    lower = []
    # lower teeth (behind)
    lx = (W - (8 * 46 + 7 * 3)) / 2
    for i in range(8):
        dy = random.uniform(-8, 8) if crooked else 0
        rot = random.uniform(-10, 10) if crooked else 0
        cx = lx + 23
        lower.append(f'<path d="{tooth_path(lx, 0, 46, 84)}" transform="translate(0,{318+dy}) rotate({rot} {cx} 40) scale(1,-1) translate(0,-84)" fill="#F1ECE2" stroke="#D9CFBE" stroke-width="2"/>')
        lx += 49
    for i, (tw, th) in enumerate(zip(widths, heights)):
        center_offset = abs(i - 3.5)
        arc = (center_offset ** 2) * 3.2
        dy = arc
        rot = 0
        dx = 0
        if crooked:
            dy += random.uniform(-14, 14)
            rot = random.uniform(-12, 12)
            dx = random.uniform(-6, 6)
            if i == 2 or i == 5:
                dy -= 18
        if gap and i == 4:
            x += 18
        cx = x + tw / 2
        parts.append(f'<path d="{tooth_path(x + dx, 150 + dy, tw, th)}" transform="rotate({rot:.1f} {cx:.1f} {200+dy:.1f})" fill="#FBF8F1" stroke="#DDD3C2" stroke-width="2"/>')
        x += tw + 3
    save(name, f'''<svg xmlns="http://www.w3.org/2000/svg" width="{W}" height="{H}" viewBox="0 0 {W} {H}">
<rect width="{W}" height="{H}" fill="#E9D7D2"/>
<ellipse cx="{W/2}" cy="270" rx="300" ry="170" fill="#7A3B3F"/>
<path d="M60 180 Q360 120 660 180 L660 175 Q360 90 60 175Z" fill="#C56B70"/>
<path d="M90 160 Q360 120 630 160 L630 210 Q360 175 90 210Z" fill="#E28F93"/>
{''.join(lower)}
{''.join(parts)}
<path d="M40 150 Q360 40 680 150 Q360 105 40 150Z" fill="#C9766F"/>
<path d="M40 395 Q360 520 680 395 Q360 440 40 395Z" fill="#C9766F"/>
</svg>''')

def map_svg(name):
    W, H = 800, 600
    save(name, f'''<!DOCTYPE html><html><head><meta charset="utf-8"><style>html,body{{margin:0;height:100%;background:#E8EEF2}}svg{{width:100%;height:100%;display:block}}</style></head><body>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {W} {H}" preserveAspectRatio="xMidYMid slice">
<rect width="{W}" height="{H}" fill="#E8EEF2"/>
<path d="M520 0 Q560 200 700 260 L800 280 L800 0Z" fill="#DCE9E4"/>
<path d="M0 470 Q200 430 260 600 L0 600Z" fill="#DCE9E4"/>
<g stroke="#FFFFFF" stroke-linecap="round" fill="none">
<path d="M0 320 L800 250" stroke-width="18"/>
<path d="M330 0 Q360 300 300 600" stroke-width="14"/>
<path d="M0 140 L800 110" stroke-width="8"/>
<path d="M0 520 L800 470" stroke-width="8"/>
<path d="M150 0 L170 600" stroke-width="8"/>
<path d="M560 0 L600 600" stroke-width="8"/>
<path d="M700 0 L720 600" stroke-width="6"/>
<path d="M440 0 L470 600" stroke-width="6"/>
<path d="M0 410 L800 360" stroke-width="6"/>
<path d="M0 220 L800 185" stroke-width="6"/>
</g>
<text x="40" y="306" font-family="Figtree, system-ui, sans-serif" font-size="15" fill="#7C929D">Hillcrest Ave</text>
<text x="344" y="560" font-family="Figtree, system-ui, sans-serif" font-size="15" fill="#7C929D" transform="rotate(-80 344 560)">Mays St</text>
<g transform="translate(470 252)">
<path d="M0 -44 C-20 -44 -30 -28 -30 -16 C-30 6 0 28 0 28 C0 28 30 6 30 -16 C30 -28 20 -44 0 -44Z" fill="#237086"/>
<circle cx="0" cy="-16" r="10" fill="#fff"/>
</g>
<rect x="508" y="216" width="214" height="40" rx="8" fill="#fff"/>
<text x="524" y="241" font-family="Figtree, system-ui, sans-serif" font-size="16" font-weight="500" fill="#0F2730">Northline Orthodontics</text>
</svg></body></html>''')

slot("hero.svg", 960, 1200, "Photo: patient smiling in the chair, window light")
slot("doctor.svg", 960, 1200, "Photo: orthodontist portrait in the office")
slot("t-braces.svg", 720, 540, "Photo: close-up, metal braces")
slot("t-clear.svg", 720, 540, "Photo: close-up, clear braces")
slot("t-invisalign.svg", 720, 540, "Photo: aligner held up to the light")
slot("t-early.svg", 720, 540, "Photo: child at a first visit")
slot("p1.svg", 720, 540, "Article image")
slot("p2.svg", 720, 540, "Article image")
slot("p3.svg", 720, 540, "Article image")
map_svg("map.html")
print("images ok")
