(() => {
    const button = document.querySelector('[data-home-menu-button]');
    const navigation = document.querySelector('[data-home-navigation]');
    if (!button || !navigation) return;

    const setOpen = (open) => {
        navigation.classList.toggle('is-open', open);
        button.setAttribute('aria-expanded', String(open));
    };

    button.addEventListener('click', () => setOpen(!navigation.classList.contains('is-open')));
    navigation.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => setOpen(false)));
    window.matchMedia('(min-width: 1001px)').addEventListener('change', () => setOpen(false));
})();
