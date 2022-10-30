import { Router } from './router';

export class Request {

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
            .then(r => {
                if (r.status === 204) {
                    return {success: true, status: r.status};
                }

                return r.json();
            });
    }
}
