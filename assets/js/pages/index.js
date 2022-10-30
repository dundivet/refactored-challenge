import App from '../app';

document.addEventListener('DOMContentLoaded', () => {
    const app = new App();

    app.fetchAll();

    const searchForm = document.querySelector('form[role="search"]');
    searchForm.addEventListener('submit', (e) => {
        e.preventDefault();
    });

    const inputSearch = document.querySelector('input[type="search"]');
    inputSearch.addEventListener('search', (e) => {
        app.fetchAll(e.target.value);
    });
});
