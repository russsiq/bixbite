import Unauthorized from '@/views/errors/http-unauthorized';

export default [{
    path: '/unauthorized',
    name: 'unauthorized',
    component: Unauthorized,
    meta: {
        title: '401. Unauthorized'
    },
}]
