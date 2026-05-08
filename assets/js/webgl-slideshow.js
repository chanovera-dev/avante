document.addEventListener("DOMContentLoaded", function () {
    const wrappers = document.querySelectorAll(".slideshow--wrapper");

    wrappers.forEach((wrapper) => {
        const slideshow = wrapper.querySelector(".slideshow");
        const originalSlides = Array.from(wrapper.querySelectorAll(".slideshow > *"));
        const images = Array.from(wrapper.querySelectorAll(".slideshow img")).map(img => img.src || img.dataset.src);
        const bulletsWrapper = wrapper.querySelector(".slideshow-bullets") || wrapper.parentElement.querySelector(".slideshow-bullets");

        if (!slideshow || originalSlides.length === 0 || images.length === 0) return;

        // --- WebGL Setup ---
        const container = document.createElement("div");
        container.classList.add("webgl-canvas-container");
        container.style.position = "absolute";
        container.style.top = "0";
        container.style.left = "0";
        container.style.width = "100%";
        container.style.height = "100%";
        container.style.zIndex = "1";
        wrapper.style.position = "relative";
        wrapper.prepend(container);

        slideshow.style.opacity = "0";
        slideshow.style.pointerEvents = "none";

        const scene = new THREE.Scene();
        const camera = new THREE.OrthographicCamera(-1, 1, 1, -1, 0, 1);
        const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
        renderer.setPixelRatio(window.devicePixelRatio);
        renderer.setSize(wrapper.offsetWidth, wrapper.offsetHeight);
        container.appendChild(renderer.domElement);

        const loader = new THREE.TextureLoader();
        const textures = images.map(src => {
            const tex = loader.load(src, (t) => {
                if (images.indexOf(src) === 0) {
                    wrapper.style.aspectRatio = `${t.image.width} / ${t.image.height}`;
                    updateSize();
                }
            });
            tex.minFilter = THREE.LinearFilter;
            tex.generateMipmaps = false;
            return tex;
        });

        const vertexShader = `
            varying vec2 vUv;
            void main() {
                vUv = uv;
                gl_Position = vec4(position, 1.0);
            }
        `;

        const fragmentShader = `
            varying vec2 vUv;
            uniform sampler2D texture1;
            uniform sampler2D texture2;
            uniform float progress;
            uniform float intensity;
            uniform vec2 direction;
            uniform float seed;

            void main() {
                vec2 uv = vUv;
                
                // Random-ish displacement based on seed and direction
                float noise = sin(uv.x * 10.0 + seed) * cos(uv.y * 10.0 + seed) * 0.5;
                float displacement = (texture2D(texture1, uv).r + noise) * 0.5;
                
                vec2 distortedUv1 = uv + direction * progress * intensity * displacement;
                vec2 distortedUv2 = uv - direction * (1.0 - progress) * intensity * displacement;
                
                vec4 _texture1 = texture2D(texture1, distortedUv1);
                vec4 _texture2 = texture2D(texture2, distortedUv2);
                
                gl_FragColor = mix(_texture1, _texture2, progress);
            }
        `;

        const material = new THREE.ShaderMaterial({
            uniforms: {
                texture1: { value: textures[0] },
                texture2: { value: textures[1] },
                progress: { value: 0 },
                intensity: { value: 0.2 },
                direction: { value: new THREE.Vector2(1.0, 0.0) },
                seed: { value: Math.random() * 10.0 }
            },
            vertexShader,
            fragmentShader
        });

        const mesh = new THREE.Mesh(new THREE.PlaneGeometry(2, 2), material);
        scene.add(mesh);

        let currentSlide = 0;
        let isAnimating = false;

        function updateBullets(index) {
            if (!bulletsWrapper) return;
            const bullets = bulletsWrapper.querySelectorAll(".bullet");
            bullets.forEach((bullet, i) => {
                bullet.classList.toggle("active", i === index);
            });
        }

        function goToSlide(nextIndex) {
            if (isAnimating || nextIndex === currentSlide) return;
            isAnimating = true;

            // Randomize transition direction and seed
            const angle = Math.random() * Math.PI * 2;
            material.uniforms.direction.value.set(Math.cos(angle), Math.sin(angle));
            material.uniforms.seed.value = Math.random() * 100.0;
            material.uniforms.intensity.value = 0.1 + Math.random() * 0.3;

            material.uniforms.texture1.value = textures[currentSlide];
            material.uniforms.texture2.value = textures[nextIndex];
            material.uniforms.progress.value = 0;

            const duration = 1200;
            const startTime = performance.now();

            function animate(time) {
                const elapsed = time - startTime;
                const p = Math.min(elapsed / duration, 1);
                const easedP = p < 0.5 ? 4 * p * p * p : 1 - Math.pow(-2 * p + 2, 3) / 2;
                
                material.uniforms.progress.value = easedP;

                if (p < 1) {
                    requestAnimationFrame(animate);
                } else {
                    currentSlide = nextIndex;
                    updateBullets(currentSlide);
                    isAnimating = false;
                }
            }
            requestAnimationFrame(animate);
        }

        let autoplayInterval;

        function startAutoplay() {
            autoplayInterval = setInterval(() => {
                goToSlide((currentSlide + 1) % textures.length);
            }, 6000);
        }

        function resetAutoplay() {
            clearInterval(autoplayInterval);
            startAutoplay();
        }

        startAutoplay();

        const prevBtn = wrapper.querySelector(".slideshow-prev") || wrapper.parentElement.querySelector(".slideshow-prev");
        const nextBtn = wrapper.querySelector(".slideshow-next") || wrapper.parentElement.querySelector(".slideshow-next");

        if (prevBtn) {
            prevBtn.addEventListener("click", (e) => {
                e.preventDefault();
                resetAutoplay();
                goToSlide((currentSlide - 1 + textures.length) % textures.length);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener("click", (e) => {
                e.preventDefault();
                resetAutoplay();
                goToSlide((currentSlide + 1) % textures.length);
            });
        }

        if (bulletsWrapper) {
            bulletsWrapper.addEventListener("click", (e) => {
                if (e.target.classList.contains("bullet")) {
                    resetAutoplay();
                    goToSlide(parseInt(e.target.dataset.index));
                }
            });
        }

        function updateSize() {
            renderer.setSize(wrapper.offsetWidth, wrapper.offsetHeight);
        }

        window.addEventListener("resize", updateSize);

        function render() {
            renderer.render(scene, camera);
            requestAnimationFrame(render);
        }
        render();

        if (bulletsWrapper && bulletsWrapper.innerHTML === "") {
            textures.forEach((_, i) => {
                const bullet = document.createElement("div");
                bullet.classList.add("bullet");
                if (i === 0) bullet.classList.add("active");
                bullet.dataset.index = i;
                bulletsWrapper.appendChild(bullet);
            });
        }
    });
});