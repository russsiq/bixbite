import { Database } from '@vuex-orm/core';

import Article from '@/store/models/article';
import Category from '@/store/models/category';
import Categoryable from '@/store/models/categoryable';
import Comment from '@/store/models/comment';
import Attachment from '@/store/models/attachment';
import Note from '@/store/models/note';
// import Privilege from '@/store/models/privilege';
import Setting from '@/store/models/setting';
import Tag from '@/store/models/tag';
import Taggable from '@/store/models/taggable';
import Template from '@/store/models/template';
import User from '@/store/models/user';
import XField from '@/store/models/x_field';

import articles from './modules/articles';
import categories from './modules/categories';
import categoryables from './modules/categoryables';
import comments from './modules/comments';
import attachments from './modules/attachments';
import notes from './modules/notes';
// import privileges from './modules/privileges';
import settings from './modules/settings';
import tags from './modules/tags';
import taggables from './modules/taggables';
import templates from './modules/templates';
import users from './modules/users';
import x_fields from './modules/x_fields';

// Create a new database instance.
const database = new Database();

// Register Models to the database.
database.register(Article, articles);
database.register(Category, categories);
database.register(Categoryable, categoryables);
database.register(Comment, comments);
database.register(Attachment, attachments);
database.register(Note, notes);
// database.register(Privilege, privileges);
database.register(Setting, settings);
database.register(Tag, tags);
database.register(Taggable, taggables);
database.register(Template, templates);
database.register(User, users);
database.register(XField, x_fields);

export default database;
