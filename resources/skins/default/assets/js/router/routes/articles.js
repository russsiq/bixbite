import Article from '@/store/models/article';

import Articles from '@/views/articles/index';
import ArticleEdit from '@/views/articles/edit';
import Settings from '@/views/articles/settings';

export default [{
    path: '/articles',
    name: 'articles',
    component: Articles,
    meta: {
        title: 'Список записей'
    },
    props: route => ({
        model: Article,
    }),
}, {
    path: '/articles/:id/edit',
    name: 'articles.edit',
    component: ArticleEdit,
    meta: {
        title: 'Редактирование записи'
    },
    props: route => ({
        model: Article,
        id: parseInt(route.params.id, 10),
    }),
}, {
    path: '/articles/settings',
    name: 'articles.settings',
    component: Settings,
    meta: {
        title: 'Настройки записей'
    },
}];
