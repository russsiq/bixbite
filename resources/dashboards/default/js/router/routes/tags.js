import Tag from '@/store/models/tag';

import TagIndex from '@/views/tags/index';
import TagEdit from '@/views/tags/edit';

export default [{
    path: '/tags',
    name: 'tags.index',
    component: TagIndex,
    meta: {
        title: 'Tags'
    },
    props: route => ({
        model: Tag,
    }),
}, {
    path: '/tags/:id/edit',
    name: 'tags.edit',
    component: TagEdit,
    meta: {
        title: 'Edit tag'
    },
    props: route => ({
        model: Tag,
        id: parseInt(route.params.id, 10),
    }),
}]
