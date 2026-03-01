document.addEventListener('DOMContentLoaded', function () {
    const likeButtons = document.querySelectorAll('.button__like');

    likeButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const article = this.closest('article');
            if (!article) return;

            const post_id = article.getAttribute('data-id');
            if (!post_id) return;

            // Simple debounce to prevent multiple clicks
            if (this.classList.contains('is-loading')) return;
            this.classList.add('is-loading');

            const data = new FormData();
            data.append('action', 'avante_post_like');
            data.append('post_id', post_id);

            fetch(avante_likes_obj.ajax_url, {
                method: 'POST',
                body: data
            })
                .then(response => response.json())
                .then(response => {
                    this.classList.remove('is-loading');
                    if (response.success) {
                        const countSpan = this.querySelector('.like-count');
                        const currentSvg = this.querySelector('svg');

                        if (countSpan) {
                            countSpan.textContent = response.data.likes > 0 ? response.data.likes : '';
                        }

                        if (currentSvg && response.data.icon) {
                            const temp = document.createElement('div');
                            temp.innerHTML = response.data.icon.trim();
                            const newSvg = temp.firstElementChild;
                            if (newSvg) {
                                this.replaceChild(newSvg, currentSvg);
                            }
                        }

                        if (response.data.action === 'liked' || response.data.likes > 0) {
                            this.classList.add('liked');
                        } else {
                            this.classList.remove('liked');
                        }
                    }
                })
                .catch(error => {
                    this.classList.remove('is-loading');
                    console.error('Error:', error);
                });
        });
    });
});
