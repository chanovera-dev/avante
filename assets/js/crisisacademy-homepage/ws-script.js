document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('cta-whatsapp-form');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const phone = form.getAttribute('data-phone');
            const name = document.getElementById('wa_name').value.trim();
            const email = document.getElementById('wa_email').value.trim();
            const userPhone = document.getElementById('wa_phone').value.trim();
            const interest = document.getElementById('wa_interest').value.trim();

            let message = `*Nueva Inscripción / Solicitud*\n\n`;
            message += `*Nombre:* ${name}\n`;
            message += `*Correo:* ${email}\n`;
            message += `*Teléfono:* ${userPhone}\n`;
            message += `*Interés:* ${interest}\n`;

            const waUrl = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
            window.open(waUrl, '_blank');

            // Ocultar campos y mostrar éxito
            const formFields = form.querySelector('.cta-form-fields');
            const successMsg = form.querySelector('.cta-success-message');
            if (formFields && successMsg) {
                formFields.style.display = 'none';
                successMsg.style.display = 'block';
            }
        });

        // Lógica de botón reset
        const resetBtn = document.getElementById('cta-reset-btn');
        if (resetBtn) {
            resetBtn.addEventListener('click', function () {
                form.reset();
                const formFields = form.querySelector('.cta-form-fields');
                const successMsg = form.querySelector('.cta-success-message');
                if (formFields && successMsg) {
                    formFields.style.display = 'block';
                    successMsg.style.display = 'none';
                }
            });
        }
    }
});