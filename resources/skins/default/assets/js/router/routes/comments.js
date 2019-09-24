import Comment from '@/store/models/comment';

import Categories from '@/views/comments/index';
import CommentEdit from '@/views/comments/edit';
import Settings from '@/views/comments/settings';

export default [{
    path: '/comments',
    name: 'comments',
    component: Categories,
    meta: {
        title: 'Список комментариев сайта'
    },
    props: route => ({
        model: Comment,
    }),
}, {
    path: '/comments/:id/edit',
    name: 'comments.edit',
    component: CommentEdit,
    meta: {
        title: 'Редактирование комментария'
    },
    props: route => ({
        model: Comment,
        id: parseInt(route.params.id, 10),
    }),
}, {
    path: '/comments/settings',
    name: 'comments.settings',
    component: Settings,
    meta: {
        title: 'Настройки комментариев'
    },
}];
