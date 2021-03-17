import User from '@/store/models/user';

import UserIndex from '@/views/users/index';
import UserCreate from '@/views/users/create';
import UserEdit from '@/views/users/edit';

export default [{
    path: '/users',
    name: 'users.index',
    component: UserIndex,
    meta: {
        title: 'Users'
    },
    props: route => ({
        model: User,
    }),
}, {
    path: '/users/create',
    name: 'users.create',
    component: UserCreate,
    meta: {
        title: 'Create user'
    },
    props: route => ({
        model: User,
    }),
}, {
    path: '/users/:id/edit',
    name: 'users.edit',
    component: UserEdit,
    meta: {
        title: 'Edit user'
    },
    props: route => ({
        model: User,
        id: parseInt(route.params.id, 10),
    }),
}]
