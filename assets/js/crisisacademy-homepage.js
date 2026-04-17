/**
 * Effect for horizontal scroll mask in Services.
 */
function initServicesScrollMask() {
    const wrapper = document.querySelector('.services-loop__wrapper');
    const loop = document.querySelector('.services-loop');
    
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
            // Excluir la última sección (#training) para que fluya de manera normal con el footer
            if (index === sections.length - 1) return;

            const rect = section.getBoundingClientRect();
            
            // 1. Detectamos por JS cuando el fondo exacto de la sección ya es visible
            // Le damos +1px de tolerancia por posibles decimales
            if (rect.bottom <= viewportHeight + 1) {
                
                // Agregamos la clase y la anclamos
                if (!section.classList.contains('is-fixed')) {
                    section.classList.add('is-fixed');
                    section.style.position = 'sticky';
                    
                    // Calculamos matemáticamente dónde queda su "top"
                    // Así el final de la sección siempre será exactamente visible, sin saltos
                    const height = section.offsetHeight;
                    const topOffset = height > viewportHeight ? viewportHeight - height : 0;
                    section.style.top = topOffset + 'px';
                }
                
                // 2. Evaluamos la posición del siguiente bloque para la impresión de "irse por debajo"
                const nextSection = sections[index + 1];
                if (nextSection) {
                    const nextRect = nextSection.getBoundingClientRect();
                    // Si el siguiente elemento ya empezó a entrar en pantalla
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
        // Asegura que las posteriores floten por encima
        card.style.zIndex = index + 1;
    });

    const handleScroll = () => {
        cards.forEach((card, index) => {
            const nextCard = cards[index + 1];
            
            if (nextCard) {
                const nextRect = nextCard.getBoundingClientRect();
                const cardRect = card.getBoundingClientRect();
                
                // Usamos offsetHeight sumado al `top` top para obtener un "punto fijo" 
                // inquebrantable, y evitar un ciclo de jitter al ejecutar el `scale`
                const staticBottom = cardRect.top + card.offsetHeight;
                
                // Si la siguiente tarjeta "alcanza" la altura del fondo de la tarjeta actual
                // (Le damos +15px de margen para activar un milisegundo antes)
                if (nextRect.top <= staticBottom + 15) {
                    card.classList.add('is-bottom');
                } else {
                    card.classList.remove('is-bottom');
                }
            }
        });
    };

    window.addEventListener('scroll', handleScroll, { passive: true });
    // Refrescar en resize no es estrictamente necesario ya que usamos un cálculo estático, pero bueno
    setTimeout(handleScroll, 100);
}

document.addEventListener('DOMContentLoaded', () => {
    initServicesScrollMask();
    initSectionStacking();
    initCardsStacking();
    initScrambleEffect();
    initTitlesFadeEffect();
    initButtonsFadeEffect();
});

/**
 * Slide-up and scale-in effect for main buttons.
 */
function initButtonsFadeEffect() {
    const buttons = document.querySelectorAll('.site-main .btn.primary.large');
    if (buttons.length === 0) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Add a small delay so they pop up slightly after the title sequence starts
                setTimeout(() => {
                    entry.target.classList.add('is-visible');
                }, 200);
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1 
    });

    buttons.forEach(el => observer.observe(el));
}

/**
 * Slide-up word-by-word effect for main titles.
 */
function initTitlesFadeEffect() {
    const titles = document.querySelectorAll('.page-title, .title-section');
    if (titles.length === 0) return;

    // 1. Preparar las palabras rompiendo subnodos de texto en spans pero sin tocar HTML
    titles.forEach(title => {
        if (title.dataset.prepared) return;
        title.dataset.prepared = 'true';
        
        // Guardamos el texto puro en aria-label para que los lectores
        // de pantalla lo lean fluidamente y no palabra por palabra fragmentada
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
            // Si es puro salto de línea de formato de código, lo dejamos quieto (no lo envolvemos)
            if (!text.trim() && text.includes('\n')) return; 
            
            const fragment = document.createDocumentFragment();
            // Desglosar por palabras conservando el map de espacios exacto
            const parts = text.split(/(\s+)/); 
            
            parts.forEach(part => {
                if (part === '') return;
                
                const inner = document.createElement('span');
                inner.textContent = part;
                inner.className = 'word-slide-anim';
                
                if (part.trim().length > 0) {
                    // Es una palabra
                    inner.style.transitionDelay = `${globalWordIndex * 45}ms`;
                    globalWordIndex++;
                } else {
                    // Es un espacio: aplicamos el mismo retraso que la palabra anterior para que viaje junto a ella
                    // y usamos pre-wrap para que el inline-block no colapse
                    inner.style.whiteSpace = 'pre-wrap';
                    inner.style.transitionDelay = `${Math.max(0, globalWordIndex - 1) * 45}ms`;
                }
                
                fragment.appendChild(inner);
            });
            node.parentNode.replaceChild(fragment, node);
        });
    });

    // 2. Animar al hacer intersección
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                // observer.unobserve(entry.target); // Si quisieras que no lo vuelva a hacer al subir, manténla
            } else {
                // Removemos la clase si subimos para que vuelva a hacer el intro al bajar (opcional)
                entry.target.classList.remove('is-visible');
            }
        });
    }, {
        threshold: 0.15
    });

    titles.forEach(el => observer.observe(el));
}

/**
 * Text scramble effect for .span-pretext elements upon entering the viewport.
 */
function initScrambleEffect() {
    const pretexts = document.querySelectorAll('.span-pretext');
    if (pretexts.length === 0) return;

    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                const originalText = el.dataset.scrambleOriginal || el.textContent.trim();
                
                // Mostrar visualmente el contenedor al intersectar
                el.classList.add('is-visible');
                
                // Save original text to data attribute to prevent losing it if re-triggered
                if (!el.dataset.scrambleOriginal) {
                    el.dataset.scrambleOriginal = originalText;
                    // Añadir aria-label para accesibilidad (lectores de pantalla ignoran las letras revueltas y leen la original)
                    el.setAttribute('aria-label', originalText);
                }

                
                let iteration = 0;
                clearInterval(el.scrambleInterval);

                el.scrambleInterval = setInterval(() => {
                    el.textContent = originalText
                        .split('')
                        .map((letter, index) => {
                            if (index < iteration) {
                                return originalText[index];
                            }
                            // Mantén los espacios en blanco
                            if (originalText[index] === ' ') return ' ';
                            
                            // Letra aleatoria
                            return chars[Math.floor(Math.random() * chars.length)];
                        })
                        .join('');

                    // Avanzar más rápido para que la duración total sea menor,
                    // conservando los 30ms de "parpadeo" de las letras.
                    iteration += 1.5;

                    if (iteration >= originalText.length) {
                        clearInterval(el.scrambleInterval);
                        el.textContent = originalText;
                    }
                }, 30);

                // Des-observar si solo queremos que el efecto ocurra una vez (al entrar por primera vez)
                // Si quieres que suceda cada vez que entran en pantalla, puedes quitar esta línea:
                observer.unobserve(el);
            }
        });
    }, {
        threshold: 0.1 // Se activa cuando al menos el 10% del elemento es visible
    });

    pretexts.forEach(el => observer.observe(el));
}
