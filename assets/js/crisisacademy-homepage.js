/**
 * Effect for horizontal scroll mask in Services.
 */
function initServicesScrollMask() {
    const wrapper = document.querySelector('#how-works');
    const loop = document.querySelector('#how-works .content');

    if (!wrapper || !loop) return;

    const firstItem = loop.firstElementChild;
    const lastItem = loop.lastElementChild;
    if (!firstItem || !lastItem) return;

    const checkState = () => {
        const scrollLeft = wrapper.scrollLeft;
        const scrollWidth = wrapper.scrollWidth;
        const clientWidth = wrapper.clientWidth;

        const atStart = scrollLeft <= 10;
        const atEnd = scrollLeft + clientWidth >= scrollWidth - 10;

        wrapper.classList.toggle('at-start', atStart);
        wrapper.classList.toggle('at-end', atEnd);
        wrapper.classList.toggle('is-scrolling', !atStart && !atEnd);
    };

    wrapper.addEventListener('scroll', checkState, { passive: true });
    checkState();
}

/**
 * Handles the overlapping sections effect requested.
 */
function initSectionStacking() {
    const sections = document.querySelectorAll('.site-main > .block');
    const footer = document.querySelector('#main-footer');

    if (sections.length === 0) return;

    // Asignar z-index incremental para asegurar que la siguiente sección flote por encima
    sections.forEach((section, index) => {
        section.style.zIndex = index + 1;
        section.style.position = 'relative';
    });

    const handleScroll = () => {
        const viewportHeight = window.innerHeight;

        sections.forEach((section, index) => {
            const rect = section.getBoundingClientRect();

            // 1. Detectamos por JS cuando el fondo exacto de la sección ya es visible
            if (rect.bottom <= viewportHeight + 1) {

                // Agregamos la clase y la anclamos
                if (!section.classList.contains('is-fixed')) {
                    section.classList.add('is-fixed');
                    section.style.position = 'sticky';

                    const height = section.offsetHeight;
                    const topOffset = height > viewportHeight ? viewportHeight - height : 0;
                    section.style.top = topOffset + 'px';
                }

                // 2. Evaluamos la posición del siguiente bloque para la impresión de "irse por debajo"
                const nextSection = sections[index + 1];
                if (nextSection) {
                    const nextRect = nextSection.getBoundingClientRect();
                    if (nextRect.top <= viewportHeight) {
                        section.classList.add('is-bottom');
                    } else {
                        section.classList.remove('is-bottom');
                    }
                }

            } else {
                // El fondo aún no se ve, la sección fluye normal
                if (section.classList.contains('is-fixed')) {
                    section.classList.remove('is-fixed', 'is-bottom');
                    section.style.position = 'relative';
                    section.style.top = '';
                }
            }
        });
    };

    window.addEventListener('scroll', handleScroll, { passive: true });
    window.addEventListener('resize', () => {
        sections.forEach(s => {
            s.classList.remove('is-fixed', 'is-bottom');
            s.style.position = 'relative';
            s.style.top = '';
        });
        handleScroll();
    }, { passive: true });

    setTimeout(handleScroll, 100);
}

/**
 * Handles the overlapping cards effect inside the calendary loop.
 */
function initCardsStacking() {
    const cards = document.querySelectorAll('.calendary-loop__event');
    if (cards.length === 0) return;

    cards.forEach((card, index) => {
        card.style.zIndex = index + 1;
    });

    const handleScroll = () => {
        cards.forEach((card, index) => {
            const nextCard = cards[index + 1];

            if (nextCard) {
                const nextRect = nextCard.getBoundingClientRect();
                const cardRect = card.getBoundingClientRect();
                const staticBottom = cardRect.top + card.offsetHeight;

                if (nextRect.top <= staticBottom + 15) {
                    card.classList.add('is-bottom');
                } else {
                    card.classList.remove('is-bottom');
                }
            }
        });
    };

    window.addEventListener('scroll', handleScroll, { passive: true });
    setTimeout(handleScroll, 100);
}

/**
 * Preparación para el efecto de palabras deslizantes.
 */
function prepareTitles() {
    const titles = document.querySelectorAll('.scramble-words');
    titles.forEach(title => {
        if (title.dataset.prepared) return;
        title.dataset.prepared = 'true';

        if (!title.hasAttribute('aria-label')) {
            title.setAttribute('aria-label', title.textContent.trim().replace(/\s+/g, ' '));
        }

        const walker = document.createTreeWalker(title, NodeFilter.SHOW_TEXT, null, false);
        const textNodes = [];
        while (walker.nextNode()) {
            textNodes.push(walker.currentNode);
        }

        let globalWordIndex = 0;
        textNodes.forEach(node => {
            const text = node.nodeValue;
            if (!text.trim() && text.includes('\n')) return;

            const fragment = document.createDocumentFragment();
            const parts = text.split(/(\s+)/);

            parts.forEach(part => {
                if (part === '') return;
                const inner = document.createElement('span');
                inner.textContent = part;
                inner.className = 'word-slide-anim';

                if (part.trim().length > 0) {
                    inner.style.transitionDelay = `${globalWordIndex * 45}ms`;
                    globalWordIndex++;
                } else {
                    inner.style.whiteSpace = 'pre-wrap';
                    inner.style.transitionDelay = `${Math.max(0, globalWordIndex - 1) * 45}ms`;
                }
                fragment.appendChild(inner);
            });
            node.parentNode.replaceChild(fragment, node);
        });
    });
}

