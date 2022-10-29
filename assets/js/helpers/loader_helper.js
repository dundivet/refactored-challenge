export class LoaderHelper {
    static SELECTOR = 'div.loader';
    static LOADER = document.querySelector(LoaderHelper.SELECTOR);

    static show() {
        LoaderHelper.LOADER.classList.remove('d-none');
        LoaderHelper.LOADER.classList.add('d-block');
    }

    static hide() {
        LoaderHelper.LOADER.classList.remove('d-block');
        LoaderHelper.LOADER.classList.add('d-none');
    }
}