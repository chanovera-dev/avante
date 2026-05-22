/**
 * Upcoming Events — World Clock
 *
 * Reads [data-tz] from each .world-clock element and
 * updates .world-clock__time every second with the local
 * time for that timezone.
 */
function initUpcomingEventsClocks() {
    const clocks = document.querySelectorAll('#upcoming-events-clocks .world-clock');
    if (clocks.length === 0) return;

    function tick() {
        clocks.forEach(clock => {
            const tz = clock.dataset.tz;
            const timeEl = clock.querySelector('.world-clock__time');
            if (!timeEl || !tz) return;

            try {
                const now = new Date();
                const formatted = now.toLocaleTimeString('es-MX', {
                    timeZone: tz,
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true,
                });
                timeEl.textContent = formatted;
            } catch (e) {
                timeEl.textContent = '--:--';
            }
        });
    }

    tick();
    setInterval(tick, 1000);
}

/**
 * Upcoming Events — Scroll fade edges
 *
 * Toggles .at-start / .at-end classes on the track wrapper
 * so the CSS gradient fade hints are shown/hidden correctly.
 */
function initUpcomingEventsScroll() {
    const wrapper = document.getElementById('upcoming-events-track-wrapper');
    const track = document.getElementById('upcoming-events-track');
    if (!wrapper || !track) return;

    const check = () => {
        const { scrollLeft, scrollWidth, clientWidth } = track;
        wrapper.classList.toggle('at-start', scrollLeft <= 4);
        wrapper.classList.toggle('at-end', scrollLeft + clientWidth >= scrollWidth - 4);
    };

    track.addEventListener('scroll', check, { passive: true });
    window.addEventListener('resize', check, { passive: true });
    check();
}

/**
 * Upcoming Events — Opacity cascade based on visible position
 *
 * Observes which cards are inside the scroll track viewport on each
 * scroll/resize and applies opacity based on their ORDER within the
 * visible window (not their global index in the array).
 *
 * First visible card  → opacity 1.00
 * Second visible card → opacity 0.82
 * Third visible card  → opacity 0.64
 * … capped at 0.30 minimum
 * Cards outside view  → opacity 0.25
 */
function initUpcomingEventsOpacityCascade() {
    const track = document.getElementById('upcoming-events-track');
    if (!track) return;

    const cards = Array.from(track.querySelectorAll('.event-card'));
    if (cards.length === 0) return;

    const STEP = 0.18;   // opacity drop per visible position
    const MIN_VIS = 0.30;   // minimum opacity while still visible
    const OFF = 0.25;   // opacity for off-screen cards

    function update() {
        const trackLeft = track.scrollLeft;
        const trackRight = trackLeft + track.clientWidth;

        // Classify each card
        const visible = [];

        cards.forEach(card => {
            // offsetLeft is relative to scrollable parent
            const cardLeft = card.offsetLeft;
            const cardRight = cardLeft + card.offsetWidth;

            // Consider visible if at least 30px of the card is inside the track
            const overlap = Math.min(cardRight, trackRight) - Math.max(cardLeft, trackLeft);

            if (overlap >= 30) {
                visible.push({ card, cardLeft });
            } else {
                // Off-screen — skip featured
                if (!card.classList.contains('event-card--featured')) {
                    card.style.opacity = OFF;
                }
            }
        });

        // Sort left-to-right so position 0 is always the leftmost visible
        visible.sort((a, b) => a.cardLeft - b.cardLeft);

        visible.forEach(({ card }, i) => {
            // Featured card is always fully opaque
            if (card.classList.contains('event-card--featured')) {
                card.style.opacity = '1';
                return;
            }
            const opacity = Math.max(MIN_VIS, 1 - i * STEP);
            card.style.opacity = opacity;
        });
    }

    track.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update, { passive: true });

    // Run once after layout is ready
    requestAnimationFrame(update);
}

/**
 * FAQ Section — Accordion Toggle
 */
function initFaqAccordion() {
    const accordionItems = document.querySelectorAll('.accordion-item');

    accordionItems.forEach(item => {
        const header = item.querySelector('.accordion-header');
        const body = item.querySelector('.accordion-body');
        const icon = item.querySelector('.accordion-icon');

        header.addEventListener('click', () => {
            const isActive = item.classList.contains('active');

            // Close all other items
            accordionItems.forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.classList.remove('active');
                    const otherBody = otherItem.querySelector('.accordion-body');
                    const otherBtn = otherItem.querySelector('.accordion-header');
                    const otherIcon = otherItem.querySelector('.accordion-icon');

                    otherBody.style.maxHeight = null;
                    otherBody.style.opacity = '0';
                    otherBtn.setAttribute('aria-expanded', 'false');
                    otherIcon.innerHTML = '+';
                }
            });

            // Toggle current item
            if (isActive) {
                item.classList.remove('active');
                body.style.maxHeight = null;
                body.style.opacity = '0';
                header.setAttribute('aria-expanded', 'false');
                icon.innerHTML = '+';
            } else {
                item.classList.add('active');
                body.style.maxHeight = body.scrollHeight + 'px';
                body.style.opacity = '1';
                header.setAttribute('aria-expanded', 'true');
                icon.innerHTML = '&times;'; // Multiply symbol used as "X"
            }
        });
    });
}

