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
        this.handleProfileForm();
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


    handleCommentForm() {
        const commentForm = $('formcomment-form');

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
            } else {
                this.triggerModal('Erreur', json.message);
            }
        });
    }

    handleProfileForm() {
        const profileForm = $('form#form-profile');
        const modal = $('#modal-profile');
        const notification = $('#notification');
        const spinner = $('#spinner');
        const message = $('#message');
        const modal_cancel = $('#modal-cancel');
        const modify = $('#modify');
        const username = $('#username');
        const modal_close = $('#modal-close');
        const about = $('#about');

        $('#modal-background').on('click', () => {
                modal.removeClass('is-active');
                notification.hide();
            }
        );
        modify.on('click', () => {
                modal.toggleClass('is-active');
            }
        );
        modal_cancel.on('click', () => {
            modal.removeClass('is-active');
            notification.hide();
        });
        modal_close.on('click', () => {
                modal.removeClass('is-active');
                notification.hide();
            }
        );

        spinner.hide();
        notification.hide();
        if (null === profileForm) {
            return;
        }

        profileForm.on('submit', async (e) => {
            e.preventDefault();
            spinner.show();
            const response = await fetch('/ajax/profile', {
                method: 'POST',
                body: new FormData(e.target),
            })

            const json = await response.json();
            if (json.code === 'USER_NOT_AUTHENTICATED_FULLY') {
                spinner.hide();
                notification.show();
                notification.addClass('is-danger');
                notification.removeClass('is-success');
                message.text(json.message);
            }
            if (json.code === 'USERNAME_ALREADY_EXISTS') {
                spinner.hide();
                notification.show();
                notification.addClass('is-danger');
                notification.removeClass('is-success');
                message.text(json.message);
            }
            if (json.code === 'PROFILE_UPDATED_SUCCESSFULLY') {
                spinner.hide();
                notification.show();
                notification.addClass('is-success');
                notification.removeClass('is-danger');
                message.text(json.message);
                username.text('@' + json.username);
                about.text(json.about);
            }
            if (json.code === 'PROFILE_UPDATE_FAILED') {
                spinner.hide();
                notification.show();
                notification.addClass('is-danger');
                notification.removeClass('is-success');
                message.text(json.message);
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
                            this.triggerModal('Erreur', 'Vous devez être connecté pour aimer un article', true);
                        }
                    },
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
                });
            }
        );
    }

    removeLike() {
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


}