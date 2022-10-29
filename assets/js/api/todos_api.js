import Request from '../common/request';
import { ToDosHelper } from '../helpers';

export class ToDos {
    static PRE_FETCH_EVENT = 'todos.pre_fetch';
    static POST_FETCH_EVENT = 'todos.post_fetch';

    static PRE_DELETE_EVENT = 'todos.pre_delete';
    static POST_DELETE_EVENT = 'todos.post_delete';

    static PRE_COMPLETE_EVENT = 'todos.pre_complete';
    static POST_COMPLETE_EVENT = 'todos.post_complete';
   
    static fetchAll() {
        document.dispatchEvent(new CustomEvent(ToDos.PRE_FETCH_EVENT));

        const response = Request.exec('api_todos');
        response.then((todos) => {
            ToDosHelper.removeAll();

            for (const todo of todos) {
                ToDosHelper.LIST_GROUP.appendChild(ToDosHelper.build(todo));
            }

            document.dispatchEvent(new CustomEvent(ToDos.POST_FETCH_EVENT));
        });
    }

    static delete(id) {
        document.dispatchEvent(new CustomEvent(ToDos.PRE_DELETE_EVENT));

        const response = Request.exec('api_todos_delete', { id: id });
        response.then((data) => {
            if (data.success && data.status === 204) {
                const postEvent = new CustomEvent(ToDos.POST_DELETE_EVENT, { detail: { id: id } });
                document.dispatchEvent(postEvent);
            }
        });
    }

    static complete(id) {
        document.dispatchEvent(new CustomEvent(ToDos.PRE_COMPLETE_EVENT));

        const response = Request.exec('api_todos_complete', { id: id });
        response.then((data) => {
            if (data.success && data.status === 204) {
                const completeEvent = new CustomEvent(ToDos.POST_COMPLETE_EVENT, { detail: { id: id } });
                document.dispatchEvent(completeEvent);
            }
        });
    }
}
