/* =========================================================
   PatientPath — GSAP animations + UI interactions
   ========================================================= */
(function () {
  "use strict";

  const prefersReduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  const hasGSAP = typeof window.gsap !== "undefined";

  /* ---------- Footer year ---------- */
  const yearEl = document.getElementById("year");
  if (yearEl) yearEl.textContent = new Date().getFullYear();

  /* ---------- Preloader ---------- */
  const preloader = document.getElementById("preloader");
  const bar = preloader ? preloader.querySelector(".preloader__bar span") : null;
  let progress = 0;
  if (bar) {
    const tick = setInterval(() => {
      progress = Math.min(progress + Math.random() * 22, 100);
      bar.style.width = progress + "%";
      if (progress >= 100) clearInterval(tick);
    }, 120);
  }
  function hidePreloader() {
    if (bar) bar.style.width = "100%";
    setTimeout(() => preloader && preloader.classList.add("is-done"), 250);
    startHeroIntro();
  }
  window.addEventListener("load", () => setTimeout(hidePreloader, 400));
  // safety fallback
  setTimeout(() => { if (preloader && !preloader.classList.contains("is-done")) hidePreloader(); }, 2600);

  /* ---------- Sticky header ---------- */
  const header = document.getElementById("siteHeader");
  const onScrollHeader = () => {
    if (window.scrollY > 30) header.classList.add("is-stuck");
    else header.classList.remove("is-stuck");
  };
  onScrollHeader();
  window.addEventListener("scroll", onScrollHeader, { passive: true });

  /* ---------- Scroll progress bar ---------- */
  const progressBar = document.getElementById("scrollProgress");
  window.addEventListener("scroll", () => {
    const h = document.documentElement;
    const scrolled = (h.scrollTop) / (h.scrollHeight - h.clientHeight);
    if (progressBar) progressBar.style.width = (scrolled * 100) + "%";
  }, { passive: true });

  /* ---------- Mobile nav ---------- */
  const toggle = document.getElementById("navToggle");
  const navLinks = document.getElementById("navLinks");
  if (toggle && navLinks) {
    const closeNav = () => {
      toggle.classList.remove("is-open");
      navLinks.classList.remove("is-open");
      toggle.setAttribute("aria-expanded", "false");
      toggle.setAttribute("aria-label", "Open menu");
    };
    toggle.addEventListener("click", () => {
      const open = navLinks.classList.toggle("is-open");
      toggle.classList.toggle("is-open", open);
      toggle.setAttribute("aria-expanded", String(open));
      toggle.setAttribute("aria-label", open ? "Close menu" : "Open menu");
    });
    navLinks.querySelectorAll("a").forEach((a) => a.addEventListener("click", closeNav));
    document.addEventListener("keydown", (e) => { if (e.key === "Escape") closeNav(); });
  }

  /* ---------- Hero intro (GSAP) ---------- */
  function startHeroIntro() {
    if (!hasGSAP || prefersReduced) {
      document.querySelectorAll(".hero .reveal, .hero__title .line > span")
        .forEach((el) => { el.style.opacity = 1; el.style.transform = "none"; });
      return;
    }
    const tl = gsap.timeline({ defaults: { ease: "power3.out" } });
    tl.from(".hero__title .line > span", {
      yPercent: 110, opacity: 0, duration: 1, stagger: 0.12,
    })
      .to(".hero__eyebrow", { opacity: 1, y: 0, duration: 0.7 }, "-=0.7")
      .to(".hero__lead",   { opacity: 1, y: 0, duration: 0.7 }, "-=0.5")
      .to(".hero__sub",    { opacity: 1, y: 0, duration: 0.7 }, "-=0.5")
      .to(".hero__actions",{ opacity: 1, y: 0, duration: 0.7 }, "-=0.5");
    // set initial offset for the eyebrow/lead/etc (they use .reveal => translateY)
  }

  /* ---------- Scroll reveals (GSAP ScrollTrigger) ---------- */
  if (hasGSAP && window.ScrollTrigger && !prefersReduced) {
    gsap.registerPlugin(ScrollTrigger);

    // Generic reveal for everything outside the hero
    gsap.utils.toArray(".reveal, .reveal-left, .reveal-right").forEach((el) => {
      if (el.closest(".hero")) return; // hero handled by intro timeline
      ScrollTrigger.create({
        trigger: el,
        start: "top 88%",
        once: true,
        onEnter: () => el.classList.add("is-inview"),
      });
    });

    // Pillars stagger
    ScrollTrigger.create({
      trigger: ".pillars",
      start: "top 80%",
      once: true,
      onEnter: () => {
        gsap.from(".pillar", {
          y: 40, opacity: 0, duration: 0.7, stagger: 0.12, ease: "power3.out",
          clearProps: "all",
        });
      },
    });

    // Subtle parallax on the success image
    gsap.to(".success__media-img", {
      yPercent: -8, ease: "none",
      scrollTrigger: { trigger: ".success", start: "top bottom", end: "bottom top", scrub: true },
    });
  } else {
    // No GSAP / reduced motion → just show everything
    document.querySelectorAll(".reveal, .reveal-left, .reveal-right")
      .forEach((el) => el.classList.add("is-inview"));
  }

  /* ---------- Animated number counters ---------- */
  const counters = document.querySelectorAll("[data-count]");
  const animateCount = (el) => {
    const target = parseFloat(el.dataset.count);
    const decimals = parseInt(el.dataset.decimals || "0", 10);
    const prefix = el.dataset.prefix || "";
    const suffix = el.dataset.suffix || "";
    const dur = 1600;
    const start = performance.now();
    const step = (now) => {
      const p = Math.min((now - start) / dur, 1);
      const eased = 1 - Math.pow(1 - p, 3); // easeOutCubic
      const val = (target * eased).toFixed(decimals);
      el.textContent = prefix + val + suffix;
      if (p < 1) requestAnimationFrame(step);
      else el.textContent = prefix + target.toFixed(decimals) + suffix;
    };
    requestAnimationFrame(step);
  };

  if ("IntersectionObserver" in window) {
    const io = new IntersectionObserver((entries, obs) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          animateCount(entry.target);
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });
    counters.forEach((c) => io.observe(c));
  } else {
    counters.forEach(animateCount);
  }

  /* ---------- CTA form ---------- */
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
      note.textContent = `Thanks, ${name.split(" ")[0]}! We'll be in touch within one business day.`;
      note.classList.add("is-success");
      form.reset();
      if (hasGSAP) gsap.fromTo(note, { scale: 0.9, opacity: 0.4 }, { scale: 1, opacity: 1, duration: 0.5, ease: "back.out(2)" });
    });
  }

  /* ---------- Smooth-scroll for in-page anchors (respects header offset) ---------- */
  document.querySelectorAll('a[href^="#"]').forEach((link) => {
    link.addEventListener("click", (e) => {
      const id = link.getAttribute("href");
      if (id.length < 2) return;
      const target = document.querySelector(id);
      if (!target) return;
      e.preventDefault();
      const top = target.getBoundingClientRect().top + window.scrollY - 70;
      window.scrollTo({ top, behavior: prefersReduced ? "auto" : "smooth" });
    });
  });
})();
