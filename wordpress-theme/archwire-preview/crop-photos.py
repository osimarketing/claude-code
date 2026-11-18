"""Crop the sample stock photos in photos/ to each preview slot.

Each crop: source, output, aspect (w/h), focal point (fx, fy as 0-1),
zoom (1 = largest crop that fits), output width.
"""
import os, sys
from PIL import Image

out = sys.argv[1]
os.makedirs(out, exist_ok=True)

CROPS = [
    ('clinician-patient.jpg', 'hero.jpg', 4 / 5, 0.55, 0.45, 1.0, 960),
    ('orthodontist-portrait.jpg', 'doctor.jpg', 4 / 5, 0.6, 0.4, 0.92, 768),
    ('model-color-braces.jpg', 't-braces.jpg', 4 / 3, 0.45, 0.5, 0.9, 720),
    ('model-clear-braces.jpg', 't-clear.jpg', 4 / 3, 0.36, 0.55, 0.85, 720),
    ('aligner-smile.jpg', 't-invisalign.jpg', 4 / 3, 0.5, 0.45, 0.9, 720),
    ('office-operatory.jpg', 't-early.jpg', 4 / 3, 0.45, 0.5, 1.0, 720),
    ('blog-teen-braces-meal.jpg', 'p1.jpg', 4 / 3, 0.5, 0.42, 1.0, 720),
    ('blog-child-first-visit.jpg', 'p2.jpg', 4 / 3, 0.5, 0.45, 1.0, 720),
    ('blog-invisalign-consult.jpg', 'p3.jpg', 4 / 3, 0.42, 0.5, 1.0, 720),
]

here = os.path.dirname(os.path.abspath(__file__))
for src, dst, aspect, fx, fy, zoom, width in CROPS:
    im = Image.open(os.path.join(here, 'photos', src)).convert('RGB')
    W, H = im.size
    cw, ch = (H * aspect, H) if W / H > aspect else (W, W / aspect)
    cw, ch = cw * zoom, ch * zoom
    x = min(max(fx * W - cw / 2, 0), W - cw)
    y = min(max(fy * H - ch / 2, 0), H - ch)
    im = im.crop((round(x), round(y), round(x + cw), round(y + ch)))
    im = im.resize((width, round(width / aspect)), Image.LANCZOS)
    im.save(os.path.join(out, dst), quality=80, optimize=True, progressive=True)
print('photos ok')
