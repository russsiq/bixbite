import Privilege from '@/store/models/privilege';

import Privileges from '@/views/privileges/index'

export default [{
    path: '/privileges',
    name: 'privileges',
    component: Privileges,
    meta: {
        title: 'Привилегии пользователей'
    },
    props: route => ({
        model: Privilege,
    }),
}]
