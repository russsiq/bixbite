import Article from '@/store/models/article';

import ArticleIndex from '@/views/articles/index';
import ArticleCreate from '@/views/articles/create';
import ArticleEdit from '@/views/articles/edit';

export default [{
    path: '/articles',
    name: 'articles.index',
    component: ArticleIndex,
    meta: {
        title: 'Articles'
    },
    props: route => ({
        model: Article,
    }),
}, {
    path: '/articles/create',
    name: 'articles.create',
    component: ArticleCreate,
    meta: {
        title: 'Create article'
    },
    props: route => ({
        model: Article,
    }),
}, {
    path: '/articles/:id/edit',
    name: 'articles.edit',
    component: ArticleEdit,
    meta: {
        title: 'Edit article'
    },
    props: route => ({
        model: Article,
        id: parseInt(route.params.id, 10),
    }),
}]
