import Article from '@/store/models/article';

import ArticleIndex from '@/views/articles/index';
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
