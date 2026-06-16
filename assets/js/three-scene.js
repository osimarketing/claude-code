/* =========================================================
   PatientPath — Three.js animated background
   A flowing "connected paths" particle field that echoes the
   brand: nodes (patients/practices) linked along glowing paths.
   Performance-aware + respects prefers-reduced-motion.
   ========================================================= */
import * as THREE from "three";

const canvas = document.getElementById("bg-canvas");
const prefersReduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

if (canvas && !prefersReduced) {
  initScene(canvas);
}

function initScene(canvas) {
  const isMobile = window.matchMedia("(max-width: 768px)").matches;

  const scene = new THREE.Scene();
  scene.fog = new THREE.FogExp2(0x14323b, 0.055);

  const camera = new THREE.PerspectiveCamera(
    60,
    window.innerWidth / window.innerHeight,
    0.1,
    100
  );
  camera.position.set(0, 0, 14);

  const renderer = new THREE.WebGLRenderer({
    canvas,
    antialias: !isMobile,
    alpha: true,
    powerPreference: "high-performance",
  });
  renderer.setSize(window.innerWidth, window.innerHeight);
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, isMobile ? 1.5 : 2));

  // ---- Brand palette ----
  const GOLD = new THREE.Color(0xeb9b3e);
  const BLUE = new THREE.Color(0x2b93b0);

  // ---------------------------------------------------------
  // Particle field
  // ---------------------------------------------------------
  const COUNT = isMobile ? 90 : 180;
  const SPREAD = 26;
  const positions = new Float32Array(COUNT * 3);
  const colors = new Float32Array(COUNT * 3);
  const velocities = [];

  for (let i = 0; i < COUNT; i++) {
    const x = (Math.random() - 0.5) * SPREAD;
    const y = (Math.random() - 0.5) * SPREAD * 0.7;
    const z = (Math.random() - 0.5) * SPREAD * 0.6;
    positions[i * 3] = x;
    positions[i * 3 + 1] = y;
    positions[i * 3 + 2] = z;

    const c = Math.random() > 0.5 ? GOLD : BLUE;
    colors[i * 3] = c.r;
    colors[i * 3 + 1] = c.g;
    colors[i * 3 + 2] = c.b;

    velocities.push(
      new THREE.Vector3(
        (Math.random() - 0.5) * 0.012,
        (Math.random() - 0.5) * 0.012,
        (Math.random() - 0.5) * 0.012
      )
    );
  }

  const pGeo = new THREE.BufferGeometry();
  pGeo.setAttribute("position", new THREE.BufferAttribute(positions, 3));
  pGeo.setAttribute("color", new THREE.BufferAttribute(colors, 3));

  // soft round sprite for points
  const sprite = makeCircleTexture();
  const pMat = new THREE.PointsMaterial({
    size: isMobile ? 0.5 : 0.42,
    map: sprite,
    vertexColors: true,
    transparent: true,
    opacity: 0.9,
    depthWrite: false,
    blending: THREE.AdditiveBlending,
    sizeAttenuation: true,
  });
  const points = new THREE.Points(pGeo, pMat);
  scene.add(points);

  // ---------------------------------------------------------
  // Connecting lines (the "paths")
  // ---------------------------------------------------------
  const MAX_DIST = isMobile ? 4.2 : 4.8;
  const lineGeo = new THREE.BufferGeometry();
  const maxLineVerts = COUNT * COUNT;
  const linePositions = new Float32Array(maxLineVerts * 3);
  const lineColors = new Float32Array(maxLineVerts * 3);
  lineGeo.setAttribute("position", new THREE.BufferAttribute(linePositions, 3));
  lineGeo.setAttribute("color", new THREE.BufferAttribute(lineColors, 3));
  const lineMat = new THREE.LineBasicMaterial({
    vertexColors: true,
    transparent: true,
    opacity: 0.22,
    blending: THREE.AdditiveBlending,
    depthWrite: false,
  });
  const lines = new THREE.LineSegments(lineGeo, lineMat);
  scene.add(lines);

  // ---------------------------------------------------------
  // Large brand ring (echoes the gold "P" ring in the original)
  // ---------------------------------------------------------
  const ringGroup = new THREE.Group();
  const ring = new THREE.Mesh(
    new THREE.TorusGeometry(7.5, 0.06, 16, 120),
    new THREE.MeshBasicMaterial({ color: GOLD, transparent: true, opacity: 0.18 })
  );
  const ring2 = new THREE.Mesh(
    new THREE.TorusGeometry(5.2, 0.04, 16, 120),
    new THREE.MeshBasicMaterial({ color: BLUE, transparent: true, opacity: 0.16 })
  );
  ring2.rotation.x = Math.PI / 3;
  ringGroup.add(ring, ring2);
  ringGroup.position.set(7, 1, -6);
  scene.add(ringGroup);

  // ---------------------------------------------------------
  // Interaction (pointer parallax + scroll)
  // ---------------------------------------------------------
  const pointer = { x: 0, y: 0, tx: 0, ty: 0 };
  window.addEventListener("pointermove", (e) => {
    pointer.tx = (e.clientX / window.innerWidth - 0.5) * 2;
    pointer.ty = (e.clientY / window.innerHeight - 0.5) * 2;
  });

  let scrollY = 0;
  window.addEventListener("scroll", () => { scrollY = window.scrollY; }, { passive: true });

  // ---------------------------------------------------------
  // Animation loop
  // ---------------------------------------------------------
  const clock = new THREE.Clock();
  let rafId;
  let running = true;

  function animate() {
    if (!running) return;
    rafId = requestAnimationFrame(animate);
    const t = clock.getElapsedTime();

    // drift particles
    const pos = pGeo.attributes.position.array;
    for (let i = 0; i < COUNT; i++) {
      const i3 = i * 3;
      pos[i3] += velocities[i].x;
      pos[i3 + 1] += velocities[i].y;
      pos[i3 + 2] += velocities[i].z;
      // wrap within bounds
      ["x", "y", "z"].forEach((axis, a) => {
        const limit = a === 0 ? SPREAD / 2 : a === 1 ? (SPREAD * 0.7) / 2 : (SPREAD * 0.6) / 2;
        if (pos[i3 + a] > limit) pos[i3 + a] = -limit;
        if (pos[i3 + a] < -limit) pos[i3 + a] = limit;
      });
    }
    pGeo.attributes.position.needsUpdate = true;

    // rebuild connecting lines
    let v = 0;
    for (let i = 0; i < COUNT; i++) {
      const ax = pos[i * 3], ay = pos[i * 3 + 1], az = pos[i * 3 + 2];
      for (let j = i + 1; j < COUNT; j++) {
        const dx = ax - pos[j * 3];
        const dy = ay - pos[j * 3 + 1];
        const dz = az - pos[j * 3 + 2];
        const d2 = dx * dx + dy * dy + dz * dz;
        if (d2 < MAX_DIST * MAX_DIST) {
          const alpha = 1 - Math.sqrt(d2) / MAX_DIST;
          linePositions[v * 3] = ax;
          linePositions[v * 3 + 1] = ay;
          linePositions[v * 3 + 2] = az;
          linePositions[(v + 1) * 3] = pos[j * 3];
          linePositions[(v + 1) * 3 + 1] = pos[j * 3 + 1];
          linePositions[(v + 1) * 3 + 2] = pos[j * 3 + 2];
          for (let k = 0; k < 2; k++) {
            lineColors[(v + k) * 3] = GOLD.r * alpha;
            lineColors[(v + k) * 3 + 1] = GOLD.g * alpha;
            lineColors[(v + k) * 3 + 2] = BLUE.b * alpha;
          }
          v += 2;
        }
      }
    }
    lineGeo.setDrawRange(0, v);
    lineGeo.attributes.position.needsUpdate = true;
    lineGeo.attributes.color.needsUpdate = true;

    // rings slowly rotate
    ringGroup.rotation.z = t * 0.05;
    ring2.rotation.z = -t * 0.08;

    // smooth pointer parallax
    pointer.x += (pointer.tx - pointer.x) * 0.04;
    pointer.y += (pointer.ty - pointer.y) * 0.04;
    camera.position.x += (pointer.x * 2.2 - camera.position.x) * 0.05;
    camera.position.y += (-pointer.y * 1.6 - scrollY * 0.0016 - camera.position.y) * 0.05;
    points.rotation.y = t * 0.02;
    camera.lookAt(scene.position);

    renderer.render(scene, camera);
  }
  animate();

  // pause when tab hidden (saves battery/CPU)
  document.addEventListener("visibilitychange", () => {
    if (document.hidden) {
      running = false;
      cancelAnimationFrame(rafId);
    } else if (!running) {
      running = true;
      animate();
    }
  });

  // ---- resize ----
  let resizeTimer;
  window.addEventListener("resize", () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      camera.aspect = window.innerWidth / window.innerHeight;
      camera.updateProjectionMatrix();
      renderer.setSize(window.innerWidth, window.innerHeight);
    }, 150);
  });
}

// soft circular point texture
function makeCircleTexture() {
  const size = 64;
  const c = document.createElement("canvas");
  c.width = c.height = size;
  const ctx = c.getContext("2d");
  const g = ctx.createRadialGradient(size / 2, size / 2, 0, size / 2, size / 2, size / 2);
  g.addColorStop(0, "rgba(255,255,255,1)");
  g.addColorStop(0.4, "rgba(255,255,255,0.6)");
  g.addColorStop(1, "rgba(255,255,255,0)");
  ctx.fillStyle = g;
  ctx.fillRect(0, 0, size, size);
  const tex = new THREE.CanvasTexture(c);
  return tex;
}
