const wrapper = document.querySelector('.clouds--wrapper');
const clouds = document.querySelector('.clouds');

if (wrapper && clouds && window.gsap) {
    const cloudWidth = clouds.offsetWidth;
    let speed = 0.5;

    const clouds2 = clouds.cloneNode(true);
    const clouds3 = clouds.cloneNode(true);
    wrapper.appendChild(clouds2);
    wrapper.appendChild(clouds3);

    let cloudsArray = [clouds, clouds2, clouds3];
    cloudsArray.forEach((c, i) => {
        c.style.position = "absolute";
        c.style.left = "0px";
        window.gsap.set(c, { x: i * cloudWidth });
    });

    // Duración para recorrer el ancho de una nube a velocidad constante
    const duration = cloudWidth / (speed * 60);

    cloudsArray.forEach((c, i) => {
        window.gsap.to(c, {
            x: `+=${cloudWidth * 3}`,
            ease: "none",
            duration: duration * 3,
            repeat: -1,
            modifiers: {
                x: window.gsap.utils.unitize((x) => {
                    const val = parseFloat(x);
                    const totalSpan = cloudWidth * 3;
                    const offset = i * cloudWidth;
                    
                    let relativeX = (val - offset) % totalSpan;
                    if (relativeX < 0) relativeX += totalSpan;
                    
                    return relativeX - cloudWidth + offset;
                })
            }
        });
    });
}