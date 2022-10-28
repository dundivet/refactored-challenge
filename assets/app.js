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

class Request {
    static GET = 'get';
    static POST = 'post';
    static PUT = 'put';
    static PATCH = 'put';
    static DELETE = 'delete';

    static exec(route, vars = {}, data = {}) {
        const options = {
            method: Router.get(route).method,
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json'
            }
        };

        if (Object.entries(data).length > 0 && Router.get(route).method !== Request.GET) {
            options.body = JSON.stringify(data);
        }

        return fetch(Router.generate(route, vars), options)
            .then(r => r.json());
    }
}

class Router {
    static routes = {
        'api_todos': {
            path: '/api/todos',
            method: Request.GET
        },
        'api_todos_create': {
            path: '/api/todos',
            method: Request.POST
        },
        'api_todos_update': {
            path: '/api/todos/{id}',
            method: Request.PUT
        },
        'api_todos_complete': {
            path: '/api/todos/{id}',
            method: Request.PATCH
        },
        'api_todos_delet': {
            path: '/api/users/{id}',
            method: Request.DELETE
        }
    };

    static get(name) {
        if (typeof Router.routes[name] === 'undefined') {
            throw new Error(`Route "${name}" is not defined`);
        }

        return Router.routes[name];
    }

    static generate(name, vars = {}) {
        const route = Router.get(name);

        let path = route.path;
        for (const [key, value] of Object.entries(vars)) {
            path = path.replace(`{${key}}`, value);
        }

        return path;
    }
}

class ToDosHelper {
    static TEMPLATE = `<div class="list-group-item d-flex align-items-center gap-3 py-3">
	<input class="form-check-input flex-shrink-0" type="checkbox" value="" style="font-size: 1.375em;" data-com.bitwarden.browser.user-edited="yes">
	<div class="d-flex gap-2 w-100 justify-content-between align-items-center">
		<div>
			<h6 class="mb-0"><a>:title:</a></h6>
			<p class="mb-0 opacity-75">:description:</p>
            <small class="opacity-50 text-nowrap">:due:</small>
		</div>
        <a href="#delete" data-entity=":id:"><i class="fas fa-trash text-danger fa-xl"></i></a>
	</div>
</div>`;
    static SELECTOR = 'div.list-group';
    static LIST_GROUP = document.querySelector(ToDosHelper.SELECTOR);

    static show() {
        ToDosHelper.LIST_GROUP.classList.remove('d-none');
        ToDosHelper.LIST_GROUP.classList.add('d-block');
    }

    static hide() {
        ToDosHelper.LIST_GROUP.classList.add('d-none');
        ToDosHelper.LIST_GROUP.classList.remove('d-block');
    }

    static build(todo) {
        let todoHtml = ToDosHelper.TEMPLATE;

        todoHtml = todoHtml.replace(':title:', todo.title);
        todoHtml = todoHtml.replace(':description:', todo.description);
        todoHtml = todoHtml.replace(':due:', todo.due);
        todoHtml = todoHtml.replace(':id:', todo.id);

        const template = document.createElement('template');
        template.innerHTML = todoHtml;

        return template.content.firstChild;
    }

    static setEvents() {
        const collection = document
            .querySelectorAll('.list-group-item a[href="#delete"]');
        for (const el of collection) {
            el.addEventListener('click', (e) => {
                e.preventDefault();

            });
        }
    }

    static removeAll() {
        ToDosHelper.LIST_GROUP.innerHTML = '';
    }
}

class LoaderHelper {
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

class App {

    constructor() {
        this.fetchTodos();
    }

    fetchTodos() {
        ToDosHelper.hide();
        LoaderHelper.show();

        const response = Request.exec('api_todos');
        response.then((todos) => {
            ToDosHelper.removeAll();

            for (const todo of todos) {
                ToDosHelper.LIST_GROUP.appendChild(ToDosHelper.build(todo));
            }

            ToDosHelper.show();
            ToDosHelper.setEvents();
            LoaderHelper.hide();
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const app = new App();
});
