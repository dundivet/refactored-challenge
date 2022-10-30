
export class Router {
    static GET = 'get';
    static POST = 'post';
    static PUT = 'put';
    static PATCH = 'patch';
    static DELETE = 'delete';

    static routes = {
        // api
        'api_todos': {
            path: '/api/todos',
            method: Router.GET
        },
        'api_todos_create': {
            path: '/api/todos',
            method: Router.POST
        },
        'api_todos_update': {
            path: '/api/todos/{id}',
            method: Router.PUT
        },
        'api_todos_complete': {
            path: '/api/todos/{id}',
            method: Router.PATCH
        },
        'api_todos_delete': {
            path: '/api/todos/{id}',
            method: Router.DELETE
        },
        // public interface
        'todos_add': {
            path: '/todos/add',
            method: Router.GET
        },
        'todos_show': {
            path: '/todos/{id}',
            method: Router.GET
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
