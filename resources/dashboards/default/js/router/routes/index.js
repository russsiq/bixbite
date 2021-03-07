import dashboard from './dashboard';

import articles from './articles';
import categories from './categories';
import comments from './comments';
import tags from './tags';
import users from './users';

import errors from './errors/index';

export default [
    ...dashboard,

    ...articles,
    ...categories,
    ...comments,
    ...tags,
    ...users,

    ...errors, {
        path: '*',
        redirect: {
            name: 'not-found'
        },
    }
];
