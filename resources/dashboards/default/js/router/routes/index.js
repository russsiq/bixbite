import dashboard from './dashboard';
import unauthorized from './unauthorized';
import forbidden from './forbidden';
import notFound from './not-found';

import articles from './articles';
import categories from './categories';

export default [
    ...dashboard,
    ...unauthorized,
    ...forbidden,
    ...notFound,

    ...articles,
    ...categories,
];
