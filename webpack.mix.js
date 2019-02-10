const mix = require('laravel-mix')

/**
 * Theme.
 */
mix.options({
    processCssUrls: false
})
.setPublicPath('./resources/themes/default/')
.js('resources/themes/default/assets/js/app.js', 'public/js')
.sass('resources/themes/default/assets/sass/app.scss', 'public/css')
.copyDirectory(
    'node_modules/font-awesome/fonts',
    './resources/themes/default/public/css/fonts/font-awesome'
)

// /**
//  * Skin.
//  */
// mix.options({
//     processCssUrls: false
// })
// .setPublicPath('./resources/skins/default/')
// .js('resources/skins/default/assets/js/app.js', 'public/js')
// .js('resources/skins/default/assets/js/code-editor.js', 'public/js')
// .sass('resources/skins/default/assets/sass/app.scss', 'public/css')
// .sass('resources/skins/default/assets/sass/code-editor.scss', 'public/css')
// .copyDirectory(
//     'node_modules/font-awesome/fonts',
//     './resources/skins/default/public/css/fonts/font-awesome'
// )

/**
 * ----------------------------------------------------------------------------
 * Mix Asset Management
 * ----------------------------------------------------------------------------
 * Mix provides a clean, fluent API for defining some Webpack build steps
 * for your Laravel application. By default, we are compiling the Sass
 * file for the application as well as bundling up all the JS files.
 * ----------------------------------------------------------------------------
 * Full API Mix. Altered
 * https://scotch.io/tutorials/using-laravel-mix-with-webpack-for-all-your-assets
 * ----------------------------------------------------------------------------
 *
    .js(src, output)
    .react(src, output) // Identical to mix.js(), but registers React Babel compilation.
    .extract(vendorLibs)
    .sass(src, output)
    .standaloneSass('src', output) // Faster, but isolated from Webpack.
    .fastSass('src', output) // Alias for mix.standaloneSass().
    .less(src, output)
    .stylus(src, output)
    .postCss(src, output, [require('postcss-some-plugin')()])
    .browserSync('my-site.dev')
    .combine(files, destination)
    .babel(files, destination) // Identical to mix.combine(), but also includes Babel compilation.
    .copy(from, to)
    .copyDirectory(fromDir, toDir) // copy folder from node_modules to our public folder.
    .minify(file)
    .sourceMaps() // Enable sourcemaps
    .version() // Enable versioning.
    .disableNotifications()
    .setPublicPath('path') // set another path to compiled public files.
    .setResourceRoot('path') // prefix for resource locators.
    .autoload({}) // Will be passed to Webpack's ProvidePlugin.
    .webpackConfig({}) // Override webpack.config.js, without editing the file directly.
    .then(function () {}) // Will be triggered each time Webpack finishes building.
    .options({
        extractVueStyles: false, // Extract .vue component styling to file, rather than inline.
        processCssUrls: true, // Process/optimize relative compiled stylesheet url()'s. Set to false, if you don't want them touched.
        purifyCss: false, // Remove unused CSS selectors.
        uglify: {}, // Uglify-specific options. https://webpack.github.io/docs/list-of-plugins.html#uglifyjsplugin
        postCss: [] // Post-CSS options: https://github.com/postcss/postcss/blob/master/docs/plugins.md
    })
*/
