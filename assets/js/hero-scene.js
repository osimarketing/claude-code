/* =========================================================
   PatientPath — refined hero background
   Slow, low-opacity flowing "path" lines. Restrained, to suit
   a consulting aesthetic. Respects reduced-motion + battery.
   ========================================================= */
import * as THREE from "three";

const canvas = document.getElementById("bg-canvas");
const reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
if (canvas && !reduced) init(canvas);

function init(canvas) {
  const isMobile = window.matchMedia("(max-width: 768px)").matches;

  const scene = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(60, window.innerWidth / window.innerHeight, 0.1, 100);
  camera.position.z = 14;

  const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: !isMobile, powerPreference: "high-performance" });
  renderer.setSize(window.innerWidth, window.innerHeight);
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

  const GOLD = new THREE.Color(0xd99240);
  const TEAL = new THREE.Color(0x2b93b0);

  const COUNT = isMobile ? 5 : 9;
  const SEG = 140;
  const W = 36;
  const lines = [];

  for (let i = 0; i < COUNT; i++) {
    const geo = new THREE.BufferGeometry();
    const pos = new Float32Array(SEG * 3);
    geo.setAttribute("position", new THREE.BufferAttribute(pos, 3));
    const mat = new THREE.LineBasicMaterial({
      color: i % 3 === 0 ? GOLD : TEAL,
      transparent: true,
      opacity: i % 3 === 0 ? 0.22 : 0.13,
    });
    const line = new THREE.Line(geo, mat);
    scene.add(line);
    lines.push({
      geo, pos,
      y: (i / (COUNT - 1) - 0.5) * 15,
      phase: i * 0.8,
      amp: 1.1 + Math.random() * 1.6,
      speed: 0.12 + Math.random() * 0.14,
    });
  }

  const pointer = { x: 0, y: 0, tx: 0, ty: 0 };
  window.addEventListener("pointermove", (e) => {
    pointer.tx = (e.clientX / window.innerWidth - 0.5) * 2;
    pointer.ty = (e.clientY / window.innerHeight - 0.5) * 2;
  });

  const clock = new THREE.Clock();
  let raf, running = true;

  function animate() {
    if (!running) return;
    raf = requestAnimationFrame(animate);
    const t = clock.getElapsedTime();

    for (const L of lines) {
      for (let s = 0; s < SEG; s++) {
        const x = (s / (SEG - 1) - 0.5) * W;
        const y = L.y
          + Math.sin(x * 0.28 + t * L.speed + L.phase) * L.amp
          + Math.sin(x * 0.12 - t * L.speed * 0.6) * 0.5;
        const z = Math.cos(x * 0.18 + t * 0.2 + L.phase) * 1.3;
        L.pos[s * 3] = x; L.pos[s * 3 + 1] = y; L.pos[s * 3 + 2] = z;
      }
      L.geo.attributes.position.needsUpdate = true;
    }

    pointer.x += (pointer.tx - pointer.x) * 0.04;
    pointer.y += (pointer.ty - pointer.y) * 0.04;
    camera.position.x += (pointer.x * 1.4 - camera.position.x) * 0.05;
    camera.position.y += (-pointer.y * 0.9 - camera.position.y) * 0.05;
    camera.lookAt(scene.position);

    renderer.render(scene, camera);
  }
  animate();

  document.addEventListener("visibilitychange", () => {
    if (document.hidden) { running = false; cancelAnimationFrame(raf); }
    else if (!running) { running = true; animate(); }
  });

  let rt;
  window.addEventListener("resize", () => {
    clearTimeout(rt);
    rt = setTimeout(() => {
      camera.aspect = window.innerWidth / window.innerHeight;
      camera.updateProjectionMatrix();
      renderer.setSize(window.innerWidth, window.innerHeight);
    }, 150);
  });
}
