import User from '@/store/models/user';

import Users from '@/views/users/index';
import UserEdit from '@/views/users/edit';
import Settings from '@/views/users/settings';

export default [{
    path: '/users',
    name: 'users',
    component: Users,
    meta: {
        title: 'Список пользователей'
    },
    props: route => ({
        model: User,
    }),
}, {
    path: '/users/:id/edit',
    name: 'user.edit',
    component: UserEdit,
    meta: {
        title: 'Редактирование пользователя'
    },
    props: route => ({
        model: User,
        id: parseInt(route.params.id, 10),
    }),
}, {
    path: '/users/settings',
    name: 'users.settings',
    component: Settings,
    meta: {
        title: 'Настройки пользователей'
    },
}]
