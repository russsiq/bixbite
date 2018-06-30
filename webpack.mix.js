let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

/*
 |--------------------------------------------------------------------------
 | 1 setPublicPath - set another pathto compiled files
 | 2 processCssUrls - set absolute path in compiled css files
 |--------------------------------------------------------------------------
 */


// mix.setPublicPath('./resources/skins/default/')
//     .js('resources/skins/default/assets/js/app.js', 'public/js')
//     .sass('resources/skins/default/assets/sass/app.scss', 'public/css')
//     .options({
//         processCssUrls: false
//     })
//
//     /* copy fonts to font-awesome*/
//     .copyDirectory('node_modules/font-awesome/fonts', './resources/skins/default/public/css/fonts/font-awesome')
//
//     /* copy ckeditor distr files and folders */
//     .copy('node_modules/ckeditor/config.js', './resources/skins/default/public/js/ckeditor/config.js')
//     .copy('node_modules/ckeditor/styles.js', './resources/skins/default/public/js/ckeditor/styles.js')
//     .copy('node_modules/ckeditor/contents.css', './resources/skins/default/public/js/ckeditor/contents.css')
//     .copyDirectory('node_modules/ckeditor/skins', './resources/skins/default/public/js/ckeditor/skins')
//     .copyDirectory('node_modules/ckeditor/lang', './resources/skins/default/public/js/ckeditor/lang')
//     .copyDirectory('node_modules/ckeditor/plugins', './resources/skins/default/public/js/ckeditor/plugins')
//     .copyDirectory('node_modules/ckeditor/adapters', './resources/skins/default/public/js/ckeditor/adapters')
//    ;

mix.setPublicPath('./resources/themes/default/')
    .js('resources/themes/default/assets/js/app.js', 'public/js')
    .sass('resources/themes/default/assets/sass/app.scss', 'public/css')
    .options({
        processCssUrls: false
    })

    /* copy fonts to font-awesome*/
    .copyDirectory('node_modules/font-awesome/fonts', './resources/themes/default/public/css/fonts/font-awesome')
    ;
