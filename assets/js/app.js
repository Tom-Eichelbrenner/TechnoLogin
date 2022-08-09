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
                console.log(json.message);
                const commentList = document.querySelector('#comment-list');
                const commentCount = document.querySelector('#comment-count');
                const commentContent = document.querySelector('#comment-content');
                // commentList.insertAdjacentHTML('beforeend', json.html);
                commentList.insertAdjacentHTML('afterbegin', json.html); //todo its not working
                // commentList.lastElementChild.scrollIntoView({behavior: 'smooth'});
                commentCount.innerText = json.numberOfComments;
                commentContent.value = '';
            }
        });
    }

    openBurger() {
        const burgerIcon = $('#burger');
        const navbarMenu = $('#nav-links');

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
    }
}