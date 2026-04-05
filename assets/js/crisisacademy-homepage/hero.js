/**
 * Hero Seamless Multi-Parallax Engine + Content Animations
 */
document.addEventListener('DOMContentLoaded', () => {

    /**
     * Mover fondos (Layers)
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
     * Mover objetos (Bicicletas)
     */
    function initBikeParallax(selector, durationSeconds) {
        const bike = document.querySelector(selector);
        if (!bike) return;

        const screenWidth = window.innerWidth;
        const bikeWidth = 100;
        const totalTravel = screenWidth + bikeWidth * 2;
        const fps = 60;
        const pixelsPerFrame = totalTravel / (durationSeconds * fps);
        let currentPos = -bikeWidth;

        function animate() {
            currentPos += pixelsPerFrame;
            if (currentPos > screenWidth + bikeWidth) {
                currentPos = -bikeWidth;
            }
            bike.style.backgroundPositionX = `${currentPos}px`;
            requestAnimationFrame(animate);
        }
        animate();
    }

    /**
     * Animaciones de Contenido (Texto y Botones)
     */
    function initContentAnimations() {
        const contentElements = document.querySelectorAll('.hero--content > *');
        
        contentElements.forEach((el, index) => {
            // Estado inicial sugerido (puedes añadir esto a tu CSS si prefieres)
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.8s cubic-bezier(0.16, 1, 0.3, 1)';
            
            // Retraso escalonado para un efecto "Premium"
            setTimeout(() => {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            }, 400 + (index * 150));
        });
    }

    // Inicializamos todo
    initParallax('.layer-2', 30);
    initParallax('.layer-3', 55);
    initParallax('.layer-4', 75);
    initParallax('.layer-5', 95);
    initParallax('.layer-6', 120);
    initBikeParallax('.bike-1', 10);
    initBikeParallax('.bike-2', 15);
    
    // Lanzamos las animaciones de texto
    initContentAnimations();
});
