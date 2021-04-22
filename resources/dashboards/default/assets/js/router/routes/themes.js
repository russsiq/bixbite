import Themes from '@/views/themes/index'


// Route::resource('themes/templates', 'TemplatesController')->except(['create', 'show'])->names(['destroy' => 'templates.delete'])->middleware(['can:themes']);
// Route::resource('themes', 'ThemesController')->only(['index'])->middleware(['can:themes']);

export default [{
    path: '/themes',
    name: 'themes',
    component: Themes,
    meta: {
        title: 'Themes on the your site.'
    }
}]
