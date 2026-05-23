document.addEventListener('click', function (e) {
    const button = e.target.closest('.button__like');
    if (!button) return;

    e.preventDefault();
    e.stopPropagation();

    const article = button.closest('article');
    if (!article) return;

    const post_id = article.getAttribute('data-id');
    if (!post_id) return;

    // Simple debounce to prevent multiple clicks
    if (button.classList.contains('is-loading')) return;
    button.classList.add('is-loading');

    const data = new FormData();
    data.append('action', 'avante_post_like');
    data.append('post_id', post_id);

    fetch(avante_likes_obj.ajax_url, {
        method: 'POST',
        body: data
    })
        .then(response => response.json())
        .then(response => {
            button.classList.remove('is-loading');
            if (response.success) {
                const countSpan = button.querySelector('.like-count');
                const currentSvg = button.querySelector('svg');

                if (countSpan) {
                    countSpan.textContent = response.data.likes > 0 ? response.data.likes : '';
                }

                if (currentSvg && response.data.icon) {
                    const temp = document.createElement('div');
                    temp.innerHTML = response.data.icon.trim();
                    const newSvg = temp.firstElementChild;
                    if (newSvg) {
                        button.replaceChild(newSvg, currentSvg);
                    }
                }

                if (response.data.action === 'liked' || response.data.likes > 0) {
                    button.classList.add('liked');
                } else {
                    button.classList.remove('liked');
                }

                // Dynamic accessibility updates
                const isLikedNow = response.data.action === 'liked';
                button.setAttribute('aria-pressed', isLikedNow ? 'true' : 'false');
                button.setAttribute('aria-label', isLikedNow 
                    ? 'Quitar me gusta a esta publicación' 
                    : 'Dar me gusta a esta publicación'
                );
            }
        })
        .catch(error => {
            button.classList.remove('is-loading');
            console.error('Error:', error);
        });
});
