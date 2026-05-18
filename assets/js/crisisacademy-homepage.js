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
    const cards = document.querySelectorAll('#hero, .about-item, .signal-item, .how-it-works--card, .cta-container, .cert-container, .cert-panel, .how-works-modal-container');

    cards.forEach(card => {
        let rect;

        // Capture boundary ONCE when mouse enters to avoid recursive render loops
        card.addEventListener('mouseenter', () => {
            rect = card.getBoundingClientRect();
        });

        card.addEventListener('mousemove', e => {
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

    // Force sticky + ascending z-index (overrides ID-level specificity)
    blocks.forEach((block, index) => {
        block.style.position = 'sticky';
        block.style.zIndex = index + 1;
    });

    // Recalculate `top` for each block based on its full content height.
    // scrollHeight is used (not offsetHeight) to get the real height including
    // any content that may not yet have rendered at DOMContentLoaded.
    function applyStickyTops() {
        const vh = window.innerHeight;
        blocks.forEach(block => {
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
 * Pretext Scramble Reveal
 *
 * Targets every .pretext-reveal element. When it enters the viewport:
 *   1. Runs a character-scramble animation that settles on the real text.
 *   2. Adds .is-visible so CSS transitions it to opacity: 1.
 *
 * When the parent .block gains .is-bottom, CSS fades it out after 10 s.
 * When .is-bottom is removed, the scramble re-runs from scratch.
 *
 * Each element gets aria-label set to its original text for SEO.
 */
function initPretextReveal() {
    const CHARS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%&';
    const SETTLE_FRAMES = 3;   // frames per letter (~32ms each at 60fps)

    /**
     * Scramble animation — resolves one letter at a time left→right.
     * @param {HTMLElement} el
     * @param {string} originalText
     */
    function scramble(el, originalText) {
        // Cancel any running animation on this element
        if (el._scrambleId) cancelAnimationFrame(el._scrambleId);
        el.classList.remove('is-visible');

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

            // Write scrambled text while keeping the aria-label intact
            el.textContent = output;

            if (frame < total) {
                frame++;
                el._scrambleId = requestAnimationFrame(tick);
            } else {
                el.textContent = originalText;
                el._scrambleId = null;
            }
        }

        // Slight delay so CSS opacity fade-in starts visibly before text settles
        setTimeout(() => {
            el.classList.add('is-visible');
            tick();
        }, 200);
    }

    /** Set up IntersectionObserver for a single element */
    function observe(el, originalText) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Element entered viewport — cancel any pending reset and scramble in
                    if (el._resetTimer) {
                        clearTimeout(el._resetTimer);
                        el._resetTimer = null;
                    }
                    scramble(el, originalText);
                } else {
                    // Element left viewport — reset after 10 s
                    if (!el._resetTimer) {
                        el._resetTimer = setTimeout(() => reset(el), 10000);
                    }
                }
            });
        }, { threshold: 0.2 });

        io.observe(el);
        return io;
    }

    /** Reset element to initial invisible state */
    function reset(el) {
        if (el._scrambleId) {
            cancelAnimationFrame(el._scrambleId);
            el._scrambleId = null;
        }
        if (el._resetTimer) {
            clearTimeout(el._resetTimer);
            el._resetTimer = null;
        }
        el.classList.remove('is-visible');
    }

    /**
     * Watch the parent .block for .is-bottom changes.
     * - .is-bottom added  → reset element to initial invisible state
     * - .is-bottom removed → re-run scramble from scratch
     */
    function watchParentBlock(el, originalText) {
        const block = el.closest('.block');
        if (!block) return;

        let wasBottom = false;

        const mo = new MutationObserver(() => {
            const isBottom = block.classList.contains('is-bottom');

            if (isBottom && !wasBottom) {
                // Section just got covered — reset after 10 s if still covered
                if (!el._resetTimer) {
                    el._resetTimer = setTimeout(() => reset(el), 10000);
                }
            }

            if (!isBottom && wasBottom) {
                // Section re-appeared — cancel any pending reset and scramble in
                if (el._resetTimer) {
                    clearTimeout(el._resetTimer);
                    el._resetTimer = null;
                }
                scramble(el, originalText);
            }

            wasBottom = isBottom;
        });

        mo.observe(block, { attributes: true, attributeFilter: ['class'] });
    }

    document.querySelectorAll('.pretext-reveal').forEach(el => {
        const originalText = el.textContent.trim();

        // SEO: preserve readable text for screen readers & crawlers
        el.setAttribute('aria-label', originalText);

        observe(el, originalText);
        watchParentBlock(el, originalText);
    });
}

/**
 * Title Word-by-Word Reveal
 *
 * Targets every .title-reveal element. On init, wraps each word in a
 * <span class="title-word" style="--word-index: N"> so CSS can stagger
 * the slide-up transition per word.
 *
 * Lifecycle mirrors initPretextReveal():
 *   - Visible in viewport   → add .is-visible (words slide up)
 *   - Section gets .is-bottom → reset after 1 s (words snap back down)
 *   - Section loses .is-bottom → re-add .is-visible (words slide up again)
 */
function initTitleReveal() {
    /**
     * Walk all text nodes inside el and wrap individual words in spans.
     * Preserves existing HTML structure (e.g. <u>, <strong>, <em>).
     */
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

    function reset(el) {
        if (el._titleResetTimer) {
            clearTimeout(el._titleResetTimer);
            el._titleResetTimer = null;
        }
        el.classList.remove('is-visible');
    }

    function observe(el) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Element entered viewport — cancel any pending reset and show
                    if (el._titleResetTimer) {
                        clearTimeout(el._titleResetTimer);
                        el._titleResetTimer = null;
                    }
                    el.classList.add('is-visible');
                } else {
                    // Element left viewport — reset after 10 s
                    if (!el._titleResetTimer) {
                        el._titleResetTimer = setTimeout(() => reset(el), 10000);
                    }
                }
            });
        }, { threshold: 0.2 });

        io.observe(el);
    }

    function watchParentBlock(el) {
        const block = el.closest('.block');
        if (!block) return;

        let wasBottom = false;

        const mo = new MutationObserver(() => {
            const isBottom = block.classList.contains('is-bottom');

            if (isBottom && !wasBottom) {
                // Section just got covered — reset after 10 s
                if (!el._titleResetTimer) {
                    el._titleResetTimer = setTimeout(() => reset(el), 10000);
                }
            }

            if (!isBottom && wasBottom) {
                // Section re-appeared — cancel pending reset, re-trigger
                if (el._titleResetTimer) {
                    clearTimeout(el._titleResetTimer);
                    el._titleResetTimer = null;
                }
                el.classList.add('is-visible');
            }

            wasBottom = isBottom;
        });

        mo.observe(block, { attributes: true, attributeFilter: ['class'] });
    }

    document.querySelectorAll('.title-reveal').forEach(el => {
        // SEO: preserve full readable text for screen readers & crawlers
        // Must be set BEFORE wrapWords() replaces text nodes with spans
        el.setAttribute('aria-label', el.textContent.trim());

        wrapWords(el);
        observe(el);
        watchParentBlock(el);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initUpcomingEventsClocks();
    initUpcomingEventsScroll();
    initUpcomingEventsOpacityCascade();
    initFaqAccordion();
    initCardGlowEffect();
    initStickyOverlapEffect();
    initPretextReveal();
    initTitleReveal();
});