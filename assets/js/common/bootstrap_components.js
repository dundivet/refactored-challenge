class Modal {
    static TEMPLATE = `<div class="modal fade" id=":idmodal:" tabindex="-1" aria-labelledby=":label:" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id=":label:">:title:</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">:body:</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>`;

    static _button(value = 'Save changes', classList = ['btn', 'btn-primary'], options = {dismiss: false}) {
        const btn = document.createElement('button');
        btn.innerText = value;
        btn.classList.add(...classList);
        if (options.dismiss) {
            btn.dataset.bsDismiss = 'modal';
        }

        return btn;
    }
}

export class DeleteModal extends Modal {

    static getModal(title, message) {
        const template = document.createElement('template');
        template.innerHTML = Modal.TEMPLATE;

        const btnDelete = super._button('Delete', ['btn', 'btn-danger']);
        const btnCancel = super._button('Cancel', ['btn', 'btn-secondary'], {dismiss: true});

        template.content.firstChild.id = 'deleteModal';
        template.content.firstChild.setAttribute('aria-labelledby', 'deleteModalLabel');

        template.content.querySelector('.modal-title').innerHTML = title;
        template.content.querySelector('.modal-title').id = 'deleteModalLabel';
        template.content.querySelector('.modal-body').innerHTML = message;

        template.content.querySelector('.modal-footer').innerHTML = '';
        template.content.querySelector('.modal-footer').append(btnDelete, btnCancel);

        return template.content.firstChild;
    }
}
