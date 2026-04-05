/**
 * Hero Seamless Multi-Parallax Engine
 * Maneja múltiples capas con duraciones independientes y auto-detección de ancho.
 */
document.addEventListener('DOMContentLoaded', () => {

    /**
     * Mover fondos (Layers 1-6)
     */
    function initParallax(selector, durationSeconds) {
        const layer = document.querySelector(selector);
        if (!layer) return;

        const style = window.getComputedStyle(layer);
        const bgUrl = style.backgroundImage.slice(4, -1).replace(/"/g, "");

        const img = new Image();
        img.src = bgUrl;
        img.onload = () => {
            const imgWidth = img.naturalWidth;
            const fps = 60;
            const pixelsPerFrame = imgWidth / (durationSeconds * fps);
            let currentPos = 0;

            function animate() {
                currentPos -= pixelsPerFrame;
                const xPos = currentPos % imgWidth;
                layer.style.backgroundPositionX = `${xPos}px`;
                requestAnimationFrame(animate);
            }
            animate();
        };
    }

    /**
     * Mover objetos que atraviesan la pantalla (Bicicletas)
     */
    function initBikeParallax(selector, durationSeconds) {
        const bike = document.querySelector(selector);
        if (!bike) return;

        // Configuramos la distancia de viaje (pantalla completa + margen)
        const screenWidth = window.innerWidth;
        const bikeWidth = 100; // Margen para que desaparezca del todo
        const totalTravel = screenWidth + bikeWidth * 2;

        const fps = 60;
        const pixelsPerFrame = totalTravel / (durationSeconds * fps);

        // Empezamos desde la izquierda (fuera de pantalla)
        let currentPos = -bikeWidth;

        function animate() {
            currentPos += pixelsPerFrame;

            // Si llega al final de la derecha, reinicia a la izquierda
            if (currentPos > screenWidth + bikeWidth) {
                currentPos = -bikeWidth;
            }

            bike.style.backgroundPositionX = `${currentPos}px`;
            requestAnimationFrame(animate);
        }
        animate();
    }

    // Inicializamos las capas de fondo
    initParallax('.layer-2', 30);
    initParallax('.layer-3', 55);
    initParallax('.layer-4', 75);
    initParallax('.layer-5', 95);
    initParallax('.layer-6', 120);

    // Inicializamos la bicicleta para que cruce cada N segundos
    initBikeParallax('.bike-1', 10);
    initBikeParallax('.bike-2', 15);
});
