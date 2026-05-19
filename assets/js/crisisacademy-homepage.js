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
 * Se activa una sola vez cuando el elemento entra en el viewport.
 */
function initPretextReveal() {
    const CHARS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%&';
    const SETTLE_FRAMES = 3;

    function scramble(el, originalText) {
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
            }
        }

        setTimeout(() => {
            el.classList.add('is-visible');
            tick();
        }, 200);
    }

    // Observer centralizado para escalar animaciones
    let scrambleQueue = [];
    let isProcessing = false;

    function processQueue() {
        if (scrambleQueue.length === 0) {
            isProcessing = false;
            return;
        }
        isProcessing = true;
        
        const { el, originalText } = scrambleQueue.shift();
        scramble(el, originalText);
        
        // Retardo escalonado para el siguiente elemento
        setTimeout(processQueue, 150);
    }

    const io = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                io.unobserve(entry.target);
                
                scrambleQueue.push({
                    el: entry.target,
                    originalText: entry.target._originalText
                });
                
                if (!isProcessing) processQueue();
            }
        });
    }, { 
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    document.querySelectorAll('.pretext-reveal').forEach(el => {
        const originalText = el.textContent.trim();
        el.setAttribute('aria-label', originalText);
        el._originalText = originalText;
        io.observe(el);
    });
}

/**
 * Title Word-by-Word Reveal
 * Se activa una sola vez cuando el elemento entra en el viewport.
 */
function initTitleReveal() {
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

    // Observer centralizado para escalar animaciones
    let titleQueue = [];
    let isTitleProcessing = false;

    function processTitleQueue() {
        if (titleQueue.length === 0) {
            isTitleProcessing = false;
            return;
        }
        isTitleProcessing = true;
        
        const el = titleQueue.shift();
        el.classList.add('is-visible');
        
        // Retardo escalonado para el siguiente título
        setTimeout(processTitleQueue, 200);
    }

    const io = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                io.unobserve(entry.target);
                titleQueue.push(entry.target);
                if (!isTitleProcessing) processTitleQueue();
            }
        });
    }, { 
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    document.querySelectorAll('.title-reveal').forEach(el => {
        el.setAttribute('aria-label', el.textContent.trim());
        wrapWords(el);
        io.observe(el);
    });
}

/**
 * Card Entrance Effect
 * Adds .is-visible to each .card-entrada when it enters the viewport.
 * Fires once per element, then disconnects the observer.
 */
function initCardReveal() {
    // Observer centralizado para escalar animaciones de tarjetas
    let cardQueue = [];
    let isCardProcessing = false;

    function processCardQueue() {
        if (cardQueue.length === 0) {
            isCardProcessing = false;
            return;
        }
        isCardProcessing = true;
        
        // Procesar hasta 2 tarjetas al mismo tiempo para no hacerlo muy lento
        // si hay muchas (como en la grilla de próximos eventos)
        const batch = cardQueue.splice(0, 1);
        batch.forEach(el => el.classList.add('is-visible'));
        
        // Retardo escalonado para el siguiente grupo
        setTimeout(processCardQueue, 150);
    }

    const io = new IntersectionObserver((entries) => {
        // Ordenar las entradas de izquierda a derecha y arriba a abajo
        // para que la cascada siempre se vea natural
        const visibleEntries = entries.filter(e => e.isIntersecting);
        
        if (visibleEntries.length === 0) return;
        
        visibleEntries.sort((a, b) => {
            const rectA = a.target.getBoundingClientRect();
            const rectB = b.target.getBoundingClientRect();
            // Si están más o menos en la misma línea horizontal, ordenar por X
            if (Math.abs(rectA.top - rectB.top) < 50) {
                return rectA.left - rectB.left;
            }
            // Sino, ordenar por Y (arriba hacia abajo)
            return rectA.top - rectB.top;
        });

        visibleEntries.forEach(entry => {
            io.unobserve(entry.target);
            cardQueue.push(entry.target);
        });
        
        if (!isCardProcessing) processCardQueue();
        
    }, { 
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    document.querySelectorAll('.card-reveal').forEach(el => io.observe(el));
}

/**
 * Smooth Scroll for Sticky Anchor Links
 * Bypasses the native anchor jump bug where browsers won't scroll 
 * to a sticky element if it's currently stuck at the top.
 */
function initStickyAnchorLinks() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
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
    initPretextReveal();
    initTitleReveal();
    initCardReveal();
    initStickyAnchorLinks();
});