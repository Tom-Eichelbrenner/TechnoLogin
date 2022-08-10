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
}