import Template from '@/store/models/template';

import Templates from '@/views/templates/index';

export default [{
    path: '/templates',
    name: 'templates',
    component: Templates,
    meta: {
        title: 'Редактор шаблонов текущей темы сайта'
    },
    props: route => ({
        model: Template,
    }),
}];
