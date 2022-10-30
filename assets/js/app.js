/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../styles/app.scss';

import '@popperjs/core';
import 'bootstrap';

import { ToDosHelper, LoaderHelper } from './helpers';
import { ToDos as ToDosAPI } from './api';

export default class App {
    #loading = false;

    constructor() {
        this.toggleLoading = this.toggleLoading.bind(this);
        document.addEventListener(ToDosAPI.PRE_FETCH_EVENT, this.toggleLoading, false);
        document.addEventListener(ToDosAPI.POST_FETCH_EVENT, this.toggleLoading, false);

        document.addEventListener(ToDosAPI.POST_DELETE_EVENT, this.#onDelete, false);
        document.addEventListener(ToDosAPI.POST_COMPLETE_EVENT, this.#onComplete, false);

        // ToDosAPI.fetchAll();
        // const add_btn = document.getElementById('#add_btn');
        // add_btn.addEventListener('click', ToDosAPI.create, false);
    }

    toggleLoading() {
        if (!this.#loading) {
            ToDosHelper.hide();
            LoaderHelper.show();

            this.#loading = true;
        } else {
            ToDosHelper.show();
            ToDosHelper.setEvents();

            LoaderHelper.hide();
            this.#loading = false;
        }
    }

    fetchAll(query = null) {
        ToDosAPI.fetchAll(query);
    }

    #onDelete (event) {
        ToDosHelper.remove(event.detail.id);
    }
    #onComplete (event) {
        ToDosHelper.complete(event.detail.id);
    }
}

// document.addEventListener('DOMContentLoaded', () => {
//     const app = new App();
//     app.fetchAll();
// });
