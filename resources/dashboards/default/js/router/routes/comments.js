import Comment from '@/store/models/comment';

import CommentIndex from '@/views/comments/index';
import CommentEdit from '@/views/comments/edit';

export default [{
    path: '/comments',
    name: 'comments.index',
    component: CommentIndex,
    meta: {
        title: 'Comment'
    },
    props: route => ({
        model: Comment,
    }),
}, {
    path: '/comments/:id/edit',
    name: 'comments.edit',
    component: CommentEdit,
    meta: {
        title: 'Edit comment'
    },
    props: route => ({
        model: Comment,
        id: parseInt(route.params.id, 10),
    }),
}]
