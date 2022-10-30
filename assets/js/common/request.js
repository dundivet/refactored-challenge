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
        let url = Router.generate(route, vars);

        if (Object.entries(data).length > 0) {
            switch (Router.get(route).method) {
                case Router.GET:
                    url += '?' + new URLSearchParams(data).toString();
                    break;
                default:
                    options.body = JSON.stringify(data);
                    break;
            }
        }

        return fetch(url, options)
            .then(r => {
                if (r.status === 204) {
                    return {success: true, status: r.status};
                }

                return r.json();
            });
    }
}
