/**
 * Hero Crisis Buildup — Tags cluster, escalate, and explode
 *
 * Crisis tags spawn rapidly, cluster via gravity, progressively
 * shift blue → orange → red as density rises. At critical mass
 * they detonate with a shockwave ring and spawn a burst of new
 * tags — the crisis multiplies. Cursor pushes tags apart.
 *
 * @package Avante
 * @subpackage JS/CrisisAcademy-Homepage
 */

function initHeroCrisisGrid() {
    const hero = document.getElementById('hero');
    if (!hero || hero.dataset.gridInit) return;
    hero.dataset.gridInit = '1';

    const canvas = document.createElement('canvas');
    canvas.className = 'hero-canvas';
    hero.insertBefore(canvas, hero.firstChild);
    const ctx = canvas.getContext('2d');

    /* ── Crisis keywords ─────────────────────────────────────── */
    const TAGS = [
        '#Negligencia', '#MalasPrácticas', '#Escándalo', '#Fraude',
        '#Corrupción', '#Acoso', '#Discriminación', '#Filtración',
        '#Demanda', '#Boicot', '#DespidoMasivo', '#FugaDeDatos',
        '#ProductoDefectuoso', '#Polémica', '#Difamación',
        '#AbusoLaboral', '#LavadoDeDinero', '#CrisisAmbiental',
        '#PublicidadEngañosa', '#Soborno', '#CrisisDeConfianza',
        '#DeclaracionesOfensivas', '#ConflictoDeInterés',
        '#FaltaDeTransparencia', '#ExplotaciónLaboral',
        '#MalaPraxis', '#PlagioCorporativo', '#DañoAmbiental',
        '#ManipulaciónMediatica', '#RetiradaDeProducto',
        '#CrisisSanitaria', '#Impunidad', '#RumoresVirales',
        '#CaídaDeReputación', '#EscándaloFiscal',
    ];

    /* ════════════════════════════════════════════════════════════
       CONFIGURACIÓN — Ajusta estos valores para cambiar el ritmo
       ════════════════════════════════════════════════════════════ */

    const CLUSTER_COUNT = 3;         // Núcleos de agrupación simultáneos
    const GRAVITY = 0.03;      // Fuerza de atracción (↑ = se juntan más rápido)
    const CRITICAL_DENSITY = 110;       // Dist. promedio para empezar a ponerse rojo
    const EXPLOSION_DENSITY = 50;        // Dist. promedio para explotar (↑ = explota antes)
    const MOUSE_RADIUS = 200;      // Radio de influencia del cursor (px)
    const MOUSE_PUSH = 1.4;       // Fuerza de repulsión del cursor
    const SPAWN_MS = 60;        // ms entre cada tag nuevo (↓ = más rápido)
    const MAX_TAGS = 65;        // Máximo de tags activos
    const EXPLOSION_SPAWN = 14;        // Tags nuevos que nacen de cada explosión
    const SHOCKWAVE_COUNT = 4;         // Ondas expansivas por explosión
    const SHOCKWAVE_MAX_R = 160;       // Radio máximo de las ondas expansivas
    const DEBRIS_COUNT = 50;        // Partículas de debris por explosión

    /* ── Colores (RGB) ─────────────────────────────────────────
       Azul base:    r=0,   g=180, b=255  (estado normal)
       Rojo alerta:  r=255, g=45,  b=45   (estado crítico)
       Explosión:    r=255, g=80,  b=30   (debris/ondas)
       ──────────────────────────────────────────────────────── */
    const COLOR_NORMAL = { r: 131, g: 166, b: 208 };
    const COLOR_ALERT = { r: 255, g: 45, b: 45 };
    const COLOR_EXPLODE = { r: 201, g: 10, b: 33 };

    /* ── State ───────────────────────────────────────────────── */
    let W, H, dpr;
    let isMobile = false; // Detectado dinámicamente en resize()
    let mouseX = -9999, mouseY = -9999;
    let tags = [];
    let clusters = [];
    let shockwaves = [];
    let explosionParticles = [];
    let isVisible = true;
    let rafId = null;
    let lastSpawn = 0;
    let canvasStartTime = null;
    let initialExplosionTriggered = [false, false, false];
    let positionsInitialized = false;

    /* ── Tag ──────────────────────────────────────────────────── */
    function createTag(x, y, clusterIdx) {
        const label = TAGS[Math.floor(Math.random() * TAGS.length)];
        const fontSize = 10 + Math.random() * 4;

        // If no position given, spawn from random edge
        if (x === undefined) {
            const side = Math.floor(Math.random() * 4);
            if (side === 0) { x = -100; y = Math.random() * H; }
            else if (side === 1) { x = W + 100; y = Math.random() * H; }
            else if (side === 2) { x = Math.random() * W; y = -50; }
            else { x = Math.random() * W; y = H + 50; }
        }

        const ci = clusterIdx !== undefined ? clusterIdx : Math.floor(Math.random() * CLUSTER_COUNT);
        const angle = Math.random() * Math.PI * 2;

        return {
            x, y,
            vx: Math.cos(angle) * 0.3,
            vy: Math.sin(angle) * 0.3,
            label, fontSize,
            alpha: 0,
            targetAlpha: 0.22 + Math.random() * 0.18,
            alertLevel: 0,
            cluster: ci,
            exploding: false,
            explodeVx: 0, explodeVy: 0,
            explodeAlpha: 1,
            dead: false,
        };
    }

    /* ── Cluster ─────────────────────────────────────────────── */
    function createCluster() {
        return {
            x: W * 0.12 + Math.random() * W * 0.76,
            y: H * 0.12 + Math.random() * H * 0.76,
            driftVx: (Math.random() - 0.5) * 0.2,
            driftVy: (Math.random() - 0.5) * 0.15,
        };
    }

    /* ── Múltiples ondas expansivas escalonadas ─────────────── */
    function spawnShockwaves(cx, cy) {
        const count = isMobile ? 2 : SHOCKWAVE_COUNT;
        const maxRadius = isMobile ? SHOCKWAVE_MAX_R * 0.7 : SHOCKWAVE_MAX_R;
        for (let i = 0; i < count; i++) {
            // Cada onda arranca con un radio distinto para efecto escalonado
            const delay = i * 8;  // frames de retraso simulado via radio inicial
            shockwaves.push({
                x: cx, y: cy,
                r: -delay,          // radio negativo = espera antes de aparecer
                maxR: maxRadius * (1 - i * 0.16),
                alpha: 0.65 - i * 0.1,
                lineWidth: 3.5 - i * 0.5,
            });
        }
    }

    /* ── Ondas de advertencia (pre-explosión) ───────────────── */
    function spawnWarningWave(cx, cy) {
        shockwaves.push({
            x: cx, y: cy,
            r: 0,
            maxR: 95,
            alpha: 0.38,
            lineWidth: 1.0,
            isWarning: true
        });
    }

    /* ── Debris de explosión ──────────────────────────────────── */
    function spawnDebris(cx, cy) {
        const count = isMobile ? 15 : DEBRIS_COUNT;
        for (let i = 0; i < count; i++) {
            const angle = Math.random() * Math.PI * 2;
            const speed = 3 + Math.random() * (isMobile ? 5 : 9);
            explosionParticles.push({
                x: cx, y: cy,
                vx: Math.cos(angle) * speed,
                vy: Math.sin(angle) * speed,
                r: 1 + Math.random() * (isMobile ? 2 : 3),
                alpha: 0.85 + Math.random() * 0.15,
                life: 40 + Math.random() * (isMobile ? 20 : 40),
            });
        }
    }

    /* ── Sizing ──────────────────────────────────────────────── */
    function resize() {
        const rect = hero.getBoundingClientRect();
        dpr = Math.min(window.devicePixelRatio || 1, 2);
        W = rect.width;
        H = rect.height;
        isMobile = W < 768; // Toggles mobile mode optimizing rendering metrics
        canvas.width = W * dpr;
        canvas.height = H * dpr;
        canvas.style.width = W + 'px';
        canvas.style.height = H + 'px';
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

        // If dimensions are valid and we haven't initialized positions yet, do it now
        if (W > 0 && H > 0 && !positionsInitialized) {
            initPositions();
        }
    }

    function init() {
        resize();
        tags = [];
        clusters = [];
        shockwaves = [];
        explosionParticles = [];
        canvasStartTime = null;
        initialExplosionTriggered = [false, false, false];
        positionsInitialized = false;

        // Try initializing positions if width and height are already valid
        if (W > 0 && H > 0) {
            initPositions();
        }
    }

    function initPositions() {
        if (positionsInitialized) return;
        positionsInitialized = true;

        clusters = [];
        for (let i = 0; i < CLUSTER_COUNT; i++) {
            clusters.push(createCluster());
        }

        // Spawn multiple tags grouped around each cluster center.
        // We stagger their initial starting distances so they have enough time
        // to drift toward the center, build up visual tension (turn red),
        // and trigger at exactly the designated delayed times (3s and 6s).
        for (let ci = 0; ci < CLUSTER_COUNT; ci++) {
            const cluster = clusters[ci];
            // Spawn 5 to 7 tags per cluster
            const numTags = 5 + Math.floor(Math.random() * 3);
            for (let j = 0; j < numTags; j++) {
                const angle = Math.random() * Math.PI * 2;
                
                // Stagger starting radius per cluster to allow visual buildup:
                // - Cluster 0: Starts moderately close (60-68px) -> will reach center and explode at 3s
                // - Cluster 1: Starts further (75-85px) -> will reach center and explode at 6s
                // - Cluster 2: Starts far (90-102px) -> will drift and explode normally/later
                let r;
                if (ci === 0) {
                    r = 60 + Math.random() * 8;
                } else if (ci === 1) {
                    r = 75 + Math.random() * 10;
                } else {
                    r = 90 + Math.random() * 12;
                }

                const x = cluster.x + Math.cos(angle) * r;
                const y = cluster.y + Math.sin(angle) * r;

                const t = createTag(x, y, ci);
                t.alpha = t.targetAlpha;
                // Start with some alert level so they look slightly orange/red and build up to full red
                t.alertLevel = 0.4 + Math.random() * 0.2;
                tags.push(t);
            }
        }
    }

    /* ── Helpers ─────────────────────────────────────────────── */
    function lerp(a, b, t) { return a + (b - a) * t; }

    function getClusterStats(ci) {
        let totalDist = 0, count = 0;
        let sx = 0, sy = 0;
        const c = clusters[ci];
        for (const t of tags) {
            if (t.cluster !== ci || t.exploding || t.dead) continue;
            totalDist += Math.hypot(t.x - c.x, t.y - c.y);
            sx += t.x; sy += t.y;
            count++;
        }
        return {
            avgDist: count > 1 ? totalDist / count : 9999,
            count,
            cx: count > 0 ? sx / count : c.x,
            cy: count > 0 ? sy / count : c.y,
        };
    }

    /* ── Main loop ───────────────────────────────────────────── */
    function draw(timestamp) {
        if (!isVisible || hero.classList.contains('is-bottom')) {
            rafId = null;
            return;
        }
        ctx.clearRect(0, 0, W, H);

        if (!canvasStartTime) canvasStartTime = timestamp;
        const elapsedSeconds = (timestamp - canvasStartTime) / 1000;

        /* ── Pulse warning waves during buildup ────────────────── */
        if (W > 0 && H > 0 && clusters.length > 0) {
            // Pulse a warning wave every 900ms for Cúmulo 0 until it explodes at 3s
            if (elapsedSeconds < 3.0 && !initialExplosionTriggered[0]) {
                if (!clusters[0]._lastWavePulse || timestamp - clusters[0]._lastWavePulse > 900) {
                    clusters[0]._lastWavePulse = timestamp;
                    spawnWarningWave(clusters[0].x, clusters[0].y);
                }
            }
            // Pulse a warning wave every 900ms for Cúmulo 1 until it explodes at 6s
            if (elapsedSeconds < 6.0 && !initialExplosionTriggered[1]) {
                if (!clusters[1]._lastWavePulse || timestamp - clusters[1]._lastWavePulse > 900) {
                    clusters[1]._lastWavePulse = timestamp;
                    spawnWarningWave(clusters[1].x, clusters[1].y);
                }
            }
        }

        /* ── Spawn ─────────────────────────────────────────────── */
        const activeCount = tags.filter(t => !t.dead && !t.exploding).length;
        const currentMaxTags = isMobile ? 22 : MAX_TAGS;
        const currentSpawnMs = isMobile ? 180 : SPAWN_MS;

        if (timestamp - lastSpawn > currentSpawnMs && activeCount < currentMaxTags) {
            tags.push(createTag());
            lastSpawn = timestamp;
        }

        /* ── Drift cluster centers ─────────────────────────────── */
        for (const c of clusters) {
            c.x += c.driftVx;
            c.y += c.driftVy;
            if (c.x < W * 0.08 || c.x > W * 0.92) c.driftVx *= -1;
            if (c.y < H * 0.08 || c.y > H * 0.92) c.driftVy *= -1;
            c.x = Math.max(W * 0.05, Math.min(W * 0.95, c.x));
            c.y = Math.max(H * 0.05, Math.min(H * 0.95, c.y));
        }

        /* ── Check clusters for explosion ──────────────────────── */
        for (let ci = 0; ci < clusters.length; ci++) {
            const stats = getClusterStats(ci);

            let shouldExplode = (stats.count >= 4 && stats.avgDist < EXPLOSION_DENSITY);

            // Force artificial sequence for the first two explosions at exactly 3s and 6s
            if (ci === 0 && !initialExplosionTriggered[0] && elapsedSeconds >= 3.0) {
                shouldExplode = true;
            } else if (ci === 1 && !initialExplosionTriggered[1] && elapsedSeconds >= 6.0) {
                shouldExplode = true;
            }

            if (shouldExplode) {
                // Block explosion if we haven't reached the exact designated time yet
                if (ci === 0 && !initialExplosionTriggered[0] && elapsedSeconds < 3.0) {
                    continue;
                }
                if (ci === 1 && !initialExplosionTriggered[1] && elapsedSeconds < 6.0) {
                    continue;
                }

                // Mark the initial explosion as triggered so subsequent ones can explode normally
                if (ci === 0 || ci === 1) {
                    initialExplosionTriggered[ci] = true;
                }

                // ¡BOOM! — La crisis explota
                // Use the exact cluster coordinates to ensure the shockwaves are perfectly centered on the gravity node
                const explodeX = (ci === 0 || ci === 1) ? clusters[ci].x : stats.cx;
                const explodeY = (ci === 0 || ci === 1) ? clusters[ci].y : stats.cy;
                spawnShockwaves(explodeX, explodeY);
                spawnDebris(explodeX, explodeY);

                // Todos los tags del cluster explotan hacia afuera
                for (const t of tags) {
                    if (t.cluster !== ci || t.exploding || t.dead) continue;
                    const angle = Math.atan2(t.y - stats.cy, t.x - stats.cx) + (Math.random() - 0.5) * 0.5;
                    const speed = 5 + Math.random() * 10;
                    t.exploding = true;
                    t.explodeVx = Math.cos(angle) * speed;
                    t.explodeVy = Math.sin(angle) * speed;
                    t.explodeAlpha = 1;
                }

                // La crisis se multiplica: nacen tags nuevos del epicentro
                const spawnCount = isMobile ? 4 : EXPLOSION_SPAWN;
                for (let s = 0; s < spawnCount; s++) {
                    const angle = Math.random() * Math.PI * 2;
                    const dist = 15 + Math.random() * (isMobile ? 40 : 70);
                    const nt = createTag(
                        stats.cx + Math.cos(angle) * dist,
                        stats.cy + Math.sin(angle) * dist
                    );
                    nt.alpha = 0;
                    tags.push(nt);
                }

                // Reset cluster position
                clusters[ci] = createCluster();
            }
        }

        /* ── Update tags ───────────────────────────────────────── */
        for (const t of tags) {
            // Fade in
            if (t.alpha < t.targetAlpha && !t.exploding) {
                t.alpha = Math.min(t.alpha + 0.008, t.targetAlpha);
            }

            if (t.exploding) {
                t.x += t.explodeVx;
                t.y += t.explodeVy;
                t.explodeVx *= 0.965;
                t.explodeVy *= 0.965;
                t.explodeAlpha -= 0.018;
                if (t.explodeAlpha <= 0) t.dead = true;
                continue;
            }

            // Gravity toward cluster
            const c = clusters[t.cluster];
            const dx = c.x - t.x, dy = c.y - t.y;
            const dist = Math.hypot(dx, dy);
            if (dist > 5) {
                t.vx += (dx / dist) * GRAVITY;
                t.vy += (dy / dist) * GRAVITY;
            }

            // Mouse push
            const mdx = t.x - mouseX, mdy = t.y - mouseY;
            const mDist = Math.hypot(mdx, mdy);
            if (mDist < MOUSE_RADIUS && mDist > 1) {
                const force = (1 - mDist / MOUSE_RADIUS) * MOUSE_PUSH;
                t.vx += (mdx / mDist) * force;
                t.vy += (mdy / mDist) * force;
            }

            t.vx *= 0.98;
            t.vy *= 0.98;
            t.x += t.vx;
            t.y += t.vy;

            // Alert level based on proximity
            const stats = getClusterStats(t.cluster);
            const density = stats.avgDist;
            let targetAlert = 0;
            if (density < CRITICAL_DENSITY) {
                targetAlert = Math.min((CRITICAL_DENSITY - density) / (CRITICAL_DENSITY - EXPLOSION_DENSITY), 1);
            }
            t.alertLevel = lerp(t.alertLevel, targetAlert, 0.06);
        }

        tags = tags.filter(t => !t.dead);

        /* ── Draw connection lines (Escritorio únicamente) ──────── */
        if (!isMobile) {
            for (let i = 0; i < tags.length; i++) {
                if (tags[i].exploding) continue;
                for (let j = i + 1; j < tags.length; j++) {
                    if (tags[j].exploding || tags[i].cluster !== tags[j].cluster) continue;
                    const a = tags[i], b = tags[j];
                    const d = Math.hypot(a.x - b.x, a.y - b.y);
                    if (d < 140) {
                        const t = 1 - d / 140;
                        const al = Math.max(a.alertLevel, b.alertLevel);
                        const r = Math.round(lerp(COLOR_NORMAL.r, COLOR_ALERT.r, al));
                        const g = Math.round(lerp(COLOR_NORMAL.g, COLOR_ALERT.g, al));
                        const bl = Math.round(lerp(COLOR_NORMAL.b, COLOR_ALERT.b, al));
                        const alpha = t * 0.1 + al * t * 0.2;
                        ctx.beginPath();
                        ctx.moveTo(a.x, a.y);
                        ctx.lineTo(b.x, b.y);
                        ctx.strokeStyle = `rgba(${r},${g},${bl},${alpha.toFixed(3)})`;
                        ctx.lineWidth = 0.4 + al * 0.8;
                        ctx.stroke();
                    }
                }
            }
        }

        /* ── Draw tags ─────────────────────────────────────────── */
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';

        for (const t of tags) {
            const a = t.exploding ? t.explodeAlpha : t.alpha;
            if (a <= 0.01) continue;

            const al = t.alertLevel;
            const r = Math.round(lerp(COLOR_NORMAL.r, COLOR_ALERT.r, al));
            const g = Math.round(lerp(COLOR_NORMAL.g, COLOR_ALERT.g, al));
            const bl = Math.round(lerp(COLOR_NORMAL.b, COLOR_ALERT.b, al));

            ctx.font = `600 ${t.fontSize + al * 2}px "Manrope", sans-serif`;

            if (!isMobile && (al > 0.25 || t.exploding)) {
                ctx.shadowColor = `rgba(${r},${g},${bl},${(a * 0.45).toFixed(3)})`;
                ctx.shadowBlur = 6 + al * 16;
            } else {
                ctx.shadowColor = 'transparent';
                ctx.shadowBlur = 0;
            }

            ctx.fillStyle = `rgba(${r},${g},${bl},${a.toFixed(3)})`;
            ctx.fillText(t.label, t.x, t.y);
        }
        ctx.shadowColor = 'transparent';
        ctx.shadowBlur = 0;

        /* ── Draw shockwaves ───────────────────────────────────── */
        for (let i = shockwaves.length - 1; i >= 0; i--) {
            const sw = shockwaves[i];
            
            if (sw.isWarning) {
                sw.r += 1.8;       // Slower warning wave expansion
                sw.alpha -= 0.009;  // Gradual fade
            } else {
                sw.r += 7;          // Velocidad de expansión de la onda de explosión
                sw.alpha -= 0.012;
            }

            // Onda aún no visible (retraso escalonado)
            if (sw.r < 0) continue;

            if (sw.alpha <= 0 || sw.r > sw.maxR) {
                shockwaves.splice(i, 1);
                continue;
            }

            const progress = sw.r / sw.maxR;

            if (sw.isWarning) {
                // Elegant thin warning pulse
                ctx.beginPath();
                ctx.arc(sw.x, sw.y, sw.r, 0, Math.PI * 2);
                ctx.strokeStyle = `rgba(${COLOR_ALERT.r},${COLOR_ALERT.g},${COLOR_ALERT.b},${sw.alpha.toFixed(3)})`;
                ctx.lineWidth = sw.lineWidth || 1.0;
                ctx.stroke();
            } else {
                // Anillo exterior de explosión
                ctx.beginPath();
                ctx.arc(sw.x, sw.y, sw.r, 0, Math.PI * 2);
                ctx.strokeStyle = `rgba(${COLOR_EXPLODE.r},${COLOR_EXPLODE.g},${COLOR_EXPLODE.b},${sw.alpha.toFixed(3)})`;
                ctx.lineWidth = (sw.lineWidth || 2.5) + (1 - progress) * 4;
                ctx.stroke();

                // Resplandor interior difuso
                const grad = ctx.createRadialGradient(sw.x, sw.y, sw.r * 0.8, sw.x, sw.y, sw.r);
                grad.addColorStop(0, `rgba(255,120,40,0)`);
                grad.addColorStop(1, `rgba(${COLOR_EXPLODE.r},${COLOR_EXPLODE.g},${COLOR_EXPLODE.b},${(sw.alpha * 0.12).toFixed(3)})`);
                ctx.beginPath();
                ctx.arc(sw.x, sw.y, sw.r, 0, Math.PI * 2);
                ctx.fillStyle = grad;
                ctx.fill();
            }
        }

        /* ── Draw debris particles ─────────────────────────────── */
        for (let i = explosionParticles.length - 1; i >= 0; i--) {
            const p = explosionParticles[i];
            p.x += p.vx;
            p.y += p.vy;
            p.vx *= 0.955;
            p.vy *= 0.955;
            p.life--;
            p.alpha -= 0.018;
            if (p.life <= 0 || p.alpha <= 0) {
                explosionParticles.splice(i, 1);
                continue;
            }
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(255,${70 + Math.round(Math.random() * 50)},30,${p.alpha.toFixed(3)})`;
            ctx.fill();
        }

        rafId = requestAnimationFrame(draw);
    }

    /* ── Events ──────────────────────────────────────────────── */
    let heroRect;
    hero.addEventListener('mouseenter', () => { heroRect = hero.getBoundingClientRect(); });
    hero.addEventListener('mousemove', (e) => {
        if (!heroRect) heroRect = hero.getBoundingClientRect();
        mouseX = e.clientX - heroRect.left;
        mouseY = e.clientY - heroRect.top;
    });
    hero.addEventListener('mouseleave', () => { mouseX = -9999; mouseY = -9999; });

    /* ── Visibility & Class Observer ─────────────────────────── */
    const io = new IntersectionObserver(([entry]) => {
        isVisible = entry.isIntersecting;
        if (isVisible && !hero.classList.contains('is-bottom') && !rafId) {
            rafId = requestAnimationFrame(draw);
        }
    }, { threshold: 0.05 });
    io.observe(hero);

    // MutationObserver to watch class changes and resume loop if 'is-bottom' is removed
    const classObserver = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'class') {
                const isBottom = hero.classList.contains('is-bottom');
                if (isVisible && !isBottom && !rafId) {
                    rafId = requestAnimationFrame(draw);
                }
            }
        });
    });
    classObserver.observe(hero, { attributes: true, attributeFilter: ['class'] });

    /* ── Resize ──────────────────────────────────────────────── */
    let rt;
    window.addEventListener('resize', () => {
        clearTimeout(rt);
        rt = setTimeout(() => { resize(); heroRect = hero.getBoundingClientRect(); }, 200);
    }, { passive: true });

    init();
    rafId = requestAnimationFrame(draw);
}
