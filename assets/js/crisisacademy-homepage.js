/**
 * Handles the dynamic mask-image for the services horizontal scroll.
 */
function initServicesScrollMask() {
    const wrapper = document.querySelector('.services-loop__wrapper');
    const loop = document.querySelector('.services-loop');
    
    if (!wrapper || !loop) return;

    const firstItem = loop.firstElementChild;
    const lastItem = loop.lastElementChild;

    if (!firstItem || !lastItem) return;

    // Helper to update classes
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

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.target === firstItem) {
                wrapper.classList.toggle('at-start', entry.isIntersecting);
            }
            if (entry.target === lastItem) {
                wrapper.classList.toggle('at-end', entry.isIntersecting);
            }
            // Always recalculate scrolling state when an edge changes
            const atStart = wrapper.classList.contains('at-start');
            const atEnd = wrapper.classList.contains('at-end');
            wrapper.classList.toggle('is-scrolling', !atStart && !atEnd);
        });
    }, {
        root: wrapper,
        threshold: 0.95
    });

    observer.observe(firstItem);
    observer.observe(lastItem);

    wrapper.addEventListener('scroll', checkState, { passive: true });
    
    // Initial run
    checkState();
    // Safety timeout for dynamic layouts
    setTimeout(checkState, 100);
}

document.addEventListener('DOMContentLoaded', () => {
    initServicesScrollMask();
});
