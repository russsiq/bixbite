const mix = require('laravel-mix');
const isDashboard = process.env.npm_config_skin;

mix.options({
    processCssUrls: false
});

if (isDashboard) {
    const SKIN = process.env.APP_SKIN;
    const SKIN_PATH = `./resources/dashboard/${SKIN}/`;

    mix.webpackConfig({
            resolve: {
                extensions: [
                    '.js',
                    '.vue',
                ],

                alias: {
                    '@': `${__dirname}/resources/dashboard/${SKIN}/js`
                }
            }
        })
        .sass(SKIN_PATH+'sass/dashboard.scss', 'public/css')
        .js(SKIN_PATH+'js/dashboard.js', 'public/js')
        .vue();
} else {
    mix.sass('resources/sass/app.scss', 'public/css')
        .js('resources/js/app.js', 'public/js');
}

// mix.postCss('resources/css/app.css', 'public/css', [
//     //
// ])

mix.inProduction() && mix.version([
    'public/js/app.js',
    'public/css/app.css',
    'public/js/dashboard.js',
    'public/css/dashboard.css',
]);
