import { ToDosHelper } from '../helpers';
import { ToDos as ToDosAPI } from '../api';

document.addEventListener('DOMContentLoaded', (e) => {
    ToDosHelper.setEvents();
});

document.addEventListener(ToDosAPI.POST_DELETE_EVENT, (event) => {
    ToDosHelper.remove(event.detail.id);
}, false);