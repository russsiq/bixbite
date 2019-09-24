import Note from '@/store/models/note';

import Notes from '@/views/notes/index';
import NoteEdit from '@/views/notes/edit';
// import Settings from '@/views/notes/settings';

export default [{
    path: '/notes',
    name: 'notes',
    component: Notes,
    meta: {
        title: 'Список заметок'
    },
    props: route => ({
        model: Note,
    }),
}, {
    path: '/notes/create',
    name: 'notes.create',
    component: NoteEdit,
    meta: {
        title: 'Создание заметки'
    },
    props: route => ({
        model: Note,
        id: 0,
    }),
}, {
    path: '/notes/:id/edit',
    name: 'note.edit',
    component: NoteEdit,
    meta: {
        title: 'Редактирование заметки'
    },
    props: route => ({
        model: Note,
        id: parseInt(route.params.id, 10),
    }),
// }, {
//     path: '/notes/settings',
//     name: 'notes.settings',
//     component: Settings,
//     meta: {
//         title: 'Настройки пользователей'
//     },
}];
