import XField from '@/store/models/x_field';

import XFields from '@/views/x_fields/index';
import XFieldEdit from '@/views/x_fields/edit';

export default [{
    path: '/x_fields',
    name: 'x_fields',
    component: XFields,
    meta: {
        title: 'Дополнительные поля'
    },
    props: route => ({
        model: XField,
    }),
}, {
    path: '/x_fields/create',
    name: 'x_fields.create',
    component: XFieldEdit,
    meta: {
        title: 'Создание дополнительного поля'
    },
    props: route => ({
        model: XField,
        id: 0,
    }),
}, {
    path: '/x_fields/:id/edit',
    name: 'x_fields.edit',
    component: XFieldEdit,
    meta: {
        title: 'Редактирование дополнительного поля'
    },
    props: route => ({
        model: XField,
        id: parseInt(route.params.id, 10),
    }),
}];
