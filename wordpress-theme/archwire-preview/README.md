# Archwire preview

Renders the theme's real templates with sample practice data, without
WordPress, so designers can review layout changes. `stubs.php` stands in for
the handful of WordPress and ACF functions the templates call.

```bash
./build.sh
python3 -m http.server 8765 --directory dist
```

- `render.php`: sample practice (Northline Orthodontics, Round Rock, TX) + starter sections
- `edge.php`: renders every section with all fields empty, to catch PHP notices
- `make-images.py`: preview-only images (photo slots, before/after illustrations, map)

This is a design harness, not a WordPress test. Validate on a staging install too.

`photos/` holds sample stock photos supplied for the preview only (cropped to
each slot by `crop-photos.py`). They are not part of the theme; client sites
use their own photos.
