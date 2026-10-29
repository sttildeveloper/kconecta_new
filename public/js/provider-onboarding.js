(() => {
    const form = document.querySelector('[data-provider-demo-form]');
    const status = document.querySelector('[data-provider-demo-status]');
    if (!form || !status) return;

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        status.textContent = 'Gracias. Este registro es una demostración y no se ha enviado ningún dato.';
        status.classList.add('provider-form-note--success');
    });
})();
