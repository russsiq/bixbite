import File from '@/store/models/file';

import Files from '@/views/files/index';
// import FileEdit from '@/views/files/edit';
import Settings from '@/views/files/settings';

export default [{
    path: '/files',
    name: 'files',
    component: Files,
    meta: {
        title: 'Файловый менеджер'
    },
    props: route => ({
        model: File,
    }),
// }, {
//     path: '/files/:id/edit',
//     name: 'files.edit',
//     component: FileEdit,
//     meta: {
//         title: 'Редактирование файла'
//     },
//     props: route => ({
//         model: File,
//         id: parseInt(route.params.id, 10),
//     }),
}, {
    path: '/files/settings',
    name: 'files.settings',
    component: Settings,
    meta: {
        title: 'Настройки файлового менеджера'
    },
}];
