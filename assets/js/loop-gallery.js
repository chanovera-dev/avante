const initializedGalleries = new WeakSet()

const displacementVertexShader = `
varying vec2 vUv;
void main() {
    vUv = uv;
    gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
}
`;

const displacementFragmentShader = `
varying vec2 vUv;
uniform sampler2D currentImage;
uniform sampler2D nextImage;
uniform float dispFactor;
uniform vec2 currentRatio;
uniform vec2 nextRatio;

vec2 getCoverUv(vec2 uv, vec2 ratio) {
    return vec2(
        uv.x * ratio.x + (1.0 - ratio.x) * 0.5,
        uv.y * ratio.y + (1.0 - ratio.y) * 0.5
    );
}

void main() {
    vec2 uv = vUv;
    float intensity = 0.3;
    
    vec2 uvCurrent = getCoverUv(uv, currentRatio);
    vec2 uvNext = getCoverUv(uv, nextRatio);

    vec4 orig1 = texture2D(currentImage, uvCurrent);
    vec4 orig2 = texture2D(nextImage, uvNext);
    
    vec4 _currentImage = texture2D(currentImage, vec2(uvCurrent.x, uvCurrent.y + dispFactor * (orig2.r * intensity)));
    vec4 _nextImage = texture2D(nextImage, vec2(uvNext.x, uvNext.y + (1.0 - dispFactor) * (orig1.r * intensity)));
    
    gl_FragColor = mix(_currentImage, _nextImage, dispFactor);
}
`;

function setupWebGLSlider(wrapper, images, firstIndex = 0) {
    if (!window.THREE) return null;

    const width = wrapper.offsetWidth;
    const height = wrapper.offsetHeight;
    if (width === 0 || height === 0) return null;

    const renderer = new THREE.WebGLRenderer({ antialias: false, alpha: true });
    renderer.setPixelRatio(window.devicePixelRatio);
    renderer.setSize(width, height);
    renderer.domElement.style.position = 'absolute';
    renderer.domElement.style.top = '0';
    renderer.domElement.style.left = '0';
    renderer.domElement.style.width = '100%';
    renderer.domElement.style.height = '100%';
    renderer.domElement.style.zIndex = '0';
    renderer.domElement.style.pointerEvents = 'none';
    wrapper.appendChild(renderer.domElement);

    const scene = new THREE.Scene();
    const camera = new THREE.OrthographicCamera(width / -2, width / 2, height / 2, height / -2, 1, 1000);
    camera.position.z = 1;

    const getRatio = (texture) => {
        const w = wrapper.offsetWidth;
        const h = wrapper.offsetHeight;
        if (!texture || !texture.image || w === 0 || h === 0) return new THREE.Vector2(1, 1);
        const s = w / h;
        const i = texture.image.width / texture.image.height;
        return (s > i) ? new THREE.Vector2(1, i / s) : new THREE.Vector2(s / i, 1);
    };

    const loader = new THREE.TextureLoader();
    loader.crossOrigin = "anonymous";
    const sliderImages = images.map((img, idx) => {
        const texture = loader.load(img.src, (tex) => {
            if (idx === firstIndex) {
                mat.uniforms.currentRatio.value = getRatio(tex);
                mat.uniforms.nextRatio.value = getRatio(tex);
            }
        });
        texture.magFilter = texture.minFilter = THREE.LinearFilter;
        if (renderer.capabilities && renderer.capabilities.getMaxAnisotropy) {
            texture.anisotropy = renderer.capabilities.getMaxAnisotropy();
        }
        return texture;
    });

    const mat = new THREE.ShaderMaterial({
        uniforms: {
            dispFactor: { type: "f", value: 0.0 },
            currentImage: { type: "t", value: sliderImages[firstIndex] },
            nextImage: { type: "t", value: sliderImages[firstIndex] },
            currentRatio: { type: "v2", value: new THREE.Vector2(1, 1) },
            nextRatio: { type: "v2", value: new THREE.Vector2(1, 1) }
        },
        vertexShader: displacementVertexShader,
        fragmentShader: displacementFragmentShader,
        transparent: true
    });

    const geometry = new THREE.PlaneGeometry(width, height, 1);
    const object = new THREE.Mesh(geometry, mat);
    scene.add(object);

    const animate = () => {
        if (!wrapper.isConnected) return;
        requestAnimationFrame(animate);
        renderer.render(scene, camera);
    };
    animate();

    return {
        transitionTo: (index, onComplete) => {
            if (!sliderImages[index]) return;
            mat.uniforms.nextImage.value = sliderImages[index];
            mat.uniforms.nextRatio.value = getRatio(sliderImages[index]);
            mat.uniforms.nextImage.needsUpdate = true;

            const gsapObj = window.gsap || (window.TweenLite ? { to: window.TweenLite.to } : null);
            if (gsapObj) {
                gsapObj.to(mat.uniforms.dispFactor, {
                    value: 1,
                    duration: 1.2,
                    ease: "expo.inOut",
                    onComplete: () => {
                        mat.uniforms.currentImage.value = sliderImages[index];
                        mat.uniforms.currentRatio.value = getRatio(sliderImages[index]);
                        mat.uniforms.currentImage.needsUpdate = true;
                        mat.uniforms.dispFactor.value = 0.0;
                        if (onComplete) onComplete();
                    }
                });
            } else {
                mat.uniforms.currentImage.value = sliderImages[index];
                mat.uniforms.currentRatio.value = getRatio(sliderImages[index]);
                mat.uniforms.dispFactor.value = 0.0;
                if (onComplete) onComplete();
            }
        },
        resize: () => {
            const w = wrapper.offsetWidth;
            const h = wrapper.offsetHeight;
            renderer.setSize(w, h);
            camera.left = w / -2;
            camera.right = w / 2;
            camera.top = h / 2;
            camera.bottom = h / -2;
            camera.updateProjectionMatrix();
            if (object.geometry) object.geometry.dispose();
            object.geometry = new THREE.PlaneGeometry(w, h, 1);
            mat.uniforms.currentRatio.value = getRatio(mat.uniforms.currentImage.value);
            mat.uniforms.nextRatio.value = getRatio(mat.uniforms.nextImage.value);
        }
    };
}

