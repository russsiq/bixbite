/**
 * @cmd `npm run prod`
 * @cmd `npm run prod --dashboard`
 * @cmd `npm run watch`
 * @cmd `npm run watch --dashboard`
 */

const mix = require('laravel-mix');
const isDashboard = process.env.npm_config_dashboard;

mix.options({
    processCssUrls: false
});

if (isDashboard) {
    const DASHBOARD = process.env.APP_DASHBOARD;
    const DASHBOARD_PATH = `./resources/dashboards/${DASHBOARD}/`;

    mix.webpackConfig({
            resolve: {
                extensions: [
                    '.js',
                    '.vue',
                ],

                alias: {
                    '@': `${__dirname}/resources/dashboards/${DASHBOARD}/js`
                }
            }
        })
        .sass(DASHBOARD_PATH+'sass/dashboard.scss', 'public/css')
        .js(DASHBOARD_PATH+'js/dashboard.js', 'public/js')
        .vue();
} else {
    const THEME = process.env.APP_THEME;
    const THEME_PATH = `./resources/themes/${THEME}/`;

    mix.sass(THEME_PATH+'sass/app.scss', 'public/css')
        .js(THEME_PATH+'js/app.js', 'public/js');
}

// mix.postCss('resources/css/app.css', 'public/css', [
//     //
// ])

mix.version([
    'public/js/app.js',
    'public/css/app.css',
    'public/js/dashboard.js',
    'public/css/dashboard.css',
]);
