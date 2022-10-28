import { ToDos } from "./api";

export class ToDosHelper {
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
                ToDos.delete(el.dataset.entity);
            });
        }
    }

    static removeAll() {
        ToDosHelper.LIST_GROUP.innerHTML = '';
    }

    static remove(id) {
        const el = document.querySelector(`div.list-group-item a[data-entity="${id}"]`);
        el.parentNode.parentNode.remove();
    }
}

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
