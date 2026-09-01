/* =========================================================
   PatientPath — Consulting homepage interactions
   ========================================================= */
(function () {
  "use strict";
  const reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  const hasGSAP = typeof window.gsap !== "undefined";

  /* year */
  const yearEl = document.getElementById("year");
  if (yearEl) yearEl.textContent = new Date().getFullYear();

  /* sticky header + hero canvas fade + scroll progress */
  const header = document.getElementById("siteHeader");
  const canvas = document.getElementById("bg-canvas");
  const progress = document.getElementById("scrollProgress");
  const onScroll = () => {
    const y = window.scrollY;
    header.classList.toggle("is-stuck", y > 30);
    if (canvas) canvas.style.opacity = Math.max(0, 1 - y / (window.innerHeight * 0.8));
    if (progress) {
      const h = document.documentElement;
      progress.style.width = (h.scrollTop / (h.scrollHeight - h.clientHeight) * 100) + "%";
    }
  };
  onScroll();
  window.addEventListener("scroll", onScroll, { passive: true });

  /* mobile nav */
  const toggle = document.getElementById("navToggle");
  const navLinks = document.getElementById("navLinks");
  if (toggle && navLinks) {
    const close = () => {
      toggle.classList.remove("is-open"); navLinks.classList.remove("is-open");
      toggle.setAttribute("aria-expanded", "false"); toggle.setAttribute("aria-label", "Open menu");
    };
    toggle.addEventListener("click", () => {
      const open = navLinks.classList.toggle("is-open");
      toggle.classList.toggle("is-open", open);
      toggle.setAttribute("aria-expanded", String(open));
      toggle.setAttribute("aria-label", open ? "Close menu" : "Open menu");
    });
    navLinks.querySelectorAll("a").forEach((a) => a.addEventListener("click", close));
    document.addEventListener("keydown", (e) => { if (e.key === "Escape") close(); });
  }

  /* hero intro */
  function heroIntro() {
    if (!hasGSAP || reduced) {
      document.querySelectorAll(".hero .reveal, .hero__title .line > span")
        .forEach((el) => { el.style.opacity = 1; el.style.transform = "none"; });
      return;
    }
    const tl = gsap.timeline({ defaults: { ease: "power3.out" } });
    tl.from(".hero__title .line > span", { yPercent: 115, opacity: 0, duration: 1, stagger: 0.12 })
      .to(".hero__eyebrow", { opacity: 1, y: 0, duration: 0.7 }, "-=0.8")
      .to(".hero__lead", { opacity: 1, y: 0, duration: 0.7 }, "-=0.55")
      .to(".hero__actions", { opacity: 1, y: 0, duration: 0.7 }, "-=0.55")
      .to(".hero__trust", { opacity: 1, y: 0, duration: 0.7 }, "-=0.5");
  }
  window.addEventListener("load", heroIntro);
  setTimeout(heroIntro, 1200); // fallback if load already fired

  /* scroll reveals */
  if (hasGSAP && window.ScrollTrigger && !reduced) {
    gsap.registerPlugin(ScrollTrigger);
    gsap.utils.toArray(".reveal").forEach((el) => {
      if (el.closest(".hero")) return;
      ScrollTrigger.create({ trigger: el, start: "top 88%", once: true, onEnter: () => el.classList.add("is-inview") });
    });
    // stagger groups
    [".levers", ".steps", ".industries__list", ".proof__stats", ".posts"].forEach((sel) => {
      const group = document.querySelector(sel);
      if (!group) return;
      const kids = group.children;
      ScrollTrigger.create({
        trigger: group, start: "top 82%", once: true,
        onEnter: () => gsap.from(kids, { y: 30, opacity: 0, duration: 0.7, stagger: 0.1, ease: "power3.out", clearProps: "all" }),
      });
    });
  } else {
    document.querySelectorAll(".reveal").forEach((el) => el.classList.add("is-inview"));
  }

  /* counters */
  const animateCount = (el) => {
    const target = parseFloat(el.dataset.count);
    const dec = parseInt(el.dataset.decimals || "0", 10);
    const pre = el.dataset.prefix || "", suf = el.dataset.suffix || "";
    const dur = 1500, start = performance.now();
    const step = (now) => {
      const p = Math.min((now - start) / dur, 1);
      const v = (target * (1 - Math.pow(1 - p, 3))).toFixed(dec);
      el.textContent = pre + v + suf;
      if (p < 1) requestAnimationFrame(step); else el.textContent = pre + target.toFixed(dec) + suf;
    };
    requestAnimationFrame(step);
  };
  if ("IntersectionObserver" in window) {
    const io = new IntersectionObserver((entries, obs) => {
      entries.forEach((e) => { if (e.isIntersecting) { animateCount(e.target); obs.unobserve(e.target); } });
    }, { threshold: 0.6 });
    document.querySelectorAll("[data-count]").forEach((c) => io.observe(c));
  } else {
    document.querySelectorAll("[data-count]").forEach(animateCount);
  }

  /* Problems We Solve — tabs (desktop) / accordion (mobile) */
  const psItems = Array.from(document.querySelectorAll(".psolve__item"));
  const psCounted = new Set();
  function selectProblem(i) {
    psItems.forEach((it, k) => {
      const active = k === i;
      it.classList.toggle("is-active", active);
      const head = it.querySelector(".psolve__head");
      if (head) head.setAttribute("aria-expanded", String(active));
      if (active) {
        const inner = it.querySelector(".psolve__panel-inner");
        if (hasGSAP && !reduced && inner) gsap.fromTo(inner, { opacity: 0 }, { opacity: 1, duration: 0.45, ease: "power2.out" });
        // run the outcome counter the first time this panel opens
        const num = it.querySelector("[data-count]");
        if (num && !psCounted.has(num)) { psCounted.add(num); animateCount(num); }
      }
    });
  }
  psItems.forEach((it) => {
    const head = it.querySelector(".psolve__head");
    const i = parseInt(it.dataset.i, 10);
    if (!head) return;
    head.addEventListener("click", () => selectProblem(i));
    head.addEventListener("keydown", (e) => {
      if (e.key === "ArrowDown" || e.key === "ArrowRight") { e.preventDefault(); const n = (i + 1) % psItems.length; psItems[n].querySelector(".psolve__head").focus(); selectProblem(n); }
      if (e.key === "ArrowUp" || e.key === "ArrowLeft") { e.preventDefault(); const n = (i - 1 + psItems.length) % psItems.length; psItems[n].querySelector(".psolve__head").focus(); selectProblem(n); }
    });
  });

  /* CTA form */
  const form = document.getElementById("ctaForm");
  const note = document.getElementById("ctaNote");
  if (form) {
    form.addEventListener("submit", (e) => {
      e.preventDefault();
      const data = new FormData(form);
      const email = (data.get("email") || "").toString().trim();
      const name = (data.get("name") || "").toString().trim();
      const valid = name && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
      if (!valid) {
        note.textContent = "Please enter your name and a valid work email.";
        note.classList.remove("is-success");
        if (hasGSAP) gsap.fromTo(form, { x: -6 }, { x: 0, duration: 0.4, ease: "elastic.out(1,0.3)" });
        return;
      }
      note.textContent = `Thanks, ${name.split(" ")[0]} — we'll be in touch within one business day.`;
      note.classList.add("is-success");
      form.reset();
      if (hasGSAP) gsap.fromTo(note, { scale: 0.92, opacity: 0.4 }, { scale: 1, opacity: 1, duration: 0.5, ease: "back.out(2)" });
    });
  }

  /* smooth scroll with header offset */
  document.querySelectorAll('a[href^="#"]').forEach((link) => {
    link.addEventListener("click", (e) => {
      const id = link.getAttribute("href");
      if (id.length < 2) return;
      const target = document.querySelector(id);
      if (!target) return;
      e.preventDefault();
      const top = target.getBoundingClientRect().top + window.scrollY - 64;
      window.scrollTo({ top, behavior: reduced ? "auto" : "smooth" });
    });
  });
})();
