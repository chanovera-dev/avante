document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('ajax-posts-container');
    const loadMoreBtn = document.getElementById('load-more-btn');
    if (!container || !loadMoreBtn) return;

    const btnText = loadMoreBtn.querySelector('.btn-text');
    const btnLoader = loadMoreBtn.querySelector('.btn-loader');

    // Estado inicial
    let selectedCats = []; // Array Selección Múltiple
    let showNsfw = false;
    let isLoading = false;

    // Recuperar página inicial del botón (si existe desde data-page)
    let currentPage = parseInt(loadMoreBtn.dataset.page) || 1;

    // --- 1. Evento: Cambio de Categoría (Selección Múltiple) ---
    document.querySelectorAll('.cat-filter-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();

            const catId = parseInt(btn.dataset.catId);
            const isAll = (catId === 0);

            if (isAll) {
                // Si click en "Todos": Limpiar array y activar solo "Todos"
                selectedCats = [];
                document.querySelectorAll('.cat-filter-btn').forEach(b => b.classList.remove('active'));
                document.querySelector('.cat-filter-btn[data-cat-id="0"]').classList.add('active');
            } else {
                // Si click en categoría específica
                const allBtn = document.querySelector('.cat-filter-btn[data-cat-id="0"]');
                if (allBtn) allBtn.classList.remove('active');

                const index = selectedCats.indexOf(catId);
                if (index > -1) {
                    // Quitar
                    selectedCats.splice(index, 1);
                    btn.classList.remove('active');
                } else {
                    // Añadir
                    selectedCats.push(catId);
                    btn.classList.add('active');
                }

                if (selectedCats.length === 0) {
                    if (allBtn) allBtn.classList.add('active');
                }
            }

            currentPage = 1;
            loadPosts(false);
        });
    });

    // --- 2. Evento: Toggle NSFW ---
    const nsfwToggle = document.getElementById('nsfw-toggle-input');
    if (nsfwToggle) {
        nsfwToggle.addEventListener('change', () => {
            showNsfw = nsfwToggle.checked;
            currentPage = 1;
            loadPosts(false);
        });
    }

    // --- 3. Evento: Botón Cargar Más ---
    loadMoreBtn.addEventListener('click', (e) => {
        e.preventDefault();
        if (!isLoading) {
            currentPage++;
            loadPosts(true);
        }
    });

    /**
     * Función principal de carga AJAX usando Fetch API
     * @param {boolean} isAppend - true para paginación, false para filtros
     */
    async function loadPosts(isAppend) {
        if (isLoading) return;
        isLoading = true;

        if (isAppend) {
            // UI Carga Paginación
            loadMoreBtn.disabled = true;
            if (btnText) btnText.style.display = 'none';
            if (btnLoader) btnLoader.style.display = 'block';
        } else {
            // UI Carga Filtro Global
            container.style.opacity = '0.5';
            loadMoreBtn.style.display = 'none'; // Ocultar paginación mientras filtramos
        }

        const formData = new URLSearchParams();
        formData.append('action', 'avante_filter_posts');
        formData.append('nonce', avante_ajax.nonce);
        formData.append('nsfw', showNsfw);
        formData.append('paged', currentPage);
        // WordPress espera un array en formato categories[]
        selectedCats.forEach(cat => formData.append('categories[]', cat));

        try {
            const response = await fetch(avante_ajax.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: formData.toString()
            });

            const res = await response.json();

            if (res.success) {
                if (isAppend) {
                    container.insertAdjacentHTML('beforeend', res.data.html);
                } else {
                    container.innerHTML = res.data.html;
                }

                // Re-lanzar animaciones de entrada con el retardo de 0.3s acordado
                if (typeof animateIn === 'function') {
                    setTimeout(() => {
                        animateIn('.ajax-item-wrapper');
                    }, 300);
                }

                // Actualizar etiquetas de año
                setTimeout(updateYearLabels, 150);

                // Gestión del Botón Ver Más
                const maxPages = parseInt(res.data.max_pages);
                loadMoreBtn.dataset.page = currentPage;

                if (currentPage < maxPages) {
                    loadMoreBtn.style.display = 'flex'; // o block según tu CSS
                    loadMoreBtn.disabled = false;
                    if (btnText) btnText.style.display = 'inline';
                    if (btnLoader) btnLoader.style.display = 'none';
                } else {
                    loadMoreBtn.style.display = 'none';
                }

            } else {
                if (!isAppend) {
                    container.innerHTML = `<div class="no-results">${res.data.message}</div>`;
                }
                loadMoreBtn.style.display = 'none';
            }
        } catch (error) {
            console.error('Error fetching posts:', error);
            if (!isAppend) {
                container.innerHTML = '<div class="no-results">Error de conexión. Inténtalo de nuevo.</div>';
            }
        } finally {
            isLoading = false;
            container.style.opacity = '1';
            if (isAppend) {
                loadMoreBtn.disabled = false;
                if (btnText) btnText.style.display = 'inline';
                if (btnLoader) btnLoader.style.display = 'none';
            }
        }
    }

    /**
     * Genera etiquetas flotantes para los años (Vanilla JS)
     */
    function updateYearLabels() {
        // Eliminar etiquetas existentes
        container.querySelectorAll('.year-float-label').forEach(el => el.remove());

        let lastYear = null;
        let lastTop = -1;
        const items = container.querySelectorAll('.ajax-item-wrapper');

        items.each = Array.prototype.forEach; // Helper para compatibilidad si fuera necesario

        items.forEach(item => {
            const currentYear = item.dataset.year;
            // Get position relative to container
            const rect = item.getBoundingClientRect();
            const containerRect = container.getBoundingClientRect();
            const relativeTop = rect.top - containerRect.top + container.scrollTop;

            if (currentYear && currentYear !== lastYear) {
                let top = relativeTop;

                // Evitar que etiquetas de distintos años se pisen
                if (Math.abs(top - lastTop) < 20) {
                    top += 40;
                }

                const label = document.createElement('div');
                label.className = 'year-float-label';
                label.textContent = '— ' + currentYear;
                label.style.top = top + 'px';
                label.style.opacity = '1';

                container.appendChild(label);
                lastYear = currentYear;
                lastTop = top;
            }
        });
    }

    // Inicializar etiquetas al cargar
    setTimeout(updateYearLabels, 200);

    // Actualizar al redimensionar la ventana
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(updateYearLabels, 250);
    });
});