"""Cross-check the PHP templates against the ACF field groups.

Catches the failure this scaffold is most prone to: a partial reading a field
name that no field actually defines, which renders as silent emptiness.
"""
import json, re, glob, os, sys

sections = json.load(open('acf-json/group_smilebliss_sections.json'))
settings = json.load(open('acf-json/group_smilebliss_settings.json'))

def names(fields):
    """Every field name reachable inside a layout, repeaters included."""
    out = set()
    for f in fields:
        if f.get('name'):
            out.add(f['name'])
        for key in ('sub_fields', 'layouts'):
            sub = f.get(key)
            if isinstance(sub, list):
                out |= names(sub)
            elif isinstance(sub, dict):
                for v in sub.values():
                    out |= names(v.get('sub_fields', []))
    return out

layouts = sections['fields'][0]['layouts']
by_layout = {l['name']: names(l['sub_fields']) for l in layouts.values()}
option_names = names(settings['fields'])

USE = re.compile(r"get_sub_field\(\s*'([a-z0-9_]+)'|have_rows\(\s*'([a-z0-9_]+)'")
OPT = re.compile(r"smilebliss_option\(\s*'([a-z0-9_]+)'")

fail = []

# every partial's reads must resolve inside its own layout
for path in sorted(glob.glob('template-parts/flexible/*.php')):
    layout = os.path.splitext(os.path.basename(path))[0]
    if layout not in by_layout:
        fail.append(f'{path}: no ACF layout named "{layout}"')
        continue
    src = open(path, encoding='utf-8').read()
    used = {m[0] or m[1] for m in USE.findall(src)} - {'anchor'}
    missing = sorted(used - by_layout[layout])
    if missing:
        fail.append(f'{path}: reads fields not in layout "{layout}": {missing}')

# every layout must have a partial to render it
for layout in by_layout:
    if not os.path.exists(f'template-parts/flexible/{layout}.php'):
        fail.append(f'layout "{layout}" has no template-parts/flexible/{layout}.php')

# option reads must resolve in the settings group
for path in ['header.php', 'footer.php'] + glob.glob('template-parts/flexible/*.php') + glob.glob('inc/*.php'):
    src = open(path, encoding='utf-8').read()
    for name in set(OPT.findall(src)):
        if name not in option_names:
            fail.append(f'{path}: option "{name}" is not defined in the settings group')

# anchor must exist in every layout, since every partial calls smilebliss_section_id()
for layout, fields in by_layout.items():
    if 'anchor' not in fields:
        fail.append(f'layout "{layout}" is missing the anchor field')

# the starter payload must use real field names too
starter = open('inc/starter-sections.php', encoding='utf-8').read()
for chunk in re.split(r"'acf_fc_layout' => '", starter)[1:]:
    layout = chunk.split("'", 1)[0]
    if layout not in by_layout:
        fail.append(f'starter content uses unknown layout "{layout}"')
        continue
    body = chunk.split("'acf_fc_layout'")[0]
    keys = set(re.findall(r"'([a-z0-9_]+)'\s*=>", body)) - {'title', 'url', 'target'}
    unknown = sorted(keys - by_layout[layout] - {'acf_fc_layout'})
    if unknown:
        fail.append(f'starter "{layout}" sets unknown fields: {unknown}')

print(f'{len(by_layout)} layouts, {sum(len(v) for v in by_layout.values())} field names, {len(option_names)} options')
for layout in sorted(by_layout):
    print(f'  {layout:14} {len(by_layout[layout]):2d} fields')
if fail:
    print('\nFAILURES:')
    for f in fail:
        print('  -', f)
    sys.exit(1)
print('\nOK: every field read resolves, every layout has a partial.')
