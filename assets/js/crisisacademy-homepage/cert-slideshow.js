document.addEventListener("DOMContentLoaded", function () {
    const wrappers = document.querySelectorAll(".cert-container")

    wrappers.forEach((wrapper) => {
        const slideshowWrapper = wrapper.querySelector(".slideshow--wrapper")
        const slideshow = wrapper.querySelector(".slideshow")
        const slides = Array.from(slideshow ? slideshow.children : [])
        const bulletsWrapper = wrapper.querySelector(".slideshow-bullets")

        if (!slideshow || slides.length === 0 || !bulletsWrapper) return

        const total = slides.length
        let current = 0
        let isAnimating = false

        /* ── Build bullets ────────────────────────────────────────────── */
        bulletsWrapper.innerHTML = ""
        slides.forEach((_, i) => {
            const b = document.createElement("div")
            b.classList.add("bullet")
            if (i === 0) b.classList.add("active")
            b.dataset.index = i
            bulletsWrapper.appendChild(b)
        })
        const bullets = bulletsWrapper.querySelectorAll(".bullet")

        /* ── Mark initial active slide ────────────────────────────────── */
        slides[current].classList.add("active")

        function updateBullets(idx) {
            bullets.forEach((b, i) => b.classList.toggle("active", i === idx))
        }

        /* ── Core flip logic ──────────────────────────────────────────── */
        function goToSlide(targetIdx) {
            if (isAnimating || targetIdx === current) return
            isAnimating = true

            // Determine direction before normalising targetIdx
            const direction = targetIdx > current ? 'next' : 'prev'

            // Normalise index with wrapping
            targetIdx = ((targetIdx % total) + total) % total

            const FLIP_DURATION = 600  // ms — matches CSS 0.6 s

            const phase1Angle = direction === 'next' ? 90 : -90
            const phase2Angle = direction === 'next' ? -90 : 90

            // Phase 1: rotate 0 → ±90° (card "falls away")
            wrapper.style.transition = `transform ${FLIP_DURATION / 2}ms cubic-bezier(0.4, 0, 1, 1)`
            wrapper.style.transform = `rotateY(${phase1Angle}deg)`

            setTimeout(() => {
                // At ±90° the card is edge-on and invisible — swap slides
                slides[current].classList.remove("active")
                current = targetIdx
                slides[current].classList.add("active")
                updateBullets(current)

                // Instantly jump to ∓90° on the other side (no transition)
                wrapper.style.transition = "none"
                wrapper.style.transform = `rotateY(${phase2Angle}deg)`

                // Force reflow so the browser registers the position change
                void wrapper.offsetWidth

                // Phase 2: rotate ∓90° → 0° (card "comes back")
                wrapper.style.transition = `transform ${FLIP_DURATION / 2}ms cubic-bezier(0, 0, 0.6, 1)`
                wrapper.style.transform = "rotateY(0deg)"

                setTimeout(() => {
                    wrapper.style.transition = ""
                    wrapper.style.transform = ""
                    isAnimating = false
                }, FLIP_DURATION / 2)

            }, FLIP_DURATION / 2)
        }

        /* ── Auto-slide ───────────────────────────────────────────────── */
        let autoSlide = setInterval(() => {
            const block = wrapper.closest('.block');
            if (block && (block.classList.contains('is-bottom') || block.getBoundingClientRect().bottom < 0)) {
                return;
            }
            goToSlide(current + 1);
        }, 14000)

        function resetAutoSlide() {
            clearInterval(autoSlide)
            autoSlide = setInterval(() => {
                const block = wrapper.closest('.block');
                if (block && (block.classList.contains('is-bottom') || block.getBoundingClientRect().bottom < 0)) {
                    return;
                }
                goToSlide(current + 1);
            }, 14000)
        }

        /* ── Navigation buttons ───────────────────────────────────────── */
        const prevBtn = wrapper.querySelector(".slideshow-prev")
        const nextBtn = wrapper.querySelector(".slideshow-next")

        if (prevBtn) prevBtn.addEventListener("click", () => { goToSlide(current - 1); resetAutoSlide() })
        if (nextBtn) nextBtn.addEventListener("click", () => { goToSlide(current + 1); resetAutoSlide() })

        /* ── Bullet clicks ────────────────────────────────────────────── */
        bulletsWrapper.addEventListener("click", (e) => {
            const b = e.target.closest(".bullet")
            if (!b) return
            goToSlide(parseInt(b.dataset.index))
            resetAutoSlide()
        })

        /* ── Touch / swipe ────────────────────────────────────────────── */
        let startX = 0
        slideshow.addEventListener("touchstart", (e) => { startX = e.touches[0].clientX }, { passive: true })
        slideshow.addEventListener("touchend", (e) => {
            const delta = e.changedTouches[0].clientX - startX
            if (Math.abs(delta) > 50) {
                goToSlide(delta < 0 ? current + 1 : current - 1)
                resetAutoSlide()
            }
        })
    })
})