import ErrorComponent from '@/views/errors/index';
import ErrorCard from '@/views/errors/error-card';

export default [{
    path: '/error',
    component: ErrorComponent,
    children: [
        {
            path: 'unauthorized',
            name: 'unauthorized',
            component: ErrorCard,
            meta: {
                title: '401. Unauthorized',
                description: 'To access the resource / document, a password is required, or the user must be registered.',
            },
        }, {
            path: 'forbidden',
            name: 'forbidden',
            component: ErrorCard,
            meta: {
                title: '403. Forbidden',
                description: 'Access to the resource / document is forbidden.',
            },
        }, {
            path: 'not-found',
            name: 'not-found',
            component: ErrorCard,
            meta: {
                title: '404. Not found',
                description: 'Sorry, but the resource / document you requested could not be found.',
            },
        }, {
            path: 'expired',
            name: 'expired',
            component: ErrorCard,
            meta: {
                title: '419. Page Expired',
                description: 'The page has expired due to inactivity.',
            },
        }, {
            path: 'many-requests',
            name: 'many-requests',
            component: ErrorCard,
            meta: {
                title: '429. Too Many Requests',
                description: 'Too many requests sent in a short time.',
            },
        },
    ]
}];
