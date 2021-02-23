import NotFound from '@/views/errors/http-not-found';

export default [{
//     path: '/404',
//     name: 'not-found',
//     component: NotFound,
//     meta: {
//         title: () => '404. Not found'
//     },
// }, {
    path: '*',
    // redirect: '/404',
    name: 'not-found',
    component: NotFound,
    meta: {
        title: '404. Не найдено'
    },
}]
