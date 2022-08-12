import '../css/app.scss';
import 'jquery';

document.addEventListener('DOMContentLoaded', () => {
        new App();
    }
);

class App {
    constructor() {
        this.handleCommentForm();
        this.openBurger();
        this.addClassToTitle();
        this.addLike();
        this.removeLike();
    }

    handleCommentForm() {
        const commentForm = $('form.comment-form');
        if (null === commentForm) {
            return;
        }

        commentForm.on('submit', async (e) => {
            e.preventDefault();

            const response = await fetch('/ajax/comments', {
                method: 'POST',
                body: new FormData(e.target),
            })

            if (!response.ok) {
                return;
            }

            const json = await response.json();

            if (json.code === 'COMMENT_ADDED_SUCCESSFULLY') {
                const commentList = document.querySelector('#comment-list');
                const commentCount = document.querySelector('#comment-count');
                const commentContent = document.querySelector('#comment_content');
                commentList.insertAdjacentHTML('afterbegin', json.html); //todo its not working
                commentCount.innerText = json.numberOfComments + " ";
                commentContent.value = '';
            }
        });
    }

    openBurger() {
        const burgerIcon = $('#burger');
        const navbarMenu = $('#nav-links');
        const burgerIcon2 = $('#burger-2');
        const navbarMenu2 = $('#nav-links-2');

        burgerIcon.on('click', () => {
                navbarMenu.toggleClass('is-active');
            }
        );

        $(document).on('click', (e) => {
                if (!$(e.target).closest('#burger').length) {
                    navbarMenu.removeClass('is-active');
                }
            }
        );

        burgerIcon2.on('click', () => {
                navbarMenu2.toggleClass('is-active');
            }
        );

        $(document).on('click', (e) => {
                if (!$(e.target).closest('#burger-2').length) {
                    navbarMenu2.removeClass('is-active');
                }
            }
        );
    }

    addClassToTitle() {
        const title = $('#blog_title')
        title.addClass('tracking-in-expand-forward-bottom');
    }

    addLike() {
        const likeButton = $('.like-button');
        const article = $('article');
        const articleId = article.attr('id');
        const likeButtonCount = $('.like-button-count');
        likeButton.on('click', function (e) {
                $.ajax({
                    url: '/ajax/like/' + articleId,
                    method: 'POST',
                    success: function (data) {
                        likeButton.toggleClass('liked');
                        if (data.message === 'LIKE') {
                            likeButtonCount.text(data.count);
                        } else {
                            likeButtonCount.text(data.count);
                        }
                    },
                    error: function (data) {
                        let code = data.responseJSON.code;
                        if (code === 'USER_NOT_AUTHENTICATED_FULLY') {
                            self.triggerModal('Veuillez vous connecter', 'Vous devez être connecté pour liker un article', true)
                        }
                    }
                });
            }
        );
    }

    removeLike(){
        let remove_like = $('.remove_like');
        let article_id = remove_like.attr('data-article-id');
        let row = $('#row_' + article_id);
        let like_count = $('#like_count')
            remove_like.on('click', function (e) {
                $.ajax({
                    url: '/ajax/like/' + article_id,
                    method: 'POST',
                    success: function (data) {
                        row.remove();
                        like_count.text(like_count.text() - 1);
                    },
                });
            }
        );
    }

    triggerModal(title, message, linkToLogin = false) {
        let modal = $('#modal');
        let modalTitle = $('#modal-title');
        let modalMessage = $('#modal-message');
        let modalClose = $('#modal-close');
        let modalButton = $('#modal-button');
        modalTitle.text(title);
        modalMessage.text(message);
        if (linkToLogin) {
            modalButton.show();
        } else {
            modalButton.hide();
        }
        modal.toggleClass('is-active');
        modalClose.on('click', () => {
                modal.removeClass('is-active');
            }
        );
    }
}