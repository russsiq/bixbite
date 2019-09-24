/**
 *  Imports all of the routes used in the application to build the router.
 */
import auth from './auth';
import dashboard from './dashboard';
import notFound from './not-found';

import articles from './articles';
import categories from './categories';
import comments from './comments';
import files from './files';
import notes from './notes';
import privileges from './privileges';
import settings from './settings';
import templates from './templates';
import themes from './themes';
import users from './users';
import xFields from './x_fields';

export default [
    ...auth,
    ...dashboard,
    ...notFound,

    ...articles,
    ...categories,
    ...comments,
    ...files,
    ...notes,
    ...privileges,
    ...settings,
    ...templates,
    ...themes,
    ...users,
    ...xFields,
];
