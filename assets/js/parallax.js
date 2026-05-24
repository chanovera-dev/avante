function initParallax() {
    const parallaxImage = document.querySelector(".background-parallax");

    if (!parallaxImage) return;

    if (window.gsap && window.ScrollTrigger) {
        window.gsap.registerPlugin(window.ScrollTrigger);

        let speed = parseFloat(parallaxImage.dataset.speed) || 0.5;

        window.gsap.to(parallaxImage, {
            y: () => {
                return window.innerHeight * speed;
            },
            ease: "none",
            scrollTrigger: {
                trigger: document.body,
                start: "top top",
                end: "bottom bottom",
                scrub: true,
                invalidateOnRefresh: true
            }
        });
    } else {
        // Fallback pasivo si GSAP no estuviera disponible
        window.addEventListener("scroll", function () {
            let scrollY = window.scrollY;
            let speed = parseFloat(parallaxImage.dataset.speed) || 0.5;

            parallaxImage.style.transform = `translateY(${scrollY * speed}px)`;
        }, { passive: true });
    }
}
document.addEventListener("DOMContentLoaded", initParallax);