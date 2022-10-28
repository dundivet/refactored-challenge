import Request from './request';
import { ToDosHelper } from './helpers';

export class ToDos {
    static PRE_FETCH_EVENT = 'todos.pre_fetch';
    static COMPLETE_FETCH_EVENT = 'todos.complete_fetch';

    static PRE_DELETE_EVENT = 'todos.pre_delete';
    static COMPLETE_DELETE_EVENT = 'todos.complete_delete';
   
    static fetchAll() {
        document.dispatchEvent(new CustomEvent(ToDos.PRE_FETCH_EVENT));

        const response = Request.exec('api_todos');
        response.then((todos) => {
            ToDosHelper.removeAll();

            for (const todo of todos) {
                ToDosHelper.LIST_GROUP.appendChild(ToDosHelper.build(todo));
            }

            document.dispatchEvent(new CustomEvent(ToDos.COMPLETE_FETCH_EVENT));
        });
    }

    static delete(id) {
        window.dispatchEvent(new CustomEvent(ToDos.PRE_DELETE_EVENT));

        const response = Request.exec('api_todos_delete', { id: id });
        response.then((data) => {
            if (data.success && data.status === 204) {
                const completeEvent = new CustomEvent(ToDos.COMPLETE_DELETE_EVENT, { detail: { id: id } });
                window.dispatchEvent(completeEvent);
            }
        });
    }
}
