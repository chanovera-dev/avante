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