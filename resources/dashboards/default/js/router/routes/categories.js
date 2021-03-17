import Category from '@/store/models/category';

import CategoryIndex from '@/views/categories/index';
import CategoryCreate from '@/views/categories/create';
import CategoryEdit from '@/views/categories/edit';

export default [{
    path: '/categories',
    name: 'categories.index',
    component: CategoryIndex,
    meta: {
        title: 'Categories'
    },
    props: route => ({
        model: Category,
    }),
}, {
    path: '/categories/create',
    name: 'categories.create',
    component: CategoryCreate,
    meta: {
        title: 'Create category'
    },
    props: route => ({
        model: Category,
    }),
}, {
    path: '/categories/:id/edit',
    name: 'categories.edit',
    component: CategoryEdit,
    meta: {
        title: 'Edit category'
    },
    props: route => ({
        model: Category,
        id: parseInt(route.params.id, 10),
    }),
}]
