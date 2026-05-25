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