/**
 * Dynamic Card Glow Effect (Mouse Tracking)
 * Reads current mouse coordinates inside the card and updates
 * CSS variables --mouse-x and --mouse-y for responsive glow styling.
 */
function initCardGlowEffect() {
    const cards = document.querySelectorAll('#hero, .about-item, .signal-item, .how-it-works--card, #cta .content, .cert-container, .cert-panel, .how-works-modal-container, .intro-funnel, .sliders .quotes-container');

    cards.forEach(card => {
        let rect;

        // Capture boundary ONCE when mouse enters to avoid recursive render loops
        card.addEventListener('mouseenter', () => {
            if (window.innerWidth < 1366) return;
            rect = card.getBoundingClientRect();
        });

        card.addEventListener('mousemove', e => {
            if (window.innerWidth < 1366) return;
            if (!rect) rect = card.getBoundingClientRect(); // Safety fallback
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            card.style.setProperty('--mouse-x', `${x}px`);
            card.style.setProperty('--mouse-y', `${y}px`);
        });
    });
}

/**
 * Sticky Overlap Effect
 * Sections stack on top of each other as the user scrolls down.
 * For sections taller than the viewport, a negative `top` is used so the
 * section only sticks once the user has scrolled to its bottom.
 */
function initStickyOverlapEffect() {
    const blocks = Array.from(document.querySelectorAll('.site-main > .block'));
    if (blocks.length < 2) return;

    // Recalculate `top` for each block based on its full content height.
    // scrollHeight is used (not offsetHeight) to get the real height including
    // any content that may not yet have rendered at DOMContentLoaded.
    function applyStickyTops() {
        if (window.innerWidth < 1024) {
            blocks.forEach(block => {
                block.style.position = '';
                block.style.zIndex = '';
                block.style.top = '';
                block.classList.remove('is-bottom');
            });
            return;
        }

        const vh = window.innerHeight;
        blocks.forEach((block, index) => {
            block.style.position = 'sticky';
            block.style.zIndex = index + 1;
            const bh = block.scrollHeight;
            // Negative top: section scrolls until its bottom hits the viewport bottom,
            // then sticks — ensuring the user sees ALL content before overlap.
            block.style.top = bh > vh ? `${vh - bh}px` : '0px';
        });
    }

    applyStickyTops(); // Initial estimate (pre-images)

    // Re-run after all resources (images, fonts) are loaded
    window.addEventListener('load', applyStickyTops);
    window.addEventListener('resize', applyStickyTops, { passive: true });

    // Re-run if any section changes its rendered size (dynamic content, accordions, etc.)
    if (window.ResizeObserver) {
        const ro = new ResizeObserver(applyStickyTops);
        blocks.forEach(block => ro.observe(block));
    }

    function updateOverlap() {
        if (window.innerWidth < 1024) {
            blocks.forEach(block => {
                block.classList.remove('is-bottom');
            });
            return;
        }

        blocks.forEach((block, index) => {
            if (index === blocks.length - 1) {
                block.classList.remove('is-bottom');
                return;
            }

            const nextBlock = blocks[index + 1];
            const nextTop = nextBlock.getBoundingClientRect().top;

            // Start dimming when the next block is within 50% of the viewport
            block.classList.toggle('is-bottom', nextTop <= window.innerHeight * 0.5);
        });
    }

    window.addEventListener('scroll', updateOverlap, { passive: true });
    updateOverlap();
}

/**
 * Unified Entrance Animations
 * Integrates .pretext-reveal, .title-reveal, .card-reveal, and .object-reveal
 * into a single unified observer and a staggered orchestration queue.
 * This guarantees elements reveal in strict logical/spatial order (top-to-bottom).
 */
