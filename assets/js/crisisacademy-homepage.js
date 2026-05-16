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
    const cards = document.querySelectorAll('#hero, #main-header .block, .about-item, .signal-item, .how-it-works--card, .cta-container, .cert-container, .cert-panel, .how-works-modal-container');

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
 * Adds .is-bottom to a block when the next block slides over it.
 * Uses a scroll listener + getBoundingClientRect because IntersectionObserver
 * can't detect when a sticky element is visually covered by another.
 */
function initStickyOverlapEffect() {
    const blocks = Array.from(document.querySelectorAll('.site-main > .block'));
    if (blocks.length < 2) return;

    // Force sticky + ascending z-index via inline styles (overrides ID specificity)
    blocks.forEach((block, index) => {
        block.style.position = 'sticky';
        block.style.top = '0';
        block.style.zIndex = index + 1;
    });

    function updateOverlap() {
        blocks.forEach((block, index) => {
            if (index === blocks.length - 1) {
                block.classList.remove('is-bottom');
                return;
            }

            const nextBlock = blocks[index + 1];
            const nextTop = nextBlock.getBoundingClientRect().top;

            // Start fading when the next block is within 40% of the viewport height
            const threshold = window.innerHeight * 0.5;
            block.classList.toggle('is-bottom', nextTop <= threshold);
        });
    }

    window.addEventListener('scroll', updateOverlap, { passive: true });
    updateOverlap(); // Run once on load
}

document.addEventListener('DOMContentLoaded', () => {
    initUpcomingEventsClocks();
    initUpcomingEventsScroll();
    initUpcomingEventsOpacityCascade();
    initFaqAccordion();
    initCardGlowEffect();
    initStickyOverlapEffect();
});