import * as bootstrap from 'bootstrap';

import { ToDos } from "../api";
import { DeleteModal } from "../common";

export class ToDosHelper {
    static TEMPLATE = `<div class="list-group-item d-flex align-items-center gap-3 py-3">
	<input class="form-check-input flex-shrink-0" type="checkbox" value="" data-entity=":id:" style="font-size: 1.375em;">
	<div class="d-flex gap-2 w-100 justify-content-between align-items-center">
		<div>
			<h6 class="mb-0"><a>:title:</a></h6>
			<div class="tags"></div>
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
        todoHtml = todoHtml.replace(/:id:/g, todo.id);

        const template = document.createElement('template');
        template.innerHTML = todoHtml;

        const divTags = template.content.firstChild.querySelector('div.tags');
        for(const t of todo.tags) {
            const badge = document.createElement('div');
            badge.classList.add('badge', 'bg-primary'); 
            badge.innerText = t.name;
            divTags.appendChild(badge);
        }

        if (todo.completed) {
            ToDosHelper.setCompleted(template);
        }

        return template.content.firstChild;
    }

    static setEvents() {
        const collection = document.querySelectorAll('.list-group-item');
        collection.forEach((el) => {
            ToDosHelper.deleteEvent(el);
            ToDosHelper.completeEvent(el);
        });
    }

    static deleteEvent(el) {
        const delete_btn = el.querySelector('a[href="#delete"]');
        const delete_modal = DeleteModal.getModal('Delete ToDo', 'Are you sure you want to delete this ToDo?');
        const bs_modal = new bootstrap.Modal(delete_modal);

        delete_btn.addEventListener('click', (e) => {
            document.querySelector('body').appendChild(delete_modal);
            bs_modal.show();
        });

        delete_modal.querySelector('button.btn-danger').addEventListener('click', (e) => {
            e.preventDefault();

            ToDos.delete(delete_btn.dataset.entity);
            bs_modal.hide();
        });

        delete_modal.addEventListener('hidden.bs.modal', (e) => {
            delete_modal.remove();
        });
    }

    static completeEvent(el) {
        // complete event
        const checkbox = el.querySelector('input[type="checkbox"]')
        checkbox.addEventListener('click', (e) => {
            if(checkbox.checked) {
                ToDos.complete(checkbox.dataset.entity);
            }
        });
    }

    static removeAll() {
        ToDosHelper.LIST_GROUP.innerHTML = '';
    }

    static remove(id) {
        const el = document.querySelector(`div.list-group-item a[data-entity="${id}"]`);
        el.parentElement.parentElement.remove();
    }

    static complete(id) {
        const el = document.querySelector(`div.list-group-item input[data-entity="${id}"]`);
        ToDosHelper.setCompleted(el.parentElement);
    }

    static setCompleted(el) {
        let checkbox, parentEl, badges;
        if (el instanceof HTMLTemplateElement) {
            checkbox = el.content.querySelector('input[type="checkbox"]');
            parentEl = el.content.firstChild;
            badges = el.content.querySelectorAll('.badge');
        } else {
            checkbox = el.querySelector('input[type="checkbox"]');
            parentEl = el;
            badges = el.querySelectorAll('.badge');
        }

        checkbox.checked = true;
        checkbox.setAttribute('disabled', 'disabled');

        parentEl.classList.add('text-muted', 'text-decoration-line-through');

        badges.forEach((el) => {
            el.classList.remove('bg-primary');
            el.classList.add('bg-secondary');
        });
    }
}