function initUnifiedAnimations() {
    const CHARS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%&';
    const SETTLE_FRAMES = 3;

    // Helper: Scramble text effect for pretext
    function scramble(el, originalText, callback) {
        if (el._scrambleId) cancelAnimationFrame(el._scrambleId);

        const len = originalText.length;
        let frame = 0;

        function tick() {
            const total = len * SETTLE_FRAMES;
            const lockedCount = Math.floor(frame / SETTLE_FRAMES);
            let output = '';
            for (let i = 0; i < len; i++) {
                if (originalText[i] === ' ') {
                    output += ' ';
                } else if (i < lockedCount) {
                    output += originalText[i];
                } else {
                    output += CHARS[Math.floor(Math.random() * CHARS.length)];
                }
            }
            el.textContent = output;
            if (frame < total) {
                frame++;
                el._scrambleId = requestAnimationFrame(tick);
            } else {
                el.textContent = originalText;
                el._scrambleId = null;
                if (callback) callback();
            }
        }

        el.classList.add('is-visible');
        tick();
    }

    // Helper: wrap words inside title for word-by-word fade-in
    function wrapWords(el) {
        const walker = document.createTreeWalker(el, NodeFilter.SHOW_TEXT);
        const textNodes = [];
        let node;
        while ((node = walker.nextNode())) textNodes.push(node);

        let wordIndex = 0;

        textNodes.forEach(textNode => {
            const parts = textNode.textContent.split(/(\s+)/);
            const frag = document.createDocumentFragment();

            parts.forEach(part => {
                if (!part || /^\s+$/.test(part)) {
                    frag.appendChild(document.createTextNode(part || ''));
                } else {
                    const span = document.createElement('span');
                    span.className = 'title-word';
                    span.style.setProperty('--word-index', wordIndex++);
                    span.textContent = part;
                    frag.appendChild(span);
                }
            });

            textNode.parentNode.replaceChild(frag, textNode);
        });
    }

    // Prepare Title elements
    document.querySelectorAll('.title-reveal').forEach(el => {
        el.setAttribute('aria-label', el.textContent.trim());
        wrapWords(el);
    });

    // Prepare Pretext elements
    document.querySelectorAll('.pretext-reveal').forEach(el => {
        const originalText = el.textContent.trim();
        el.setAttribute('aria-label', originalText);
        el._originalText = originalText;
    });

    // Central master queue
    let masterQueue = [];
    let isProcessing = false;

    function processQueue() {
        if (masterQueue.length === 0) {
            isProcessing = false;
            return;
        }
        isProcessing = true;

        const el = masterQueue.shift();

        if (el.classList.contains('pretext-reveal')) {
            scramble(el, el._originalText);
            // Stagger next element after scramble starts (200ms)
            setTimeout(processQueue, 200);
        } else if (el.classList.contains('title-reveal')) {
            el.classList.add('is-visible');
            // Stagger next element slightly longer (350ms) to allow word fade-in to build up
            setTimeout(processQueue, 350);
        } else if (el.classList.contains('card-reveal')) {
            el.classList.add('is-visible');
            // Stagger cards by 150ms for smooth cascade
            setTimeout(processQueue, 150);
        } else if (el.classList.contains('object-reveal')) {
            el.classList.add('is-visible');
            // Stagger other objects by 150ms
            setTimeout(processQueue, 150);
        } else {
            el.classList.add('is-visible');
            setTimeout(processQueue, 100);
        }
    }

    const io = new IntersectionObserver((entries) => {
        const visibleEntries = entries.filter(e => e.isIntersecting);

        if (visibleEntries.length === 0) return;

        // Sort all intersecting elements relative to their scroll position
        // Top-to-bottom, and left-to-right (horizontal sorting)
        visibleEntries.sort((a, b) => {
            const rectA = a.target.getBoundingClientRect();
            const rectB = b.target.getBoundingClientRect();
            if (Math.abs(rectA.top - rectB.top) < 50) {
                return rectA.left - rectB.left;
            }
            return rectA.top - rectB.top;
        });

        visibleEntries.forEach(entry => {
            io.unobserve(entry.target);
            masterQueue.push(entry.target);
        });

        if (!isProcessing) processQueue();

    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    // Observe all visual reveal targets
    const selectors = '.pretext-reveal, .title-reveal, .card-reveal, .object-reveal';
    document.querySelectorAll(selectors).forEach(el => io.observe(el));
}

/**
 * Smooth Scroll for Sticky Anchor Links
 * Bypasses the native anchor jump bug where browsers won't scroll 
 * to a sticky element if it's currently stuck at the top.
 */
function initStickyAnchorLinks() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            const targetEl = document.querySelector(targetId);
            if (!targetEl) return;

            // Verificar si el enlace apunta a un bloque o algo dentro de un bloque
            const targetBlock = targetEl.classList.contains('block') ? targetEl : targetEl.closest('.block');

            if (targetBlock) {
                e.preventDefault();

                const blocks = Array.from(document.querySelectorAll('.site-main > .block'));
                const targetIndex = blocks.indexOf(targetBlock);

                if (targetIndex !== -1) {
                    let scrollPos = 0;

                    // Sumamos la posición inicial del contenedor general en la página
                    const siteMain = document.querySelector('.site-main');
                    if (siteMain) {
                        scrollPos += siteMain.getBoundingClientRect().top + window.scrollY;
                    }

                    // Sumamos la altura de todos los bloques que están antes que nuestro objetivo
                    for (let i = 0; i < targetIndex; i++) {
                        scrollPos += blocks[i].offsetHeight;
                    }

                    // Removemos .is-bottom preventivamente para asegurar que sea visible al llegar
                    targetBlock.classList.remove('is-bottom');

                    window.scrollTo({
                        top: scrollPos,
                        behavior: 'smooth'
                    });
                }
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initUpcomingEventsClocks();
    initUpcomingEventsScroll();
    initUpcomingEventsOpacityCascade();
    initFaqAccordion();
    initCardGlowEffect();
    initStickyOverlapEffect();
    initUnifiedAnimations();
    initStickyAnchorLinks();
});