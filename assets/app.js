/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

import '@popperjs/core';
import 'bootstrap';

import { ToDosHelper, LoaderHelper } from './helpers';
import { ToDos as ToDosAPI } from './api';

class App {

    constructor() {
        document.addEventListener(ToDosAPI.PRE_FETCH_EVENT, () => {
            ToDosHelper.hide();
            LoaderHelper.show();
        });
        document.addEventListener(ToDosAPI.COMPLETE_FETCH_EVENT, () => {
            ToDosHelper.show();
            ToDosHelper.setEvents();

            LoaderHelper.hide();
        });

        window.addEventListener(ToDosAPI.COMPLETE_DELETE_EVENT, this.onDelete, false);

        ToDosAPI.fetchAll();
    }

    onDelete (event) {
        ToDosHelper.remove(event.detail.id);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const app = new App();
});
