document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('registerForm');
    if (!form) return; // если формы нет — это не страница регистрации

    const username = form.querySelector('[name="username"]');
    const email    = form.querySelector('[name="email"]');
    const password = form.querySelector('[name="password"]');
    const confirm  = form.querySelector('[name="confirm"]');

    form.addEventListener('submit', function (e) {
        let valid = true;

        // очистка прошлых сообщений
        form.querySelectorAll('.error-text').forEach(el => el.remove());

        if (username.value.trim().length < 3) {
            showError(username, 'Логин минимум 3 символа');
            valid = false;
        }

        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!re.test(email.value.trim())) {
            showError(email, 'Введите корректный email');
            valid = false;
        }

        if (password.value.length < 6) {
            showError(password, 'Пароль минимум 6 символов');
            valid = false;
        }

        if (confirm && password.value !== confirm.value) {
            showError(confirm, 'Пароли не совпадают');
            valid = false;
        }

        if (!valid) e.preventDefault();
    });

    function showError(input, message) {
        const small = document.createElement('small');
        small.className = 'error-text';
        small.textContent = message;
        input.insertAdjacentElement('afterend', small);
    }
});