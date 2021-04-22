import NotFound from '@/views/errors/404'

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
