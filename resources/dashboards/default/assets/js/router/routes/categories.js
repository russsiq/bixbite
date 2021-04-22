import Category from '@/store/models/category';

import Categories from '@/views/categories/index';
import CategoryEdit from '@/views/categories/edit';
import Settings from '@/views/categories/settings';

export default [{
    path: '/categories',
    name: 'categories',
    component: Categories,
    meta: {
        title: 'Список категорий сайта'
    },
    props: route => ({
        model: Category,
    }),
}, {
    path: '/categories/create',
    name: 'categories.create',
    component: CategoryEdit,
    meta: {
        title: 'Создание категории'
    },
    props: route => ({
        model: Category,
        id: 0,
    }),
}, {
    path: '/categories/:id/edit',
    name: 'categories.edit',
    component: CategoryEdit,
    meta: {
        title: 'Редактирование категории'
    },
    props: route => ({
        model: Category,
        id: parseInt(route.params.id, 10),
    }),
}, {
    path: '/categories/settings',
    name: 'categories.settings',
    component: Settings,
    meta: {
        title: 'Настройки категорий'
    },
}];
