document.addEventListener('DOMContentLoaded', () => {
    const loginFormWrapper = document.getElementById('login-form');
    const registerFormWrapper = document.getElementById('register-form');
    const showRegisterLink = document.getElementById('show-register');
    const showLoginLink = document.getElementById('show-login');

    showRegisterLink.addEventListener('click', (e) => {
        e.preventDefault();
        loginFormWrapper.classList.add('hidden');
        registerFormWrapper.classList.remove('hidden');
    });

    showLoginLink.addEventListener('click', (e) => {
        e.preventDefault();
        registerFormWrapper.classList.add('hidden');
        loginFormWrapper.classList.remove('hidden');
    });

    const handleFormSubmit = async (form, event) => {
        event.preventDefault();
        const feedbackEl = form.querySelector('.feedback');
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await response.json();

            feedbackEl.textContent = result.message;
            feedbackEl.className = `feedback ${result.success ? 'success' : 'error'}`;
            feedbackEl.style.display = 'block';

            if (result.success) {
                // AJUSTE AQUI: Verifica a ação do formulário em vez do ID
                if (form.action.includes('autenticar.php')) {
                    // Redireciona para a página principal após o login
                    window.location.href = 'index.php';
                } else {
                    // Limpa o formulário de registro e volta para o login
                    form.reset();
                    setTimeout(() => {
                        showLoginLink.click();
                        feedbackEl.style.display = 'none';
                    }, 2000);
                }
            }
        } catch (error) {
            console.error('Erro:', error);
            feedbackEl.textContent = 'Ocorreu um erro de conexão.';
            feedbackEl.className = 'feedback error';
            feedbackEl.style.display = 'block';
        }
    };

    loginFormWrapper.querySelector('form').addEventListener('submit', (e) => handleFormSubmit(e.currentTarget, e));
    registerFormWrapper.querySelector('form').addEventListener('submit', (e) => handleFormSubmit(e.currentTarget, e));
});
