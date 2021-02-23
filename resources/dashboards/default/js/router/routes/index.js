import dashboard from './dashboard';
import unauthorized from './unauthorized';
import forbidden from './forbidden';
import notFound from './not-found';

import articles from './articles';
import categories from './categories';
import comments from './comments';
import tags from './tags';
import users from './users';

export default [
    ...dashboard,
    ...unauthorized,
    ...forbidden,
    ...notFound,

    ...articles,
    ...categories,
    ...comments,
    ...tags,
    ...users,
];
