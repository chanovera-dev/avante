document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('how-works--complete');
    if (!modal) return;

    const modalWysiwyg = modal.querySelector('.modal-wysiwyg');
    const closeButton = modal.querySelector('.how-works-modal-close');
    const overlay = modal.querySelector('.how-works-modal-overlay');

    // Function to open modal with proper title and WYSIWYG content
    function openModal(title, contentHtml) {
        if (!modalWysiwyg) return;

        // Clear previous content
        modalWysiwyg.innerHTML = '';



        // Inject the rich WYSIWYG content container
        const bodyElement = document.createElement('div');
        bodyElement.innerHTML = contentHtml;
        modalWysiwyg.appendChild(bodyElement);

        // Open state animations and focus settings
        modal.classList.add('is-active');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden'; // Lock background scrolling
    }

    // Function to close modal
    function closeModal() {
        modal.classList.remove('is-active');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = ''; // Restore background scrolling
    }

    // Attach click event handlers to all "More Info" buttons
    const buttons = document.querySelectorAll('.btn-more-info');
    buttons.forEach(button => {
        button.addEventListener('click', function () {
            const card = button.closest('.how-it-works--card');
            if (!card) return;

            const title = button.getAttribute('data-title') || '';
            const contentContainer = card.querySelector('.modal-complete-content');
            const contentHtml = contentContainer ? contentContainer.innerHTML : '';

            openModal(title, contentHtml);
        });
    });

    // Close on Close Button click
    if (closeButton) {
        closeButton.addEventListener('click', closeModal);
    }

    // Close on Overlay click
    if (overlay) {
        overlay.addEventListener('click', closeModal);
    }

    // Close on ESC key press
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modal.classList.contains('is-active')) {
            closeModal();
        }
    });
});