function initGallery(wrapper) {
    if (initializedGalleries.has(wrapper)) return
    initializedGalleries.add(wrapper)

    const gallery = wrapper.querySelector(".gallery")
    const originalSlides = Array.from(wrapper.querySelectorAll(".gallery > *"))
    const navigation = wrapper.querySelector(".gallery-navigation")
    const bulletsWrapper = wrapper.querySelector(".loop-gallery-bullets")

    if (!gallery || originalSlides.length === 0 || !bulletsWrapper) return

    wrapper.style.height = "100%"
    wrapper.style.overflow = "hidden"
    wrapper.style.display = "grid"
    gallery.style.display = "flex"
    gallery.style.height = "100%"

    const firstClone = originalSlides[0].cloneNode(true)
    const lastClone = originalSlides[originalSlides.length - 1].cloneNode(true)
    gallery.prepend(lastClone)
    gallery.appendChild(firstClone)

    const slides = gallery.querySelectorAll(".gallery > *")
    const totalSlides = slides.length
    const visibleSlides = originalSlides.length

    let currentSlide = 1
    let animationFrame
    let isAnimating = false

    let webglSlider = null
    const images = originalSlides.map(s => s.querySelector('img')).filter(i => !!i)

    if (window.THREE && images.length > 0) {
        webglSlider = setupWebGLSlider(wrapper, images, 0)
        if (webglSlider) {
            gallery.style.opacity = "0"
            window.addEventListener("resize", () => webglSlider.resize())
        }
    }

    gallery.style.width = `${100 * totalSlides}%`
    slides.forEach(slide => {
        slide.style.width = `${100 / totalSlides}%`
        slide.style.transition = "transform 0.5s ease, opacity 0.5s ease"
        slide.style.transform = "scale(1)"
        slide.style.opacity = "0.75"
        slide.style.position = "relative"
    })

    gallery.style.transform = `translateX(-${(100 / totalSlides) * currentSlide}%)`

    navigation.style.display = "flex"
    navigation.style.justifyContent = "space-between"
    navigation.style.alignItems = "center"

    bulletsWrapper.innerHTML = ""
    const bigGalleryIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-images" viewBox="0 0 16 16"><path d="M4.502 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3"/><path d="M14.002 13a2 2 0 0 1-2 2h-10a2 2 0 0 1-2-2V5A2 2 0 0 1 2 3a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-1.998 2M14 2H4a1 1 0 0 0-1 1h9.002a2 2 0 0 1 2 2v7A1 1 0 0 0 15 11V3a1 1 0 0 0-1-1M2.002 4a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1z"/></svg>`
    if (originalSlides.length > 5) {
        bulletsWrapper.style.display = "flex"
        bulletsWrapper.style.gap = "0.5rem"
        bulletsWrapper.style.alignItems = "center"
        bulletsWrapper.innerHTML = `${bigGalleryIcon} ${originalSlides.length}`

    } else {
        bulletsWrapper.style.display = "flex"
        bulletsWrapper.style.gap = "1rem"
        bulletsWrapper.style.alignItems = "center"
        originalSlides.forEach((_, index) => {
            const bullet = document.createElement("div")
            bullet.classList.add("bullet")
            if (index === 0) bullet.classList.add("active")
            bullet.dataset.index = index
            bulletsWrapper.appendChild(bullet)
        })
    }

    const bullets = bulletsWrapper.querySelectorAll(".bullet")

    function updateActiveClasses(index = currentSlide, shouldGrow = true) {
        slides.forEach(slide => {
            slide.classList.remove("active")
            slide.style.transform = "scale(1)"
            slide.style.opacity = "0.75"
        })

        if (shouldGrow && slides[index]) {
            slides[index].classList.add("active")
            slides[index].style.transform = "scale(1)"
            slides[index].style.opacity = "1"
        }

        const realIndex = ((index - 1) % visibleSlides + visibleSlides) % visibleSlides
        bullets.forEach((btn, i) => btn.classList.toggle("active", i === realIndex))
    }

    function handleInfiniteLoop() {
        if (currentSlide === 0) {
            currentSlide = visibleSlides
        } else if (currentSlide === totalSlides - 1) {
            currentSlide = 1
        } else {
            return false
        }

        gallery.style.transition = "none"
        slides.forEach(s => s.style.transition = "none")
        gallery.style.transform = `translateX(-${(100 / totalSlides) * currentSlide}%)`

        requestAnimationFrame(() => {
            gallery.style.transition = ""
            slides.forEach(s => s.style.transition = "transform 0.5s ease, opacity 0.5s ease")
            updateActiveClasses()
            isAnimating = false
        })

        return true
    }

    function goToSlide(targetIndex) {
        if (isAnimating) return
        isAnimating = true

        updateActiveClasses(targetIndex, false)

        if (webglSlider) {
            const realIndex = ((targetIndex - 1) % visibleSlides + visibleSlides) % visibleSlides
            webglSlider.transitionTo(realIndex, () => {
                currentSlide = targetIndex
                if (!handleInfiniteLoop()) {
                    updateActiveClasses()
                    isAnimating = false
                }
            })
            return
        }

        setTimeout(() => {
            const from = (100 / totalSlides) * currentSlide
            const to = (100 / totalSlides) * targetIndex
            const distance = to - from
            const duration = 400
            const startTime = performance.now()

            function animate(time) {
                const elapsed = time - startTime
                const progress = Math.min(elapsed / duration, 1)
                const current = from + distance * progress
                gallery.style.transform = `translateX(-${current}%)`

                if (progress < 1) {
                    animationFrame = requestAnimationFrame(animate)
                } else {
                    cancelAnimationFrame(animationFrame)
                    currentSlide = targetIndex

                    if (!handleInfiniteLoop()) {
                        updateActiveClasses()
                        isAnimating = false
                    }
                }
            }

            cancelAnimationFrame(animationFrame)
            animationFrame = requestAnimationFrame(animate)
        }, 500)
    }

    bulletsWrapper.addEventListener("click", e => {
        if (e.target.classList.contains("bullet")) {
            const index = parseInt(e.target.dataset.index, 10)
            goToSlide(index + 1)
            resetAutoSlide()
        }
    })

    let startX = 0
    let endX = 0
    const threshold = 50

    gallery.addEventListener("touchstart", e => startX = e.touches[0].clientX, { passive: true })
    gallery.addEventListener("touchmove", e => endX = e.touches[0].clientX, { passive: true })
    gallery.addEventListener("touchend", () => {
        const deltaX = endX - startX
        if (Math.abs(deltaX) > threshold) {
            goToSlide(deltaX < 0 ? currentSlide + 1 : currentSlide - 1)
        }
        startX = 0
        endX = 0
    })

    const prevBtn = wrapper.querySelector(".gallery-prev")
    const nextBtn = wrapper.querySelector(".gallery-next")

    if (prevBtn) {
        prevBtn.addEventListener("click", () => {
            goToSlide(currentSlide - 1)
            resetAutoSlide()
        })
    }

    if (nextBtn) {
        nextBtn.addEventListener("click", () => {
            goToSlide(currentSlide + 1)
            resetAutoSlide()
        })
    }

    updateActiveClasses()

    // let autoSlide = setInterval(() => goToSlide(currentSlide + 1), 14000)

    function resetAutoSlide() {
        // clearInterval(autoSlide)
        // autoSlide = setInterval(() => goToSlide(currentSlide + 1), 10000)
    }

    // wrapper.addEventListener("mouseenter", () => clearInterval(autoSlide))
    // wrapper.addEventListener("mouseleave", resetAutoSlide)
}

function initAllGalleries() {
    document.querySelectorAll(".gallery-wrapper").forEach(initGallery)
}

const observer = new MutationObserver(() => initAllGalleries())
observer.observe(document.body, { childList: true, subtree: true })

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initAllGalleries)
} else {
    initAllGalleries()
}
// Delegated listener for the info toggle button
document.addEventListener('click', (e) => {
    const btn = e.target.closest('.toggle-post-content');
    if (!btn) return;

    // Support for both standard loop (.post_body) and justified grid (.justified-post)
    const postContainer = btn.closest('.post_body') || btn.closest('.justified-post');
    if (!postContainer) return;

    // Support for both class naming conventions (.post--content and .post__content)
    const content = postContainer.querySelector('.post--content, .post__content');
    if (!content) return;

    const isShowing = content.classList.toggle('show');

    // Toggle icons
    const infoIcon = btn.querySelector('.icon-info');
    const closeIcon = btn.querySelector('.icon-close');

    if (infoIcon && closeIcon) {
        if (isShowing) {
            infoIcon.style.display = 'none';
            closeIcon.style.display = 'inline-block';
        } else {
            infoIcon.style.display = 'inline-block';
            closeIcon.style.display = 'none';
        }
    }
});
