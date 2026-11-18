/**
 * Archwire front-end. No dependencies, loaded with defer.
 *
 * 1. Mobile menu
 * 2. Today's row in the hours table (computed in the practice's time
 *    zone in the browser, so cached pages stay correct)
 * 3. Before/after compare sliders
 */
(function () {
	'use strict';

	var root = document.documentElement;
	var body = document.body;

	/* 1. Mobile menu ------------------------------------------------------ */
	var toggle = document.getElementById('navToggle');
	var nav = document.getElementById('siteNav');

	function setMenu(open) {
		body.classList.toggle('nav-open', open);
		toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
	}

	if (toggle && nav) {
		toggle.addEventListener('click', function () {
			setMenu(!body.classList.contains('nav-open'));
		});
		nav.addEventListener('click', function (e) {
			if (e.target.closest('a')) {
				setMenu(false);
			}
		});
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && body.classList.contains('nav-open')) {
				setMenu(false);
				toggle.focus();
			}
		});
		window.matchMedia('(min-width: 1024px)').addEventListener('change', function (mq) {
			if (mq.matches) {
				setMenu(false);
			}
		});
	}

	/* 2. Today's hours ------------------------------------------------------------ */
	var data = window.archwireHours;

	function nowInZone(tz) {
		try {
			var parts = new Intl.DateTimeFormat('en-US', {
				timeZone: tz || undefined,
				weekday: 'short',
				hour: '2-digit',
				minute: '2-digit',
				hourCycle: 'h23'
			}).formatToParts(new Date());
			var map = {};
			parts.forEach(function (p) { map[p.type] = p.value; });
			var days = { Mon: 1, Tue: 2, Wed: 3, Thu: 4, Fri: 5, Sat: 6, Sun: 7 };
			return { day: days[map.weekday], minutes: parseInt(map.hour, 10) * 60 + parseInt(map.minute, 10) };
		} catch (err) {
			var d = new Date();
			return { day: d.getDay() || 7, minutes: d.getHours() * 60 + d.getMinutes() };
		}
	}

	if (data && data.hours && Object.keys(data.hours).length) {
		var now = nowInZone(data.tz);

		document.querySelectorAll('[data-hours-table] tr[data-day="' + now.day + '"]').forEach(function (row) {
			row.classList.add('is-today');
			var th = row.querySelector('th');
			if (th) {
				th.setAttribute('aria-label', th.textContent + ', today');
			}
		});

	}

	/* 3. Compare sliders -------------------------------------------------- */
	document.querySelectorAll('[data-compare]').forEach(function (el) {
		var range = el.querySelector('.compare__range');
		if (!range) {
			return;
		}
		range.hidden = false;
		el.classList.add('is-ready');
		function update() {
			el.style.setProperty('--pos', range.value + '%');
		}
		range.addEventListener('input', update);
		range.addEventListener('focus', function () { el.classList.add('has-focus'); });
		range.addEventListener('blur', function () { el.classList.remove('has-focus'); });
		update();
	});

	root.classList.add('js');
})();
