import Attachment from '@/store/models/attachment';

import Attachments from '@/views/attachments/index';
// import AttachmentEdit from '@/views/attachments/edit';
import Settings from '@/views/attachments/settings';

export default [{
    path: '/attachments',
    name: 'attachments',
    component: Attachments,
    meta: {
        title: 'Файловый менеджер'
    },
    props: route => ({
        model: Attachment,
    }),
// }, {
//     path: '/attachments/:id/edit',
//     name: 'attachments.edit',
//     component: AttachmentEdit,
//     meta: {
//         title: 'Редактирование файла'
//     },
//     props: route => ({
//         model: Attachment,
//         id: parseInt(route.params.id, 10),
//     }),
}, {
    path: '/attachments/settings',
    name: 'attachments.settings',
    component: Settings,
    meta: {
        title: 'Настройки файлового менеджера'
    },
}];
