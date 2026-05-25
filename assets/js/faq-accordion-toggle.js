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