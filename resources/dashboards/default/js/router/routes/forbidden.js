import Forbidden from '@/views/errors/http-forbidden';

export default [{
    path: '/forbidden',
    name: 'forbidden',
    component: Forbidden,
    meta: {
        title: '403. Forbidden'
    },
}]
