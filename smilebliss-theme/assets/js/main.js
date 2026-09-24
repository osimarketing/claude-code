(function(){
  "use strict";
  var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---------- nav ---------- */
  var header = document.getElementById('siteHeader');
  var navToggle = document.getElementById('navToggle');
  var navPanel = document.getElementById('navPanel');
  var navClose = document.getElementById('navClose');
  if (header) {
    let onScroll = function(){ header.classList.toggle('solid', window.scrollY > 30); };
    window.addEventListener('scroll', onScroll, { passive:true });
    onScroll();
  }
  if (navToggle && navPanel) {
    let openNav = function(){ navPanel.classList.add('open'); navToggle.classList.add('open'); navToggle.setAttribute('aria-expanded','true'); document.body.style.overflow='hidden'; };
    let closeNav = function(){ navPanel.classList.remove('open'); navToggle.classList.remove('open'); navToggle.setAttribute('aria-expanded','false'); document.body.style.overflow=''; };
    navToggle.addEventListener('click', function(){ navPanel.classList.contains('open') ? closeNav() : openNav(); });
    if (navClose) navClose.addEventListener('click', closeNav);
    navPanel.querySelectorAll('a').forEach(function(a){ a.addEventListener('click', closeNav); });
    document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closeNav(); });
  }

  /* ---------- toggle pills ---------- */
  document.querySelectorAll('.toggle-group').forEach(function(group){
    group.querySelectorAll('.toggle-pill').forEach(function(pill){
      pill.addEventListener('click', function(){
        group.querySelectorAll('.toggle-pill').forEach(function(p){ p.classList.remove('active'); });
        pill.classList.add('active');
      });
    });
  });

  /* ---------- form submit ---------- */
  var form = document.getElementById('joinForm');
  var success = document.getElementById('formSuccess');
  if (form) {
    form.addEventListener('submit', function(e){
      // With no endpoint configured, keep the original behaviour: validate and
      // acknowledge without navigating, so nothing is silently dropped.
      if (!form.checkValidity()){ e.preventDefault(); form.reportValidity(); return; }
      if (!form.getAttribute('action')) {
        e.preventDefault();
        form.style.display = 'none';
        if (success) success.classList.add('show');
      }
    });
  }

  /* ---------- stat counters ---------- */
  function animateCount(el){
    var target = parseFloat(el.getAttribute('data-count'));
    var suffix = el.getAttribute('data-suffix') || '';
    var prefix = el.getAttribute('data-prefix') || '';
    if (reduceMotion) { el.textContent = prefix + target + suffix; return; }
    var dur = 1700, t0 = null;
    function step(now){
      if (t0 === null) t0 = now;
      var k = Math.min((now - t0) / dur, 1);
      var eased = 1 - Math.pow(1 - k, 3);
      var v = target % 1 === 0 ? Math.round(target * eased) : (target * eased).toFixed(1);
      el.textContent = prefix + v + suffix;
      if (k < 1) requestAnimationFrame(step);
      else el.textContent = prefix + target + suffix;
    }
    requestAnimationFrame(step);
  }

  /* ---------- scroll reveals, no library ----------
     These used GSAP + ScrollTrigger from a CDN. When that CDN did not answer,
     the reveals silently fell back to "everything visible" and the chart —
     which was Chart.js from the same CDN — rendered nothing at all. The page
     now carries its own animation, so nothing here depends on the network. */
  function onceInView(el, cb, ratio){
    if (!('IntersectionObserver' in window)) { cb(); return; }
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) { io.unobserve(e.target); cb(); }
      });
    }, { threshold: ratio || 0, rootMargin: '0px 0px -8% 0px' });
    io.observe(el);
  }

  function revealWith(el, delay){
    if (reduceMotion) { el.style.transition = 'none'; el.classList.add('is-in'); return; }
    el.style.transitionDelay = (delay || 0) + 'ms';
    el.classList.add('is-in');
  }

  document.querySelectorAll('.reveal').forEach(function (el) {
    onceInView(el, function () { revealWith(el, 0); });
  });

  [['.pillar', 80], ['.benefit', 70], ['.testi-card', 100]].forEach(function (pair) {
    var items = document.querySelectorAll(pair[0]);
    items.forEach(function (card, i) {
      card.classList.add('reveal-item');
      onceInView(card, function () { revealWith(card, (i % 5) * pair[1]); });
    });
  });

  document.querySelectorAll('.stat-card__num').forEach(function (el) {
    onceInView(el, function () { animateCount(el); });
  });

  /* checklist ticks draw in one by one */
  var checklist = document.querySelector('.checklist');
  if (checklist) {
    var checkItems = document.querySelectorAll('.check-item');
    checkItems.forEach(function (item) { item.classList.add('reveal-item'); });
    onceInView(checklist, function () {
      checkItems.forEach(function (item, i) {
        setTimeout(function () { revealWith(item, 0); }, reduceMotion ? 0 : i * 150);
      });
      ['.checklist__progress', '.checklist__closing'].forEach(function (sel, k) {
        var el = document.querySelector(sel);
        if (el) setTimeout(function () { revealWith(el, 0); },
                           reduceMotion ? 0 : checkItems.length * 150 + 150 + k * 100);
      });
    });

    var countEl = document.getElementById('checkCount');
    var barFill = document.querySelector('.checklist__bar-fill');
    var closing = document.querySelector('.checklist__closing');
    function syncChecklist(){
      var n = 0;
      checkItems.forEach(function (b) { if (b.getAttribute('aria-pressed') === 'true') n++; });
      if (countEl) countEl.textContent = n;
      if (barFill) barFill.style.width = (n / checkItems.length * 100) + '%';
      if (closing) closing.classList.toggle('is-complete', n === checkItems.length);
    }
    checkItems.forEach(function (b) {
      b.addEventListener('click', function () {
        var on = b.getAttribute('aria-pressed') === 'true';
        b.setAttribute('aria-pressed', on ? 'false' : 'true');
        b.classList.toggle('is-on', !on);
        syncChecklist();
      });
    });
    syncChecklist();
  }

  /* ---------- proforma chart, drawn here rather than by a library ----------
     chartData is the proforma of record; the table in the markup carries the
     same twelve figures for print, screen readers and JS-off.              */
  var chartData = {
    labels: ['Year 1','Year 2','Year 3'],
    base: [401012, 1805244, 3895784],
    baseStarts: [500, 1500, 2000],
    best: [721822, 2647921, 5146670],
    bestStarts: [900, 1950, 2400]
  };

  /* The template prints the proforma as JSON when the CMS holds it. Parsing is
     guarded so a malformed payload leaves the built-in figures standing rather
     than taking the chart down with it. */
  (function () {
    var node = document.getElementById('smilebliss-chart-data');
    if (!node) return;
    try {
      var data = JSON.parse(node.textContent);
      var keys = ['labels', 'base', 'baseStarts', 'best', 'bestStarts'];
      var ok = keys.every(function (k) {
        return Array.isArray(data[k]) && data[k].length === data.labels.length;
      });
      if (ok && data.labels.length) chartData = data;
    } catch (e) { /* keep the defaults */ }
  }());
  var SERIES = [
    { key:'base', startsKey:'baseStarts', name:'Base',  fill:'#054d66' },
    { key:'best', startsKey:'bestStarts', name:'Best',  fill:'#ff7d5c' }
  ];
  var SVGNS = 'http://www.w3.org/2000/svg';
  var shown = 'both';
  var chartDrawn = false;

  function money(v){ return '$' + Math.round(v).toLocaleString('en-US'); }
  function shortMoney(v){ return v < 1e6 ? '$' + Math.round(v/1e3) + 'K' : '$' + (v/1e6).toFixed(1) + 'M'; }
  function svgEl(name, attrs){
    var n = document.createElementNS(SVGNS, name);
    for (var k in attrs) n.setAttribute(k, attrs[k]);
    return n;
  }
  /* a bar with only its top corners rounded, matching the original look */
  function barPath(x, y, w, hgt, r){
    r = Math.min(r, w / 2, hgt);
    return 'M' + x + ',' + (y + hgt) +
           'V' + (y + r) + 'a' + r + ',' + r + ' 0 0 1 ' + r + ',' + (-r) +
           'h' + (w - 2*r) + 'a' + r + ',' + r + ' 0 0 1 ' + r + ',' + r +
           'V' + (y + hgt) + 'Z';
  }

  var DESCRIBE = 'Projected revenue by year. Base scenario $401,012 in year one rising to ' +
                 '$3,895,784 in year three. Best scenario $721,822 rising to $5,146,670. ' +
                 'Every figure is in the table below.';
  var MAX = 5500000;
  var TICKS = [0, 1000000, 2000000, 3000000, 4000000, 5000000];
  var GRID = 'rgba(5,77,102,.10)';
  var MUTED = 'rgba(5,77,102,.66)';

  function chartSvg(W, H){
    var s = svgEl('svg', {
      viewBox: '0 0 ' + W + ' ' + H, width: '100%', height: H,
      preserveAspectRatio: 'none', role: 'img', 'aria-label': DESCRIBE
    });
    return s;
  }

  /* wide: grouped vertical columns */
  function buildVertical(W, H, active){
    var padL = 66, padR = 14, padT = 34, padB = 46;
    var plotW = W - padL - padR, plotH = H - padT - padB;
    function y(v){ return padT + plotH - (v / MAX) * plotH; }
    var svg = chartSvg(W, H);

    TICKS.forEach(function (t) {
      svg.appendChild(svgEl('line', { x1: padL, x2: W - padR, y1: y(t), y2: y(t),
        stroke: GRID, 'stroke-width': 1 }));
      var lab = svgEl('text', { x: padL - 12, y: y(t) + 5, fill: MUTED, 'text-anchor': 'end',
        'font-family': 'Inter, system-ui, sans-serif', 'font-size': 15 });
      lab.textContent = t === 0 ? '$0' : '$' + (t / 1e6) + 'M';
      svg.appendChild(lab);
    });

    var groupW = plotW / 3, gap = 16;
    var barW = Math.max(16, Math.min(active.length === 1 ? 108 : 78,
                        (groupW * 0.62 - (active.length - 1) * gap) / active.length));

    chartData.labels.forEach(function (label, i) {
      var cx = padL + groupW * i + groupW / 2;
      var total = active.length * barW + (active.length - 1) * gap;
      var x0 = cx - total / 2;

      active.forEach(function (s, k) {
        var v = chartData[s.key][i];
        var x = x0 + k * (barW + gap);
        var top = y(v), hgt = padT + plotH - top;
        var g = svgEl('g', {});

        var bar = svgEl('path', { d: barPath(x, reduceMotion ? top : padT + plotH, barW,
                                             reduceMotion ? hgt : 0, 8), fill: s.fill });
        g.appendChild(bar);

        var val = svgEl('text', { x: x + barW / 2, y: top - 12, fill: '#054d66', 'text-anchor': 'middle',
          'font-family': '"Plus Jakarta Sans", system-ui, sans-serif', 'font-size': 17, 'font-weight': 700 });
        val.textContent = shortMoney(v);
        val.style.opacity = reduceMotion ? 1 : 0;
        g.appendChild(val);

        g.appendChild(hitArea(x, padT, barW, plotH, label, s, i));

        if (!reduceMotion) {
          requestAnimationFrame(function () {
            var d = (i * 110) + (k * 60);
            bar.animate(
              [{ d: 'path("' + barPath(x, padT + plotH, barW, 0, 8) + '")' },
               { d: 'path("' + barPath(x, top, barW, hgt, 8) + '")' }],
              { duration: 900, delay: d, easing: 'cubic-bezier(.2,.8,.2,1)', fill: 'forwards' }
            );
            bar.setAttribute('d', barPath(x, top, barW, hgt, 8));
            val.style.transition = 'opacity .4s ease ' + (d + 520) + 'ms';
            val.style.opacity = 1;
          });
        }
        svg.appendChild(g);
      });

      var xlab = svgEl('text', { x: cx, y: H - 16, fill: '#054d66', 'text-anchor': 'middle',
        'font-family': '"Plus Jakarta Sans", system-ui, sans-serif', 'font-size': 16, 'font-weight': 700 });
      xlab.textContent = label;
      svg.appendChild(xlab);
    });
    return svg;
  }

  /* narrow: the same data as rows, so nothing is shrunk past reading size */
  function buildHorizontal(W, active){
    var headH = 18, fLab = 12.5, gapLB = 5, barH = 16, gapS = 10, groupGap = 18, axisH = 24;
    var n = active.length;
    var groupH = headH + n * (fLab + gapLB + barH) + (n - 1) * gapS;
    var H = Math.round(3 * groupH + 2 * groupGap + axisH);
    var len = W - 2;
    var plotBottom = H - axisH + 4;
    var svg = chartSvg(W, H);

    TICKS.forEach(function (t) {
      var x = (t / MAX) * len;
      svg.appendChild(svgEl('line', { x1: x, x2: x, y1: 0, y2: plotBottom,
        stroke: GRID, 'stroke-width': 1 }));
      var lab = svgEl('text', { x: x, y: H - 6, fill: MUTED,
        'text-anchor': t === 0 ? 'start' : (t === 5000000 ? 'end' : 'middle'),
        'font-family': 'Inter, system-ui, sans-serif', 'font-size': 10.5 });
      lab.textContent = t === 0 ? '$0' : '$' + (t / 1e6) + 'M';
      svg.appendChild(lab);
    });

    var cursor = 0;
    chartData.labels.forEach(function (label, i) {
      var head = svgEl('text', { x: 0, y: cursor + 12, fill: MUTED,
        'font-family': '"Plus Jakarta Sans", system-ui, sans-serif', 'font-size': 11,
        'font-weight': 700, 'letter-spacing': '.09em' });
      head.textContent = label.toUpperCase();
      svg.appendChild(head);
      cursor += headH;

      active.forEach(function (s, k) {
        var v = chartData[s.key][i];
        var w = Math.max(3, (v / MAX) * len);
        var g = svgEl('g', {});

        var txt = svgEl('text', { x: 0, y: cursor + fLab, fill: '#054d66',
          'font-family': '"Plus Jakarta Sans", system-ui, sans-serif', 'font-size': fLab });
        var nm = svgEl('tspan', { 'font-weight': 700 });
        nm.textContent = s.name + ' · ';
        txt.appendChild(nm);
        txt.appendChild(document.createTextNode(money(v)));
        g.appendChild(txt);

        var st = svgEl('text', { x: len, y: cursor + fLab, fill: MUTED, 'text-anchor': 'end',
          'font-family': 'Inter, system-ui, sans-serif', 'font-size': fLab - 1.5 });
        st.textContent = chartData[s.startsKey][i].toLocaleString('en-US') + ' starts';
        g.appendChild(st);

        var by = cursor + fLab + gapLB;
        var bar = svgEl('path', {
          d: rowPath(reduceMotion ? w : 3, by, barH), fill: s.fill });
        g.appendChild(bar);
        g.appendChild(hitArea(0, cursor, len, fLab + gapLB + barH, label, s, i));

        if (!reduceMotion) {
          requestAnimationFrame(function () {
            var d = (i * 110) + (k * 60);
            bar.animate(
              [{ d: 'path("' + rowPath(3, by, barH) + '")' },
               { d: 'path("' + rowPath(w, by, barH) + '")' }],
              { duration: 900, delay: d, easing: 'cubic-bezier(.2,.8,.2,1)', fill: 'forwards' }
            );
            bar.setAttribute('d', rowPath(w, by, barH));
          });
        }
        svg.appendChild(g);
        cursor += fLab + gapLB + barH + (k < n - 1 ? gapS : 0);
      });
      cursor += groupGap;
    });
    return svg;
  }

  /* a row bar: square at the axis, rounded at the growing end */
  function rowPath(w, y, h){
    var r = Math.min(5, w / 2, h / 2);
    return 'M0,' + y + 'H' + (w - r) + 'a' + r + ',' + r + ' 0 0 1 ' + r + ',' + r +
           'V' + (y + h - r) + 'a' + r + ',' + r + ' 0 0 1 ' + (-r) + ',' + r + 'H0Z';
  }

  function hitArea(x, y, w, h, label, s, i){
    var v = chartData[s.key][i], starts = chartData[s.startsKey][i], series = chartData[s.key];
    var hit = svgEl('rect', { x: x, y: y, width: w, height: h, fill: 'transparent' });
    hit.addEventListener('pointerenter', function (e) {
      var lines = [money(v), starts.toLocaleString('en-US') + ' patient starts'];
      if (i > 0) lines.push('+' + Math.round((series[i] / series[i-1] - 1) * 100) + '% on year ' + i);
      showTip(e, label + ' · ' + s.name + ' scenario', lines);
    });
    hit.addEventListener('pointermove', moveTip);
    hit.addEventListener('pointerleave', hideTip);
    return hit;
  }

  function drawChart(){
    var host = document.getElementById('revenueChart');
    if (!host) return;
    host.textContent = '';
    var W = Math.round(host.clientWidth) || 1000;
    var narrow = W < 620;
    var card = host.closest ? host.closest('.revenue__card') : null;
    if (card) card.classList.toggle('is-rows', narrow);
    var active = SERIES.filter(function (s) { return shown === 'both' || shown === s.key; });
    host.appendChild(narrow ? buildHorizontal(W, active)
                            : buildVertical(W, Math.round(host.clientHeight) || 420, active));
    chartDrawn = true;
  }

  /* tooltip */
  var tip = document.createElement('div');
  tip.className = 'chart-tip';
  document.body.appendChild(tip);
  function showTip(e, head, lines){
    tip.textContent = '';
    var h1 = document.createElement('strong'); h1.textContent = head; tip.appendChild(h1);
    lines.forEach(function (l) { var d = document.createElement('div'); d.textContent = l; tip.appendChild(d); });
    tip.classList.add('is-on');
    moveTip(e);
  }
  function moveTip(e){ tip.style.left = e.clientX + 'px'; tip.style.top = e.clientY + 'px'; }
  function hideTip(){ tip.classList.remove('is-on'); }

  /* ---------- mobile CTA bar: only once the hero is out of the way ---------- */
  var stickyCta = document.querySelector('.sticky-cta');
  var heroEl = document.getElementById('top');
  if (stickyCta && heroEl && 'IntersectionObserver' in window) {
    new IntersectionObserver(function (entries) {
      stickyCta.classList.toggle('is-on', !entries[0].isIntersecting);
    }, { threshold: 0 }).observe(heroEl);
  } else if (stickyCta) {
    stickyCta.classList.add('is-on');
  }

  var revenueSection = document.getElementById('revenue');
  if (revenueSection) onceInView(revenueSection, drawChart, 0.15);

  document.querySelectorAll('.scenario__btn').forEach(function (b) {
    b.addEventListener('click', function () {
      shown = b.dataset.scenario;
      document.querySelectorAll('.scenario__btn').forEach(function (o) {
        o.classList.toggle('is-on', o === b);
        o.setAttribute('aria-pressed', o === b ? 'true' : 'false');
      });
      if (chartDrawn) drawChart();
    });
  });

  var redrawTimer;
  window.addEventListener('resize', function () {
    if (!chartDrawn) return;
    clearTimeout(redrawTimer);
    redrawTimer = setTimeout(drawChart, 200);
  });


})();