/**
 * Preparación para el efecto de letras aleatorias.
 */
function prepareScramble() {
    const scrambleLetters = document.querySelectorAll('.scramble-letters');
    scrambleLetters.forEach(el => {
        if (!el.dataset.scrambleOriginal) {
            const originalText = el.textContent.trim();
            el.dataset.scrambleOriginal = originalText;
            el.setAttribute('aria-label', originalText);
        }
    });
}

/**
 * Activa el efecto de scramble para un elemento.
 */
function triggerScramble(el) {
    const originalText = el.dataset.scrambleOriginal;
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    let iteration = 0;

    el.classList.add('is-visible');
    clearInterval(el.scrambleInterval);

    el.scrambleInterval = setInterval(() => {
        el.textContent = originalText
            .split('')
            .map((letter, index) => {
                if (index < iteration) return originalText[index];
                if (originalText[index] === ' ') return ' ';
                return chars[Math.floor(Math.random() * chars.length)];
            })
            .join('');

        iteration += .5;

        if (iteration >= originalText.length) {
            clearInterval(el.scrambleInterval);
            el.textContent = originalText;
        }
    }, 30);
}

/**
 * Orquestación de animaciones de entrada individuales con escalonado para grupos.
 */
function initStaggeredEntrances() {
    const animatables = document.querySelectorAll('.scramble-letters, .scramble-words, .btn.primary, .animate-in--fadein, .animate-in--scale-up');
    if (animatables.length === 0) return;

    prepareTitles();
    prepareScramble();

    let staggerCounter = 0;
    let staggerTimeout;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            const el = entry.target;

            if (entry.isIntersecting) {
                // Si varios elementos entran casi a la vez, los escalonamos
                clearTimeout(staggerTimeout);

                const timeoutId = setTimeout(() => {
                    if (el.classList.contains('scramble-letters')) {
                        triggerScramble(el);
                    } else {
                        el.classList.add('is-visible');
                    }
                    staggerCounter = 0; // Reset tras la ráfaga
                }, staggerCounter * 100);

                el.dataset.animTimeout = timeoutId;

                staggerCounter++;

                // Reiniciamos el contador si no entran más elementos en breve
                staggerTimeout = setTimeout(() => { staggerCounter = 0; }, 150);
            } else {
                // Cuando salgan del observer se quita la clase is-visible
                if (el.dataset.animTimeout) {
                    clearTimeout(parseInt(el.dataset.animTimeout, 10));
                    delete el.dataset.animTimeout;
                }
                if (el.scrambleInterval) {
                    clearInterval(el.scrambleInterval);
                }
                el.classList.remove('is-visible');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px' // Margen pequeño para que no se dispare "tan" al borde
    });

    animatables.forEach(el => observer.observe(el));
}

function initHowWorksModal() {
    const modal = document.querySelector('#how-works--complete');
    if (!modal) return;

    const modalTitle = modal.querySelector('.modal-title');
    const modalWysiwyg = modal.querySelector('.modal-wysiwyg');
    const closeBtn = modal.querySelector('.how-works-modal-close');
    const overlay = modal.querySelector('.how-works-modal-overlay');

    const openModal = (title, htmlContent) => {
        if (modalTitle) modalTitle.textContent = title;
        if (modalWysiwyg) modalWysiwyg.innerHTML = htmlContent;
        modal.classList.add('is-active');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    };

    const closeModal = () => {
        modal.classList.remove('is-active');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    };

    // Escuchar clicks en los botones de las tarjetas
    document.querySelectorAll('.how-it-works--card .btn-more-info').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const card = button.closest('.how-it-works--card');
            if (!card) return;

            const title = button.getAttribute('data-title') || '';
            const template = card.querySelector('.modal-complete-content');
            let htmlContent = template ? template.innerHTML : '';

            // Limpiar cualquier h3 duplicado que sea igual al título
            const parser = new DOMParser();
            const doc = parser.parseFromString(htmlContent, 'text/html');
            const h3 = doc.querySelector('h3');
            if (h3 && h3.textContent.trim() === title.trim()) {
                h3.remove();
            }
            htmlContent = doc.body.innerHTML;

            openModal(title, htmlContent);
        });
    });

    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (overlay) overlay.addEventListener('click', closeModal);

    // Cerrar al presionar la tecla Esc
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('is-active')) {
            closeModal();
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initServicesScrollMask();
    initSectionStacking();
    initCardsStacking();
    initStaggeredEntrances(); // Reemplaza a initScrambleEffect, initTitlesFadeEffect e initButtonsFadeEffect
    initHowWorksModal();
});
